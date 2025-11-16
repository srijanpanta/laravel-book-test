<script setup>
import { ref, reactive, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  book: Object,
  auth: Object,
  showModal: Boolean,
  errors: Object
})

const emit = defineEmits(['close', 'saved'])

/* Validation Errors from Inertia */
const errors = computed(() => usePage().props.errors)

/* Form state */
const form = reactive({
  title: props.book?.title || '',
  author: props.book?.author || '',
  description: props.book?.description || '',
  tags: props.book?.tags || [],
  image: props.book?.image || null,
  newImage: null
})

const newTag = ref('')

/* Add / remove tags */
function addTag() {
  const tag = newTag.value.trim()
  if (tag && !form.tags.includes(tag)) form.tags.push(tag)
  newTag.value = ''
}

function removeTag(tag) {
  form.tags = form.tags.filter(t => t !== tag)
}

/* Handle file input */
function handleFileChange(e) {
  const file = e.target.files[0]
  if (file) form.newImage = file
}

function submitForm() {
  const payload = new FormData()
  payload.append('title', form.title)
  payload.append('author', form.author)
  payload.append('description', form.description || '')
  payload.append('tags', JSON.stringify(form.tags || []))

  if (form.newImage instanceof File) {
    payload.append('image', form.newImage)
  }

  if (props.book) {
    payload.append('_method', 'PUT') // method spoofing for Laravel
  }

  const url = props.book ? `/books/${props.book.id}` : '/books'

  router.post(url, payload, {
    preserveScroll: true,
    onSuccess: () => { emit('saved'); emit('close'); }
  })
}

/* Computed for preview URL */
const newImageUrl = computed(() => {
  return form.newImage instanceof File ? URL.createObjectURL(form.newImage) : null
})
</script>

<template>
  <Transition name="fade" appear>
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-2">

      <Transition name="pop" appear>
        <div
          class="relative bg-white dark:bg-gray-900 rounded-lg sm:rounded-2xl shadow-2xl w-full h-full sm:h-auto max-h-[95vh] sm:max-w-4xl overflow-y-auto flex flex-col sm:flex-row transform transition-transform duration-300 z-10"
        >

          <!-- Image Section -->
          <div class="w-full sm:w-1/3 h-40 sm:h-auto sm:aspect-[3/4] bg-gray-200 dark:bg-gray-800 flex items-center justify-center relative">

            <!-- New uploaded image -->
            <img
              v-if="newImageUrl"
              :src="newImageUrl"
              alt="Preview"
              class="w-full h-full object-cover rounded-t-lg sm:rounded-l-lg sm:rounded-t-none"
            />

            <!-- Existing image -->
            <img
              v-else-if="form.image && !form.newImage"
              :src="`storage/images/${form.image}`"
              alt="Book Cover"
              class="w-full h-full object-cover rounded-t-lg sm:rounded-l-lg sm:rounded-t-none"
            />

            <!-- Placeholder -->
            <div v-else class="text-gray-500 dark:text-gray-400 flex flex-col items-center">
              <font-awesome-icon :icon="['fas', 'image']" class="text-7xl opacity-60" />
              <span class="mt-2 text-sm">No Image</span>
            </div>

            <!-- Upload Icon -->
            <label
              for="fileInput"
              class="absolute top-2 right-2 bg-white/80 dark:bg-gray-800/80 p-2 rounded-full cursor-pointer hover:bg-white dark:hover:bg-gray-700 transition flex items-center justify-center shadow"
              title="Upload Image"
            >
              <font-awesome-icon :icon="['fas', 'pen']" class="text-gray-700 dark:text-gray-200" />
            </label>
            <input id="fileInput" type="file" class="hidden" accept="image/*" @change="handleFileChange" />

            <!-- Image validation error -->
            <p v-if="errors.image" class="absolute bottom-2 left-2 text-red-500 text-xs bg-white/70 px-2 py-1 rounded">
              {{ errors.image }}
            </p>
          </div>

          <!-- Form Section -->
          <div class="w-full sm:w-2/3 p-4 sm:p-6 flex flex-col justify-between">
            <div class="space-y-4">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ props.book ? 'Edit Book' : 'Add Book' }}
              </h2>

              <!-- Title -->
              <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-800 dark:text-white text-sm"
                />
                <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
              </div>

              <!-- Author -->
              <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Author</label>
                <input
                  v-model="form.author"
                  type="text"
                  class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-800 dark:text-white text-sm"
                />
                <p v-if="errors.author" class="text-red-500 text-sm mt-1">{{ errors.author }}</p>
              </div>

              <!-- Description -->
              <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Description</label>
                <textarea
                  v-model="form.description"
                  rows="3"
                  class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-800 dark:text-white text-sm"
                ></textarea>
                <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
              </div>

              <!-- Tags -->
              <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Tags</label>
                <div class="flex flex-wrap gap-2 mb-2">
                  <span
                    v-for="tag in form.tags"
                    :key="tag"
                    class="bg-purple-100 dark:bg-purple-700 text-purple-800 dark:text-purple-200 px-2 py-0.5 rounded-full flex items-center gap-1 text-sm"
                  >
                    {{ tag }}
                    <button @click="removeTag(tag)" class="text-gray-500 hover:text-gray-800 dark:hover:text-white text-xs">×</button>
                  </span>
                </div>

                <div class="flex gap-2">
                  <input
                    v-model="newTag"
                    type="text"
                    placeholder="Add tag"
                    class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 dark:bg-gray-800 dark:text-white text-sm"
                  />
                  <button
                    @click.prevent="addTag"
                    class="bg-purple-600 text-white px-3 py-1 rounded-lg hover:bg-purple-700 transition text-sm"
                  >
                    Add
                  </button>
                </div>

                <p v-if="errors.tags" class="text-red-500 text-sm mt-1">{{ errors.tags }}</p>
              </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
              <button
                @click="$emit('close')"
                class="px-4 py-2 rounded-xl bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold hover:bg-gray-400 dark:hover:bg-gray-600 transition text-sm"
              >
                Cancel
              </button>
              <button
                @click="submitForm"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold hover:from-purple-700 hover:to-blue-600 transition text-sm"
              >
                {{ props.book ? 'Save Changes' : 'Add Book' }}
              </button>
            </div>
          </div>

        </div>
      </Transition>
    </div>
  </Transition>
</template>

<style>
.fade-enter-active,
.fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.pop-enter-active,
.pop-leave-active { transition: all 0.35s cubic-bezier(0.25,0.8,0.25,1); }
.pop-enter-from, .pop-leave-to { opacity: 0; transform: translateY(-20px) scale(0.95); }
</style>

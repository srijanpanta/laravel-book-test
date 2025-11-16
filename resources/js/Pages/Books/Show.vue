<script setup>
import { defineProps, defineEmits } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faImage } from '@fortawesome/free-solid-svg-icons'

const props = defineProps({
  book: Object,
  auth: Object,
  show: Boolean
})

const emit = defineEmits(['close', 'edit', 'delete'])

function handleEdit() {
  emit('edit', props.book)
  emit('close')
}

function handleDelete() {
  if (!confirm(`Delete "${props.book.title}"?`)) return
  emit('delete', props.book)
  emit('close')
}

// Tag colors
const tagColors = [
  'bg-purple-100 dark:bg-purple-700 text-purple-800 dark:text-purple-200',
  'bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-200',
  'bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-blue-200',
  'bg-yellow-100 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-200',
  'bg-pink-100 dark:bg-pink-700 text-pink-800 dark:text-pink-200',
  'bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-200',
  'bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-200',
]

function getTagColor(tag) {
  const index = tag.toLowerCase().charCodeAt(0) % tagColors.length
  return tagColors[index]
}
</script>

<template>
  <Transition name="fade" appear>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center px-4 md:px-0">
      
      <!-- Overlay -->
      <Transition name="fade" appear>
        <div
          class="absolute inset-0 bg-black/60"
          @click="$emit('close')"
        ></div>
      </Transition>

      <!-- Modal content -->
      <Transition name="pop" appear>
        <div
          class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-4xl h-[550px] md:h-[500px] overflow-hidden flex flex-col md:flex-row transform transition-transform duration-300 z-10 border border-gray-200 dark:border-gray-700"
        >
          <!-- Book Image -->
          <div class="w-full md:w-1/3 h-48 md:h-full flex-shrink-0 overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-gray-800">
            <template v-if="book.image">
              <img
                :src="`storage/images/${book.image}`"
                :alt="book.title"
                class="w-full h-full object-cover object-center"
              />
            </template>
            <template v-else>
              <FontAwesomeIcon :icon="faImage" class="text-gray-400 dark:text-gray-500 text-6xl md:text-8xl" />
            </template>
          </div>

          <!-- Book Details -->
          <div class="w-full md:w-2/3 p-6 md:p-8 flex flex-col justify-between overflow-y-auto">
            <div>
              <h2 class="text-2xl md:text-4xl font-extrabold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-500">
                {{ book.title }}
              </h2>
              <p class="text-sm md:text-lg text-gray-600 dark:text-gray-300 mb-4 italic">by {{ book.author }}</p>
              <p class="text-gray-700 dark:text-gray-200 mb-4 leading-relaxed text-sm md:text-base">
                {{ book.description }}
              </p>

              <div v-if="book.tags?.length" class="flex flex-wrap gap-2 mt-2">
                <span
                  v-for="tag in book.tags"
                  :key="tag"
                  :class="getTagColor(tag) + ' px-3 py-1 rounded-full text-xs md:text-sm font-medium'"
                >
                  {{ tag }}
                </span>
              </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex flex-wrap justify-end gap-2 md:gap-3">
              <button
                v-if="auth?.user"
                @click="handleEdit"
                class="px-4 md:px-5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold hover:from-purple-700 hover:to-blue-600 transition shadow-md hover:shadow-lg text-sm md:text-base"
              >
                Edit
              </button>
              <button
                v-if="auth?.user"
                @click="handleDelete"
                class="px-4 md:px-5 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition shadow-md hover:shadow-lg text-sm md:text-base"
              >
                Delete
              </button>
              <button
                @click="$emit('close')"
                class="px-4 md:px-5 py-2 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition text-sm md:text-base"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<style>
/* Fade overlay */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.fade-enter-to,
.fade-leave-from {
  opacity: 1;
}

/* Pop modal: scale + opacity + slide from top */
.pop-enter-active,
.pop-leave-active {
  transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.pop-enter-from,
.pop-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(0.95);
}
.pop-enter-to,
.pop-leave-from {
  opacity: 1;
  transform: translateY(0) scale(1);
}
</style>

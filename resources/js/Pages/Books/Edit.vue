<script setup>
import { ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  book: Object,
  showModal: Boolean,
  auth: Object
})

const emit = defineEmits(['close'])

// Form state
let form = useForm({ ...props.book })

// Reset form when the book changes
watch(() => props.book, (newBook) => {
  form = useForm({ ...newBook })
})

// Submit update
function submit() {
  form.put(`/books/${props.book.id}`, {
    onSuccess: () => emit('close')
  })
}

// Delete book
function deleteBook() {
  if (!confirm(`Delete "${props.book.title}"?`)) return
  form.delete(`/books/${props.book.id}`, {
    onSuccess: () => {
      emit('close')
      router.visit('/books')
    }
  })
}

// Close modal
function closeModal() {
  emit('close')
}
</script>

<template>
  <Transition name="fade" appear>
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
      <!-- Overlay -->
      <Transition name="fade" appear>
        <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
      </Transition>

      <!-- Modal content -->
      <Transition name="pop" appear>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden transform transition-transform duration-300 z-10">
          <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-500">
                Edit Book
              </h3>
              <button @click="closeModal" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="pt-6 space-y-5">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                  <input
                    v-model="form.title"
                    required
                    class="mt-1 w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:outline-none text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Author</label>
                  <input
                    v-model="form.author"
                    required
                    class="mt-1 w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:outline-none text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
                <input
                  v-model="form.tags"
                  placeholder="Comma-separated"
                  class="mt-1 w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:outline-none text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea
                  v-model="form.description"
                  rows="4"
                  class="mt-1 w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:outline-none text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image URL</label>
                <input
                  v-model="form.image"
                  class="mt-1 w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:outline-none text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700"
                />
              </div>

              <!-- Footer Actions -->
              <div class="flex justify-between items-center mt-6 flex-wrap gap-2">
                <button
                  type="button"
                  @click="deleteBook"
                  class="text-red-600 hover:text-red-800 font-semibold"
                >
                  Delete
                </button>

                <div class="flex space-x-3">
                  <button
                    type="button"
                    @click="closeModal"
                    class="px-5 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold hover:from-purple-700 hover:to-blue-600 transition"
                  >
                    Update
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<style>
/* Overlay fade */
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

/* Pop modal */
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

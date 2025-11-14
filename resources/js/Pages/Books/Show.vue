<script setup>
import { defineProps, defineEmits } from 'vue'

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
}
</script>

<template>
  <Transition name="fade" appear>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center px-4">
      
      <!-- Overlay -->
      <Transition name="fade" appear>
        <div
          class="absolute inset-0 bg-black/50"
          @click="$emit('close')"
        ></div>
      </Transition>

      <!-- Modal content -->
      <Transition name="pop" appear>
        <div
          class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row transform transition-transform duration-300 z-10"
        >
          <!-- Book Image -->
          <div class="md:w-1/3 w-full h-96 flex-shrink-0">
            <img
              :src="book.image ? `/images/${book.image}` : '/images/placeholder.png'"
              :alt="book.title"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Book Details -->
          <div class="md:w-2/3 w-full p-6 flex flex-col justify-between">
            <div>
              <h2 class="text-3xl font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-500">
                {{ book.title }}
              </h2>
              <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">by {{ book.author }}</p>
              <p class="text-gray-700 dark:text-gray-200 mb-4">{{ book.description }}</p>
              <p v-if="book.tags" class="text-sm text-gray-500 dark:text-gray-400">
                <span class="font-semibold">Tags:</span> {{ book.tags }}
              </p>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex flex-wrap justify-end gap-3">
              <button v-if="auth?.user" @click="handleEdit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold hover:from-purple-700 hover:to-blue-600 transition">
                Edit
              </button>
              <button v-if="auth?.user" @click="handleDelete" class="px-5 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                Delete
              </button>
              <button @click="$emit('close')" class="px-5 py-2 rounded-xl bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold hover:bg-gray-400 dark:hover:bg-gray-600 transition">
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

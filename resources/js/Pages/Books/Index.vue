<template>
  <BooksLayout :auth="auth">
    <Head title="Books" />

    <main class="bg-gray-50 dark:bg-gray-900 min-h-screen mt-0 pt-0 pb-0">
      <!-- Hero Section -->
      <section class="relative bg-gradient-to-r from-purple-600 to-blue-500 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
          <h1 class="text-5xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">
            Explore the World of Books
          </h1>
          <p class="text-lg md:text-xl mb-8 drop-shadow-md">
            Find, read, and manage your favorite books with ease.
          </p>

          <!-- Search Bar -->
          <form class="max-w-md mx-auto" @submit.prevent="searchBooks">
            <div class="relative shadow-lg rounded-full overflow-hidden">
              <input
                v-model="search"
                type="text"
                placeholder="Search books..."
                class="w-full py-3 pl-6 pr-20 rounded-full focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 text-gray-800"
              />
              <button
                type="submit"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-purple-600 font-semibold px-5 py-2 rounded-full hover:bg-gray-100 transition"
              >
                🔍
              </button>
            </div>
          </form>
        </div>

        <!-- Decorative Shape -->
        <div class="absolute inset-0 pointer-events-none">
          <div
            class="absolute top-0 left-0 w-full h-full bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern.svg')] opacity-30"
          ></div>
        </div>
      </section>

      <!-- Books Grid -->
      <section id="books-section" class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center mb-10">
          Our Collection
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
          <div
            v-for="book in books.data"
            :key="book.id"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex flex-col overflow-hidden"
          >
            <!-- Book Image -->
            <img
              :src="book.image ? `/images/${book.image}` : '/images/placeholder.png'"
              :alt="book.title"
              class="h-56 w-full object-cover"
            />

            <!-- Book Info -->
            <div class="p-6 flex flex-col flex-1">
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ book.title }}</h3>
              <p class="text-gray-500 dark:text-gray-300 mb-4 text-sm">by {{ book.author }}</p>
              <p class="text-gray-700 dark:text-gray-200 flex-1 text-sm line-clamp-3">
                {{ book.description || 'No description available.' }}
              </p>

              <!-- Buttons -->
              <div class="mt-4 flex justify-between items-center">
                <button
                  @click="openViewModal(book)"
                  class="bg-gradient-to-r from-purple-600 to-blue-500 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-blue-600 shadow-md transition"
                >
                  Read More
                </button>
                <button
                  v-if="auth?.user"
                  @click="openEditModal(book)"
                  class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white px-3 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                >
                  Edit
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Modals -->
      <EditBook
        v-if="selectedEditBook && auth?.user"
        :auth="auth"
        :book="selectedEditBook"
        :show-modal="showEdit"
        @close="closeEditModal"
      />

      <Show
        v-if="selectedViewBook"
        :auth="auth"
        :book="selectedViewBook"
        :show="showView"
        @close="closeViewModal"
        @edit="openEditFromShow"
        @delete="deleteBook"
      />
    </main>
  </BooksLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import BooksLayout from '@/Layouts/BooksLayout.vue'
import EditBook from '@/Pages/Books/Edit.vue'
import Show from '@/Pages/Books/Show.vue'

const props = defineProps({
  books: Object,
  filters: Object,
  auth: Object
})

const search = ref(props.filters?.search || '')

// Edit modal
const showEdit = ref(false)
const selectedEditBook = ref(null)

// Show modal
const showView = ref(false)
const selectedViewBook = ref(null)

function searchBooks() {
  router.get(
    '/books',
    { search: search.value },
    {
      preserveState: true,
      onSuccess: () => {
        const booksSection = document.getElementById('books-section')
        if (booksSection) {
          booksSection.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
      }
    }
  )
}

function openEditModal(book) {
  if (!props.auth?.user) return
  selectedEditBook.value = { ...book }
  showEdit.value = true
  showView.value = false
  selectedViewBook.value = null
}

function closeEditModal() {
  showEdit.value = false
  selectedEditBook.value = null
}

function openViewModal(book) {
  selectedViewBook.value = { ...book }
  showView.value = true
}

function closeViewModal() {
  showView.value = false
  selectedViewBook.value = null
}

function openEditFromShow(book) {
  closeViewModal()
  openEditModal(book)
}

function deleteBook(book) {
  if (!props.auth?.user) return
  if (!confirm(`Are you sure you want to delete "${book.title}"?`)) return
  router.delete(`/books/${book.id}`, {
    onSuccess: () => {
      closeViewModal()
      closeEditModal()
    }
  })
}
</script>

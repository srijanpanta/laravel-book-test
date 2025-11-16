<template>
  <BooksLayout :auth="auth">
    <Head title="Books" />

    <main class="bg-gray-50 dark:bg-gray-900 min-h-screen pt-0">

      <!-- Hero Section -->
      <section class="relative bg-gradient-to-r from-purple-600 to-blue-500 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
          <h1 class="text-5xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">
            Explore My World Through Books
          </h1>
          <p class="text-lg md:text-xl mb-8 drop-shadow-md">
            Sample Book List
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
                class="absolute right-2 top-1/2 -translate-y-1/2 text-purple-600 font-semibold"
              >
                <font-awesome-icon icon="magnifying-glass" />
              </button>
            </div>
          </form>
        </div>
      </section>

      <!-- Books Grid -->
      <section id="books-section" class="max-w-7xl mx-auto px-4 py-16 transition-all duration-700 ease-in-out">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center mb-10">
          Our Collection
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
          <div
            v-for="book in books.data"
            :key="book.id"
            @click="openViewModal(book)"
            class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden"
          >
            <!-- Book Image -->
            <img
              :src="book.image ? `storage/images/${book.image}` : '/images/placeholder.png'"
              :alt="book.title"
              class="h-56 w-full object-cover"
            />

            <!-- Book Info -->
            <div class="p-4 flex flex-col flex-1">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ book.title }}</h3>
              <p class="text-gray-500 dark:text-gray-300 text-sm mb-2">by {{ book.author }}</p>

              <!-- Tags -->
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="tag in book.tags || []"
                  :key="tag"
                  :class="getTagColor(tag)"
                  class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                >
                  {{ tag }}
                </span>
              </div>
            </div>

            <!-- Edit icon -->
            <button
              v-if="auth?.user"
              @click.stop="openEditModal(book)"
              class="absolute top-2 right-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
              title="Edit"
            >
              <font-awesome-icon icon="edit" />
            </button>
          </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center items-center space-x-2">
          <button
            :disabled="!books.prev_page_url"
            @click.prevent="goToPage(books.prev_page_url)"
            class="px-3 py-1 rounded-md border bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>

          <button
            v-for="page in pagesArray"
            :key="page"
            @click.prevent="goToPageNumber(page)"
            :class="[
              'px-3 py-1 rounded-md border',
              page === books.current_page
                ? 'bg-purple-600 text-white border-purple-600'
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            {{ page }}
          </button>

          <button
            :disabled="!books.next_page_url"
            @click.prevent="goToPage(books.next_page_url)"
            class="px-3 py-1 rounded-md border bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </section>

      <!-- Floating Add Book Button -->
      <button
        v-if="auth?.user"
        @click="openCreateModal"
        class="fixed bottom-8 right-8 w-14 h-14 bg-purple-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-purple-700 transition z-50 pulse"
        title="Add Book"
      >
        <font-awesome-icon icon="plus" />
      </button>

      <!-- Footer -->
      <footer class="bottom-0 w-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-center py-6 mt-16">
        &copy; {{ new Date().getFullYear() }} Srijan Panta
      </footer>

      <!-- Modals -->
      <EditBook
        v-if="selectedEditBook && auth?.user"
        :auth="auth"
        :book="selectedEditBook"
        :show-modal="showEdit"
        @close="closeEditModal"
        @saved="refreshBooks"
      />

      <EditBook
        v-if="showCreate && auth?.user"
        :auth="auth"
        :show-modal="showCreate"
        @close="closeCreateModal"
        @saved="refreshBooks"
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
import { ref, computed } from 'vue'
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

// Modals
const showEdit = ref(false)
const showCreate = ref(false)
const selectedEditBook = ref(null)
const selectedViewBook = ref(null)
const showView = ref(false)

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

// Smooth search scroll after Inertia navigation
function searchBooks() {
  router.get(
    '/books',
    { search: search.value },
    {
      preserveState: true,
      onSuccess: () => {
        const section = document.getElementById('books-section')
        if (section) {
          section.scrollIntoView({ behavior: 'smooth' })
        }
      }
    }
  )
}

function openEditModal(book) {
  if (!props.auth?.user) return
  selectedEditBook.value = { ...book }
  showEdit.value = true
}

function closeEditModal() {
  showEdit.value = false
  selectedEditBook.value = null
}

function openCreateModal() {
  showCreate.value = true
}

function closeCreateModal() {
  showCreate.value = false
}

function refreshBooks() {
  router.reload()
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
  router.delete(`/books/${book.id}`, { onSuccess: refreshBooks })
}

// Pagination
const pagesArray = computed(() => {
  const pages = []
  for (let i = 1; i <= props.books.last_page; i++) {
    pages.push(i)
  }
  return pages
})

function goToPage(url) {
  if (!url) return

  router.get(url.replace(window.location.origin, ''), {}, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      const section = document.getElementById('books-section')
      if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    }
  })
}

function goToPageNumber(page) {
  router.get('/books', { page }, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      const section = document.getElementById('books-section')
      if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    }
  })
}
</script>

<style>
/* Pulse animation */
@keyframes pulse {
  0%,100% { transform: scale(1); box-shadow:0 0 0 0 rgba(139,92,246,0.7); }
  50% { transform: scale(1.05); box-shadow:0 0 10px 10px rgba(139,92,246,0.3); }
}
.pulse { animation: pulse 2s infinite; }
</style>

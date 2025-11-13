<template>
  <div>
    <h1>Books</h1>
    <form @submit.prevent="searchBooks">
      <input v-model="search" placeholder="Search books..." />
      <button type="submit">Search</button>
    </form>
    <ul>
      <li v-for="book in books" :key="book.id">
        <img :src="`/images/${book.image}`" alt="" width="80" />
        <strong>{{ book.title }}</strong> by {{ book.author }}
        (<Link :href="`/books/${book.id}/edit`">Edit</Link>)
      </li>
    </ul>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
const props = defineProps({ books: Array, filters: Object })
const search = ref(props.filters?.search || '')

function searchBooks() {
  router.get('/books', { search: search.value }, { preserveState: true })
}
</script>

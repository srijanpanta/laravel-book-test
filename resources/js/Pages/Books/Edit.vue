<template>
  <div>
    <h1>Edit Book</h1>
    <button @click="deleteBook" style="color:red;">Delete Book</button>
    <form @submit.prevent="submit">
      <input v-model="form.title" required />
      <input v-model="form.author" required />
      <input v-model="form.tags" />
      <textarea v-model="form.description"></textarea>
      <input v-model="form.image" />
      <button type="submit">Update Book</button>
    </form>
  </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
const props = defineProps({ book: Object })
const form = useForm({ ...props.book })
function submit() {
  form.put(`/books/${props.book.id}`)
}
function deleteBook() {
  form.delete(`/books/${props.book.id}`, {
    onSuccess: () => {
      router.visit('/books')
    }
  })
}

</script>

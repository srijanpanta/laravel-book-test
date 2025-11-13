<template>
  <div>
    <h1>Add New Book</h1>
    <form @submit.prevent="submit">
      <input v-model="form.title" placeholder="Title" required />
      <input v-model="form.author" placeholder="Author" required />
      <input v-model="form.tags" placeholder="Tags" />
      <textarea v-model="form.description" placeholder="Description" />
      <input v-model="form.image" placeholder="Cover image filename" />
      <button type="submit">Add Book</button>
    </form>
  </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
const form = useForm({
  title: '', author: '', tags: '', description: '', image: ''
})
function submit() { form.post('/books') }
</script>

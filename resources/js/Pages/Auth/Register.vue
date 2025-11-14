<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import BooksLayout from '@/Layouts/BooksLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation')
    })
}
</script>

<template>
  <BooksLayout :auth="{ user: null }">
    <Head title="Register" />

    <main class="flex justify-center items-center min-h-screen bg-gray-50 dark:bg-gray-900">
      <div class="w-full max-w-md p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white text-center">Register</h1>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Name -->
          <div>
            <InputLabel for="name" value="Name" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
              v-model="form.name"
              required
              autofocus
              autocomplete="name"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <!-- Email -->
          <div>
            <InputLabel for="email" value="Email" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
              v-model="form.email"
              required
              autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <!-- Password -->
          <div>
            <InputLabel for="password" value="Password" />
            <TextInput
              id="password"
              type="password"
              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
              v-model="form.password"
              required
              autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
          </div>

          <!-- Confirm Password -->
          <div>
            <InputLabel for="password_confirmation" value="Confirm Password" />
            <TextInput
              id="password_confirmation"
              type="password"
              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
              v-model="form.password_confirmation"
              required
              autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-between mt-4">
            <Link
              :href="route('login')"
              class="text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
            >
              Already registered?
            </Link>

            <PrimaryButton
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
              :disabled="form.processing"
            >
              Register
            </PrimaryButton>
          </div>
        </form>
      </div>
    </main>
  </BooksLayout>
</template>

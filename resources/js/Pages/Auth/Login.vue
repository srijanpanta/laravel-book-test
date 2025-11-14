<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import BooksLayout from '@/Layouts/BooksLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
    canResetPassword: Boolean,
    status: String
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <BooksLayout :auth="{ user: null }">
    <Head title="Log in" />

    <main class="flex justify-center items-center min-h-screen bg-gray-50 dark:bg-gray-900">
      <div class="w-full max-w-md p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white text-center">Log in</h1>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
          {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Email -->
          <div>
            <InputLabel for="email" value="Email" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
              v-model="form.email"
              required
              autofocus
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
              autocomplete="current-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <Checkbox name="remember" v-model:checked="form.remember" />
            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Remember me</span>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-between mt-4">
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
            >
              Forgot your password?
            </Link>

            <PrimaryButton
              class="ml-auto"
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
              :disabled="form.processing"
            >
              Log in
            </PrimaryButton>
          </div>
        </form>
      </div>
    </main>
  </BooksLayout>
</template>

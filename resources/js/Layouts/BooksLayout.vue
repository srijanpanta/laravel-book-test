<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <!-- Logo -->
          <div class="shrink-0">
            <a href="/" class="text-xl font-bold text-blue-600 dark:text-blue-400">BookApp</a>
          </div>

          <!-- Navigation Links -->
          <div class="flex items-center space-x-4">
            <template v-if="auth.user">
              <span class="text-gray-800 dark:text-gray-200">Hello, {{ auth.user.name }}</span>
              <button
                @click="logout"
                class="px-3 py-2 rounded-md text-sm font-medium bg-red-600 hover:bg-red-700 text-white transition"
              >
                Logout
              </button>
            </template>

            <template v-else>
              <a
                href="/login"
                class="px-3 py-2 rounded-md text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
              >
                Login
              </a>
              <a
                href="/register"
                class="px-3 py-2 rounded-md text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
              >
                Register
              </a>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main>
      <slot />
    </main>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  auth: {
    type: Object,
    required: true
  }
});

function logout() {
  router.post('/logout');
}
</script>

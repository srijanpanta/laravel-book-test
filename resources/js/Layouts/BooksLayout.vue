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
          <div class="flex items-center space-x-4 relative">
            <template v-if="auth.user">
              <!-- Profile Dropdown -->
              <div class="relative" ref="dropdownRef">
                <button
                  @click="openDropdown = !openDropdown"
                  class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                >
                  <span>{{ auth.user.name }}</span>
                  <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7"></path>
                  </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                  v-show="openDropdown"
                  class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md py-2 z-50 transition-transform transform scale-95 opacity-0 animate-dropdown"
                >
                  <a
                    href="/profile"
                    class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    @click="openDropdown = false"
                  >
                    Profile
                  </a>
                  <button
                    @click="logout"
                    class="w-full text-left block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                  >
                    Logout
                  </button>
                </div>
              </div>
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
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  auth: {
    type: Object,
    required: true
  }
});

const openDropdown = ref(false);
const dropdownRef = ref(null);

// Close dropdown if clicked outside
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
      openDropdown.value = false;
    }
  });
});

function logout() {
  router.post('/logout');
}
</script>

<style>
/* Dropdown animation */
@keyframes dropdown {
  0% { opacity: 0; transform: translateY(-5px) scale(0.95); }
  100% { opacity: 1; transform: translateY(0) scale(1); }
}

.animate-dropdown {
  animation: dropdown 0.15s forwards;
}
</style>

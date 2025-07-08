<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props
defineProps({
  userName: String
})

// State
const showDropdown = ref(false)

// Methods
const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

const changePassword = () => {
  // Arahkan ke halaman ganti password
  router.visit('/user/profile') // sesuaikan dengan rute yang kamu pakai
}
</script>

<template>
  <div class="flex items-start justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Hello, {{ userName }}</h1>
      <p class="text-xl font-semibold text-gray-900 mt-1">在庫管理、頑張ってね！</p>
    </div>

    <div class="relative dropdown-container">
      <!-- Dropdown toggle button -->
      <button
        @click="toggleDropdown"
        class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
        type="button"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Dropdown menu -->
      <div
        v-if="showDropdown"
        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10"
      >
        <button
          @click="changePassword"
          class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Profil
        </button>

        <Link
          as="button"
          method="post"
          href="/logout"
          class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Logout
        </Link>
      </div>
    </div>
  </div>
</template>

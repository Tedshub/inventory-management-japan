<script setup>
import { computed } from 'vue'

const props = defineProps({
  barangs: Array
})

const totalItems = computed(() => props.barangs?.length || 0)

const totalStokDipesan = computed(() => {
  if (!props.barangs || props.barangs.length === 0) return 0
  return props.barangs.reduce((total, barang) => {
    const value = parseInt(barang.stok_dipesan || 0)
    return total + (isNaN(value) ? 0 : value)
  }, 0)
})

const totalStokTersedia = computed(() => {
  if (!props.barangs || props.barangs.length === 0) return 0
  return props.barangs.reduce((total, barang) => {
    const value = parseInt(barang.stok_tersedia || 0)
    return total + (isNaN(value) ? 0 : value)
  }, 0)
})

const totalStokDibutuhkan = computed(() => {
  if (!props.barangs || props.barangs.length === 0) return 0
  return props.barangs.reduce((total, barang) => {
    const value = parseInt(barang.stok_dibutuhkan || 0)
    return total + (isNaN(value) ? 0 : value)
  }, 0)
})
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <!-- Total Items Card -->
    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 hover:shadow-lg transition-shadow duration-300">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium mb-1">Total Items</p>
          <p class="text-2xl font-bold text-gray-900">{{ totalItems.toLocaleString() }}</p>
        </div>
        <div class="bg-blue-100 rounded-lg p-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Ordered Stock Card -->
    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 hover:shadow-lg transition-shadow duration-300">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium mb-1">Ordered Stock</p>
          <p class="text-2xl font-bold text-gray-900">{{ totalStokDipesan.toLocaleString() }}</p>
        </div>
        <div class="bg-yellow-100 rounded-lg p-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2v-1" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Available Stock Card -->
    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 hover:shadow-lg transition-shadow duration-300">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium mb-1">Available Stock</p>
          <p class="text-2xl font-bold text-gray-900">{{ totalStokTersedia.toLocaleString() }}</p>
        </div>
        <div class="bg-green-100 rounded-lg p-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Required Stock Card -->
    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 hover:shadow-lg transition-shadow duration-300">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium mb-1">Required Stock</p>
          <p class="text-2xl font-bold text-gray-900">{{ totalStokDibutuhkan.toLocaleString() }}</p>
        </div>
        <div class="bg-red-100 rounded-lg p-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.193 2.5 1.732 2.5z" />
          </svg>
        </div>
      </div>
    </div>
  </div>
</template>

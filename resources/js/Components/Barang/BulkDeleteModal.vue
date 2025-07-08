<script setup>
defineProps({
  selectedItems: Array,
  barangs: Array
})

defineEmits(['confirm', 'cancel'])
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 px-4 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b border-red-200 bg-red-50">
        <h2 class="text-lg font-semibold text-red-800 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
          Confirm Mass Delete
        </h2>
        <button @click="$emit('cancel')" class="text-gray-400 hover:text-gray-500 rounded-full p-1 hover:bg-gray-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="p-6">
        <div class="flex items-start gap-3 mb-4">
          <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
              Are you sure you want to delete {{ selectedItems.length }} item(s)?
            </h3>
            <p class="text-sm text-gray-600 mb-3">
              This action cannot be undone. All selected item data will be permanently deleted.
            </p>

            <!-- Show selected items -->
            <div class="bg-gray-50 rounded-lg p-3 max-h-32 overflow-y-auto">
              <p class="text-xs font-medium text-gray-700 mb-2">Items to be deleted:</p>
              <ul class="text-xs text-gray-600 space-y-1">
                <li v-for="item in barangs.filter(b => selectedItems.includes(b.id))" :key="item.id" class="flex justify-between">
                  <span>{{ item.nama }}</span>
                  <span class="text-gray-400">{{ item.kategori }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
          <button
            type="button"
            @click="$emit('cancel')"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="$emit('confirm')"
            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Yes, Delete All
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  form: Object,
  editMode: Boolean
})

defineEmits(['submit', 'close'])
</script>

<template>
  <!-- Backdrop dengan efek blur -->
  <div class="fixed inset-0 bg-black/30 backdrop-blur-md z-40"></div>

  <!-- Modal Container -->
  <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-900">
          {{ editMode ? '商品を編集' : '新しい商品を追加' }}
        </h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500 rounded-full p-1 hover:bg-gray-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form @submit.prevent="$emit('submit')" class="p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">名</label>
          <input v-model="form.nama" type="text" placeholder="商品名を入力してください" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">単位</label>
          <input v-model="form.satuan" type="text" placeholder="例：袋、個、箱など" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">在庫注文</label>
          <input v-model="form.stok_dipesan" type="number" placeholder="注文された在庫数を入力" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">利用可能な在庫</label>
          <input v-model="form.stok_tersedia" type="number" placeholder="利用可能な在庫数を入力" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">在庫が必要です</label>
          <input v-model="form.stok_dibutuhkan" type="number" placeholder="必要な在庫数を入力" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
        </div>
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
          <button type="button" @click="$emit('close')" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            {{ editMode ? 'Save Changes' : 'Add Item' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
/* Backdrop Blur Effect */
.fixed {
  position: fixed;
}

.backdrop-blur-md {
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

/* Modal Styling */
.bg-black\/30 {
  background-color: rgba(0, 0, 0, 0.3);
}

.z-40 {
  z-index: 40;
}

.z-50 {
  z-index: 50;
}

.max-w-md {
  max-width: 28rem;
}

.shadow-2xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Transition Effects */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>

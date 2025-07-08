<script setup>
defineProps({
  importFile: File,
  hasSelectedItems: Boolean,
  selectedItems: Array
})

defineEmits([
  'file-selected',
  'import',
  'open-add-modal',
  'open-bulk-delete',
  'clear-selection'
])
</script>

<template>
  <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-3 w-full">
    <!-- Import Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2 w-full sm:w-auto">
      <label class="cursor-pointer bg-white border border-gray-200 rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-50 transition-colors w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        <span class="text-sm font-medium truncate">{{ importFile ? importFile.name : 'ファイルを選択' }}</span>
        <input type="file" @change="$emit('file-selected', $event)" accept=".xlsx,.xls" class="hidden" />
      </label>

      <button
        @click="$emit('import')"
        :disabled="!importFile"
        :class="[
          'px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full sm:w-auto',
          importFile
              ? 'bg-emerald-600 text-white hover:bg-emerald-700'
              : 'bg-gray-300 text-gray-500 cursor-not-allowed'
          ]"
      >
        Excelをインポート
      </button>
    </div>

    <!-- Export Excel -->
    <a href="/barang/export"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2 w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Excelをエクスポート
    </a>

    <!-- Download Template -->
    <a href="/import_template.xlsx" download="import_template.xlsx"
        class="bg-[#FDE68A] text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#FCD34D] transition-colors flex items-center gap-2 w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        テンプレートをダウンロード
    </a>

    <!-- Bulk Delete -->
    <button v-if="hasSelectedItems" @click="$emit('open-bulk-delete')"
        class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors flex items-center gap-2 w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        選択を削除 ({{ selectedItems.length }})
    </button>

    <!-- Clear Selection -->
    <button v-if="hasSelectedItems" @click="$emit('clear-selection')"
        class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors flex items-center gap-2 w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        選択を解除
    </button>

    <!-- Add Data -->
    <button @click="$emit('open-add-modal')"
        class="ml-0 sm:ml-auto bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2 w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        データを追加します
    </button>
  </div>
</template>

<script setup>
import { VueGoodTable } from 'vue-good-table-next'

defineProps({
  barangs: Array,
  selectedItems: Array,
  isSelectAll: Boolean
})

defineEmits([
  'edit',
  'delete',
  'toggle-select-all',
  'toggle-item-selection'
])
</script>

<template>
  <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <VueGoodTable
      :columns="[
        {
          label: '',
          field: 'checkbox',
          sortable: false,
          width: '50px',
          tdClass: 'text-center'
        },
        {
          label: 'No',
          field: 'index',
          sortable: false,
          width: '70px',
          tdClass: 'text-center'
        },
        { label: '名', field: 'nama' },
        { label: '単位', field: 'satuan' },
        { label: '在庫注文', field: 'stok_dipesan' },
        { label: '利用可能な在庫', field: 'stok_tersedia' },
        { label: '在庫が必要です', field: 'stok_dibutuhkan' },
        { label: '日付', field: 'created_at' },
        {
          label: 'アクション',
          field: 'actions',
          sortable: false
        }
      ]"
      :rows="barangs.map((barang, index) => ({
        ...barang,
        index: index + 1,
        created_at: barang.created_at ? new Date(barang.created_at).toLocaleString() : ''
      }))"
      :search-options="{ enabled: true, placeholder: 'アイテムを探す...' }"
      :pagination-options="{ enabled: true, perPage: 5, perPageDropdown: [5, 10, 20] }"
      styleClass="vgt-table striped"
    >
      <template #table-column="props">
        <span v-if="props.column.field === 'checkbox'">
          <input
            type="checkbox"
            :checked="isSelectAll"
            @change="$emit('toggle-select-all')"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
          />
        </span>
        <span v-else>{{ props.column.label }}</span>
      </template>

      <template #table-row="props">
        <td v-if="props.column.field === 'checkbox'" class="text-center">
          <input
            type="checkbox"
            :checked="selectedItems.includes(props.row.id)"
            @change="$emit('toggle-item-selection', props.row.id)"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
          />
        </td>
        <td v-if="props.column.field === 'index'" class="text-gray-500">
          {{ props.row.index }}
        </td>
        <td v-if="props.column.field === 'actions'" class="flex gap-2 justify-end">
          <button @click="$emit('edit', props.row)" class="text-yellow-600 hover:text-yellow-800 transition-colors p-2 rounded-full hover:bg-yellow-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </button>
          <button @click="$emit('delete', props.row.id)" class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-full hover:bg-red-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </td>
      </template>
    </VueGoodTable>
  </div>
</template>

<style>
.vgt-table.striped {
  --vgt-row-striped-bg: #f9fafb;
}
.vgt-table thead th {
  @apply bg-gray-50 text-gray-700 font-semibold text-sm uppercase tracking-wider;
}
.vgt-table td {
  @apply py-3 px-4 text-sm text-gray-800;
}
.vgt-table thead th.sortable button:after {
  border-bottom-color: #4b5563;
}
.vgt-table thead th.sortable button:before {
  border-top-color: #4b5563;
}
.vgt-input {
  @apply border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 text-sm;
}
.vgt-wrap__footer {
  @apply px-4 py-3 border-t border-gray-200 bg-white;
}
</style>

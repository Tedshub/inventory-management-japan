
<script setup>
import { ref, onMounted, computed } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { VueGoodTable } from 'vue-good-table-next'
import { router } from '@inertiajs/vue3'

const page = usePage()
const userName = computed(() => page.props.auth?.user?.name || 'User')

const { barangs, flash = {} } = defineProps({
  barangs: Array,
  flash: {
    type: Object,
    default: () => ({})
  }
})

const form = useForm({
  nama: '',
  kategori: '',
  stok_dipesan: '',
  stok_tersedia: '',
  stok_dibutuhkan: '',
  satuan: '',
})

const editMode = ref(false)
const showModal = ref(false)
const showAlert = ref(false)
const alertMessage = ref('')
const alertType = ref('success')
const selectedBarang = ref(null)
const showDropdown = ref(false)

// Bulk delete related states
const selectedItems = ref([])
const showBulkDeleteModal = ref(false)
const isSelectAll = ref(false)

// Computed properties for statistics - FIXED
const totalItems = computed(() => barangs?.length || 0)

const totalStokDipesan = computed(() => {
  if (!barangs || barangs.length === 0) return 0
  return barangs.reduce((total, barang) => {
    const value = parseInt(barang.stok_dipesan || 0)
    return total + (isNaN(value) ? 0 : value)
  }, 0)
})

const totalStokTersedia = computed(() => {
  if (!barangs || barangs.length === 0) return 0
  return barangs.reduce((total, barang) => {
    const value = parseInt(barang.stok_tersedia || 0)
    return total + (isNaN(value) ? 0 : value)
  }, 0)
})

const totalStokDibutuhkan = computed(() => {
  if (!barangs || barangs.length === 0) return 0
  return barangs.reduce((total, barang) => {
    const value = parseInt(barang.stok_dibutuhkan || 0)
    return total + (isNaN(value) ? 0 : value)
  }, 0)
})

// Computed property for bulk delete button visibility
const hasSelectedItems = computed(() => selectedItems.value.length > 0)

// Close dropdown when clicking outside
onMounted(() => {
  // Check for flash message on component mount
  if (flash?.success) {
    showAlert.value = true
    alertMessage.value = flash.success
    alertType.value = 'success'
    setTimeout(() => {
      showAlert.value = false
    }, 3000)
  }

  // Check for localStorage message (after page reload)
  const storedMessage = localStorage.getItem('inventoryMessage')
  const storedType = localStorage.getItem('inventoryMessageType')

  if (storedMessage) {
    showAlert.value = true
    alertMessage.value = storedMessage
    alertType.value = storedType || 'success'

    // Clear the stored message
    localStorage.removeItem('inventoryMessage')
    localStorage.removeItem('inventoryMessageType')

    setTimeout(() => {
      showAlert.value = false
    }, 3000)
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown-container')) {
      showDropdown.value = false
    }
  })
})

function submit() {
  if (editMode.value) {
    form.put(`/barang/${selectedBarang.value.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        showModal.value = false
        editMode.value = false
        selectedBarang.value = null
        form.reset()

        // Store success message in localStorage before reload
        localStorage.setItem('inventoryMessage', 'Data barang berhasil diperbarui')
        localStorage.setItem('inventoryMessageType', 'success')

        // Reload page
        window.location.reload()
      }
    })
  } else {
    form.post('/barang', {
      preserveScroll: true,
      onSuccess: () => {
        form.reset()

        // Store success message in localStorage before reload
        localStorage.setItem('inventoryMessage', 'Data barang berhasil ditambahkan')
        localStorage.setItem('inventoryMessageType', 'success')

        // Reload page
        window.location.reload()
      }
    })
  }
}

function editBarang(barang) {
  form.nama = barang.nama
  form.kategori = barang.kategori
  form.stok_dipesan = barang.stok_dipesan
  form.stok_tersedia = barang.stok_tersedia
  form.stok_dibutuhkan = barang.stok_dibutuhkan
  form.satuan = barang.satuan
  selectedBarang.value = barang
  editMode.value = true
  showModal.value = true
}

function deleteBarang(id) {
  if (confirm('Yakin hapus barang ini?')) {
    form.delete(`/barang/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        // Store success message in localStorage before reload
        localStorage.setItem('inventoryMessage', 'Data barang berhasil dihapus')
        localStorage.setItem('inventoryMessageType', 'success')

        // Reload page
        window.location.reload()
      }
    })
  }
}

function openAddModal() {
  form.reset()
  editMode.value = false
  showModal.value = true
}

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
}

function changePassword() {
  window.location.href = '/user/profile'
  showDropdown.value = false
}

function logout() {
  if (confirm('Yakin ingin logout?')) {
    router.post('/logout')
  }
  showDropdown.value = false
}

// Bulk delete functions
function toggleSelectAll() {
  if (isSelectAll.value) {
    selectedItems.value = barangs.map(barang => barang.id)
  } else {
    selectedItems.value = []
  }
}

function toggleItemSelection(itemId) {
  const index = selectedItems.value.indexOf(itemId)
  if (index > -1) {
    selectedItems.value.splice(index, 1)
  } else {
    selectedItems.value.push(itemId)
  }

  // Update select all checkbox state
  isSelectAll.value = selectedItems.value.length === barangs.length
}

function openBulkDeleteModal() {
  if (selectedItems.value.length === 0) {
    showAlert.value = true
    alertMessage.value = 'Pilih minimal satu item untuk dihapus'
    alertType.value = 'error'
    setTimeout(() => {
      showAlert.value = false
    }, 3000)
    return
  }
  showBulkDeleteModal.value = true
}

function confirmBulkDelete() {
  const selectedCount = selectedItems.value.length

  // Create form data for bulk delete
  const bulkDeleteForm = useForm({
    ids: selectedItems.value
  })

  bulkDeleteForm.post('/barang/bulk-delete', {
    preserveScroll: true,
    onSuccess: () => {
      // Reset selections
      selectedItems.value = []
      isSelectAll.value = false
      showBulkDeleteModal.value = false

      // Store success message in localStorage before reload
      localStorage.setItem('inventoryMessage', `${selectedCount} data barang berhasil dihapus`)
      localStorage.setItem('inventoryMessageType', 'success')

      // Reload page
      window.location.reload()
    },
    onError: () => {
      showAlert.value = true
      alertMessage.value = 'Terjadi kesalahan saat menghapus data'
      alertType.value = 'error'
      setTimeout(() => {
        showAlert.value = false
      }, 3000)
    }
  })
}

function cancelBulkDelete() {
  showBulkDeleteModal.value = false
}

function clearSelection() {
  selectedItems.value = []
  isSelectAll.value = false
}
</script>

<template>
  <div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Success Alert -->
    <div v-if="showAlert" class="fixed top-4 right-4 z-50">
      <div :class="`${alertType === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'} border px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in`">
        <svg v-if="alertType === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <span>{{ alertMessage }}</span>
      </div>
    </div>

    <!-- Header with Hamburger Menu -->
    <div class="flex items-start justify-between">
    <!-- Left Section: Greeting and Title -->
    <div>
        <!-- Greeting -->
        <h1 class="text-3xl font-bold text-gray-800">Hello, {{ userName }}</h1>
        <!-- Subtitle -->
        <p class="text-xl font-semibold text-gray-900 mt-1">在庫管理、頑張ってね！</p>
    </div>

    <!-- Right Section: Hamburger Menu -->
    <div class="relative dropdown-container">
        <button
        @click="toggleDropdown"
        class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
        type="button"
        >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        </button>

        <!-- Dropdown Menu -->
        <div v-if="showDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
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

<!-- Statistics Cards - FIXED TO USE DYNAMIC DATA -->
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

    <!-- Action Buttons -->
    <div class="flex flex-wrap items-center gap-3">
      <form method="POST" action="/barang/import" enctype="multipart/form-data" class="flex items-center gap-2">
        <label class="cursor-pointer bg-white border border-gray-200 rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-50 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
          <span class="text-sm font-medium">Choose File</span>
          <input type="file" name="file" required class="hidden" />
        </label>
        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors">
          Import Excel
        </button>
      </form>
      <a href="/barang/export" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export Excel
      </a>

      <!-- Bulk Delete Button -->
      <button
        v-if="hasSelectedItems"
        @click="openBulkDeleteModal"
        class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors flex items-center gap-2"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        選択を削除 ({{ selectedItems.length }})
      </button>

      <!-- Clear Selection Button -->
      <button
        v-if="hasSelectedItems"
        @click="clearSelection"
        class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors flex items-center gap-2"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        選択を解除
      </button>

      <button @click="openAddModal" class="ml-auto bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        データを追加します
      </button>
    </div>

    <!-- Tabel Barang -->
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
              v-model="isSelectAll"
              @change="toggleSelectAll"
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
              @change="toggleItemSelection(props.row.id)"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
            />
          </td>
          <td v-if="props.column.field === 'index'" class="text-gray-500">
            {{ props.row.index }}
          </td>
          <td v-if="props.column.field === 'actions'" class="flex gap-2 justify-end">
            <button @click="editBarang(props.row)" class="text-yellow-600 hover:text-yellow-800 transition-colors p-2 rounded-full hover:bg-yellow-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button @click="deleteBarang(props.row.id)" class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-full hover:bg-red-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </td>
        </template>
      </VueGoodTable>
    </div>

<!-- Modal Tambah/Edit -->
<div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 px-4 backdrop-blur-sm">
  <div class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b">
      <h2 class="text-lg font-semibold text-gray-900">
        {{ editMode ? '商品を編集' : '新しい商品を追加' }}
      </h2>
      <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 rounded-full p-1 hover:bg-gray-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <form @submit.prevent="submit" class="p-6 space-y-4">
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
        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
        Cancel
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
        {{ editMode ? 'Save Changes' : 'Add Item' }}
        </button>
      </div>
    </form>
  </div>
</div>


    <!-- Bulk Delete Confirmation Modal -->
    <div v-if="showBulkDeleteModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 px-4 backdrop-blur-sm">
      <div class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-red-200 bg-red-50">
          <h2 class="text-lg font-semibold text-red-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            Confirm Mass Delete
          </h2>
          <button @click="cancelBulkDelete" class="text-gray-400 hover:text-gray-500 rounded-full p-1 hover:bg-gray-100 transition-colors">
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
              @click="cancelBulkDelete"
              class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              type="button"
              @click="confirmBulkDelete"
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

@keyframes fade-in {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out forwards;
}

/* Custom checkbox styling */
input[type="checkbox"]:checked {
  background-color: #3b82f6;
  border-color: #3b82f6;
}

input[type="checkbox"]:focus {
  outline: none;
  ring-offset-width: 2px;
  ring-offset-color: #ffffff;
  ring-width: 2px;
  ring-color: #3b82f6;
}
</style>

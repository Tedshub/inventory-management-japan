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
  stok: '',
  satuan: '',
})

const editMode = ref(false)
const showModal = ref(false)
const showAlert = ref(false)
const alertMessage = ref('')
const alertType = ref('success')
const selectedBarang = ref(null)
const showDropdown = ref(false)

// Computed properties for statistics
const totalProducts = computed(() => barangs?.length || 0)
const totalCategories = computed(() => {
  if (!barangs?.length) return 0
  const uniqueCategories = new Set(barangs.map(barang => barang.kategori?.toLowerCase().trim()))
  return uniqueCategories.size
})
const totalStock = computed(() => {
  if (!barangs?.length) return 0
  return barangs.reduce((total, barang) => total + (parseInt(barang.stok) || 0), 0)
})

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
  form.stok = barang.stok
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
  // Navigate to change password page
  window.location.href = '/change-password'
  showDropdown.value = false
}

function logout() {
  if (confirm('Yakin ingin logout?')) {
    router.post('/logout')
  }
  showDropdown.value = false
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
        <h1 class="text-3xl font-bold text-gray-800">Halo, {{ userName }}</h1>
        <!-- Subtitle -->
        <p class="text-xl font-semibold text-gray-900 mt-1">Semangat ya memanajemen stok barangnya..</p>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            Ubah Password
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


    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Total Products Card -->
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-blue-100 text-sm font-medium">Total Produk</p>
            <p class="text-3xl font-bold">{{ totalProducts }}</p>
          </div>
          <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Categories Card -->
      <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-green-100 text-sm font-medium">Total Kategori</p>
            <p class="text-3xl font-bold">{{ totalCategories }}</p>
          </div>
          <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Stock Card -->
      <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-purple-100 text-sm font-medium">Total Stok</p>
            <p class="text-3xl font-bold">{{ totalStock.toLocaleString() }}</p>
          </div>
          <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
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
          <span class="text-sm font-medium">Pilih File</span>
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
      <button @click="openAddModal" class="ml-auto bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Barang
      </button>
    </div>

    <!-- Tabel Barang -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
      <VueGoodTable
        :columns="[
          {
            label: 'No',
            field: 'index',
            sortable: false,
            width: '70px',
            tdClass: 'text-center'
          },
          { label: 'Nama', field: 'nama' },
          { label: 'Kategori', field: 'kategori' },
          { label: 'Stok', field: 'stok' },
          { label: 'Satuan', field: 'satuan' },
          { label: 'Aksi', field: 'actions', sortable: false }
        ]"
        :rows="barangs.map((barang, index) => ({ ...barang, index: index + 1 }))"
        :search-options="{ enabled: true, placeholder: 'Cari barang...' }"
        :pagination-options="{ enabled: true, perPage: 5, perPageDropdown: [5, 10, 20] }"
        styleClass="vgt-table striped"
      >
        <template #table-row="props">
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
            {{ editMode ? 'Edit Barang' : 'Tambah Barang Baru' }}
          </h2>
          <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 rounded-full p-1 hover:bg-gray-100 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <form @submit.prevent="submit" class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
            <input v-model="form.nama" type="text" placeholder="Masukkan nama barang" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <input v-model="form.kategori" type="text" placeholder="Masukkan kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
            <input v-model="form.stok" type="number" placeholder="Masukkan jumlah stok" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
            <input v-model="form.satuan" type="text" placeholder="Masukkan satuan (pcs, kg, etc)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
          </div>
          <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
              Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
              {{ editMode ? 'Simpan Perubahan' : 'Tambahkan Barang' }}
            </button>
          </div>
        </form>
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
</style>

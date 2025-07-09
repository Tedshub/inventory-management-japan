<script setup>
import { ref, onMounted, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

// Komponen
import BarangHeader from '@/Components/Barang/Header.vue'
import BarangStatsCards from '@/Components/Barang/StatsCards.vue'
import BarangActionButtons from '@/Components/Barang/ActionButtons.vue'
import BarangTable from '@/Components/Barang/BarangTable.vue'
import BarangAddEditModal from '@/Components/Barang/AddEditModal.vue'
import BarangBulkDeleteModal from '@/Components/Barang/BulkDeleteModal.vue'
import BarangAlert from '@/Components/Barang/Alert.vue'

// Props dari server
const page = usePage()
const { barangs, flash = {} } = defineProps({
  barangs: Array,
  flash: {
    type: Object,
    default: () => ({})
  }
})

// State & form
const showModal = ref(false)
const showAlert = ref(false)
const alertMessage = ref('')
const alertType = ref('success')
const selectedBarang = ref(null)
const editMode = ref(false)
const selectedItems = ref([])
const isSelectAll = computed(() => selectedItems.value.length === barangs.length)
const showBulkDeleteModal = ref(false)
const importFile = ref(null)

const form = useForm({
  nama: '',
  kategori: '',
  stok_dipesan: '',
  stok_tersedia: '',
  stok_dibutuhkan: '',
  satuan: '',
})

const importForm = useForm({
  file: null
})

// Computed
const userName = computed(() => page.props.auth?.user?.name || 'User')
const hasSelectedItems = computed(() => selectedItems.value.length > 0)

// Alert helper
function showAlertMessage(message, type = 'success') {
  alertMessage.value = message
  alertType.value = type
  showAlert.value = true
  setTimeout(() => showAlert.value = false, 3000)
}

// File import
function handleFileSelect(event) {
  const file = event.target.files[0]
  if (file) {
    const allowedTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls)$/i)) {
      showAlertMessage('Please select a valid Excel file (.xlsx or .xls)', 'error')
      return
    }

    if (file.size > 10 * 1024 * 1024) {
      showAlertMessage('File size must be less than 10MB', 'error')
      return
    }

    importForm.file = file
    importFile.value = file
  }
}

function handleImport() {
  if (!importForm.file) {
    showAlertMessage('Please select a file to import', 'error')
    return
  }

  const formData = new FormData()
  formData.append('file', importForm.file)

  router.post('/barang/import', formData, {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      importForm.reset()
      importFile.value = null
      showAlertMessage('Data berhasil diimport')
      window.location.reload()
    },
    onError: (errors) => {
      let errorMessage = 'Import gagal, periksa format file'
      if (errors.file) errorMessage = Array.isArray(errors.file) ? errors.file[0] : errors.file
      showAlertMessage(errorMessage, 'error')
    }
  })
}

// Tambah/Edit Barang
function submit() {
  const url = editMode.value
    ? `/barang/${selectedBarang.value.id}`
    : '/barang'

  const method = editMode.value ? 'put' : 'post'

  form[method](url, {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false
      localStorage.setItem('inventoryMessage', editMode.value
        ? 'Data barang berhasil diperbarui'
        : 'Data barang berhasil ditambahkan')
      localStorage.setItem('inventoryMessageType', 'success')
      window.location.reload()
    },
    onError: () => {
      showModal.value = false
      showAlertMessage('Terjadi kesalahan saat menyimpan data', 'error')
    }
  })
}

// Modal tambah barang
function openAddModal() {
  form.reset()
  editMode.value = false
  selectedBarang.value = null
  showModal.value = true
}

// Edit barang
function editBarang(barang) {
  Object.assign(form, {
    nama: barang.nama,
    kategori: barang.kategori,
    stok_dipesan: barang.stok_dipesan,
    stok_tersedia: barang.stok_tersedia,
    stok_dibutuhkan: barang.stok_dibutuhkan,
    satuan: barang.satuan,
  })
  selectedBarang.value = barang
  editMode.value = true
  showModal.value = true
}

// Hapus 1 barang
function deleteBarang(id) {
  if (confirm('Yakin hapus barang ini?')) {
    form.delete(`/barang/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        localStorage.setItem('inventoryMessage', 'Data barang berhasil dihapus')
        localStorage.setItem('inventoryMessageType', 'success')
        window.location.reload()
      }
    })
  }
}

// Check All
function toggleSelectAll() {
  if (isSelectAll.value) {
    selectedItems.value = []
  } else {
    selectedItems.value = barangs.map(barang => barang.id)
  }
}

// Per item
function toggleItemSelection(itemId) {
  const index = selectedItems.value.indexOf(itemId)
  if (index > -1) {
    selectedItems.value.splice(index, 1)
  } else {
    selectedItems.value.push(itemId)
  }
}

// Hapus banyak
function openBulkDeleteModal() {
  if (selectedItems.value.length === 0) {
    showAlertMessage('Pilih minimal satu item untuk dihapus', 'error')
    return
  }
  showBulkDeleteModal.value = true
}

function confirmBulkDelete() {
  const bulkDeleteForm = useForm({ ids: selectedItems.value })

  bulkDeleteForm.post('/barang/bulk-delete', {
    preserveScroll: true,
    onSuccess: () => {
      selectedItems.value = []
      showBulkDeleteModal.value = false
      localStorage.setItem('inventoryMessage', `${bulkDeleteForm.ids.length} data barang berhasil dihapus`)
      localStorage.setItem('inventoryMessageType', 'success')
      window.location.reload()
    }
  })
}

function cancelBulkDelete() {
  showBulkDeleteModal.value = false
}

function clearSelection() {
  selectedItems.value = []
}

onMounted(() => {
  if (flash?.success) showAlertMessage(flash.success)
  const storedMessage = localStorage.getItem('inventoryMessage')
  const storedType = localStorage.getItem('inventoryMessageType')
  if (storedMessage) {
    showAlertMessage(storedMessage, storedType || 'success')
    localStorage.removeItem('inventoryMessage')
    localStorage.removeItem('inventoryMessageType')
  }
})
</script>

<template>
  <div class="p-6 max-w-7xl mx-auto space-y-6">
    <BarangAlert
      v-if="showAlert"
      :message="alertMessage"
      :type="alertType"
    />

    <BarangHeader :userName="userName" />

    <BarangStatsCards :barangs="barangs" />

    <BarangActionButtons
      :importFile="importFile"
      :hasSelectedItems="hasSelectedItems"
      :selectedItems="selectedItems"
      @file-selected="handleFileSelect"
      @import="handleImport"
      @open-add-modal="openAddModal"
      @open-bulk-delete="openBulkDeleteModal"
      @clear-selection="clearSelection"
    />

    <BarangTable
      :barangs="barangs"
      :selectedItems="selectedItems"
      :isSelectAll="isSelectAll"
      @edit="editBarang"
      @delete="deleteBarang"
      @toggle-select-all="toggleSelectAll"
      @toggle-item-selection="toggleItemSelection"
    />

    <BarangAddEditModal
      v-if="showModal"
      :form="form"
      :editMode="editMode"
      @submit="submit"
      @close="showModal = false"
    />

    <BarangBulkDeleteModal
      v-if="showBulkDeleteModal"
      :selectedItems="selectedItems"
      :barangs="barangs"
      @confirm="confirmBulkDelete"
      @cancel="cancelBulkDelete"
    />
  </div>
</template>

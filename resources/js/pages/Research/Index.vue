<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">
            Gestión de Investigadores
          </h1>
          <p class="mt-2 text-lg text-gray-600">
            Administra todos los investigadores registrados en el sistema
          </p>
        </div>
        <div class="mt-6 flex md:mt-0 md:ml-4">
          <button
            @click="openCreateModal"
            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo Investigador
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-16">
        <div class="text-center">
          <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-blue-500 transition ease-in-out duration-150">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Cargando investigadores...
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-6 rounded-lg shadow-sm">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-lg font-medium text-red-800">Error al cargar los datos</h3>
            <div class="mt-2 text-base text-red-700">
              {{ error }}
            </div>
            <div class="mt-4">
              <button
                @click="() => loadResearchers()"
                class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
              >
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reintentar
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Content -->
      <div v-else>
        <!-- Empty State -->
        <div v-if="researchers.length === 0" class="text-center py-16 bg-white rounded-xl shadow-sm">
          <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay investigadores registrados</h3>
          <p class="text-gray-500 mb-8 max-w-md mx-auto">
            Comienza agregando tu primer investigador para empezar a gestionar la información académica.
          </p>
          <button
            @click="openCreateModal"
            class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
          >
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Agregar primer investigador
          </button>
        </div>

        <!-- Table -->
        <div v-else class="bg-white shadow-xl rounded-xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Investigador
                  </th>
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    ORCID
                  </th>
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Email
                  </th>
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Palabras Clave
                  </th>
                  <th scope="col" class="relative px-6 py-4">
                    <span class="sr-only">Acciones</span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="researcher in researchers" :key="researcher.id" class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-6 py-5 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                          <span class="text-sm font-medium text-white">
                            {{ (researcher.given_names.charAt(0) + researcher.family_name.charAt(0)).toUpperCase() }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-base font-semibold text-gray-900">
                          {{ researcher.given_names }} {{ researcher.family_name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap">
                    <div class="text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ researcher.orcid }}</div>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ researcher.primary_email || 'N/A' }}</div>
                  </td>
                  <td class="px-6 py-5">
                    <div class="flex flex-wrap gap-1">
                      <span
                        v-for="keyword in researcher.keywords.slice(0, 3)"
                        :key="keyword"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800"
                      >
                        {{ keyword }}
                      </span>
                      <span
                        v-if="researcher.keywords.length > 3"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                      >
                        +{{ researcher.keywords.length - 3 }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <button
                        @click="openEditModal(researcher)"
                        class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-150"
                        title="Editar"
                      >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                      </button>
                      <button
                        @click="confirmDelete(researcher)"
                        class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-150"
                        title="Eliminar"
                      >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="pagination.total > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <button
                  @click="goToPage(pagination.current_page - 1)"
                  :disabled="!pagination.prev"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Anterior
                </button>
                <button
                  @click="goToPage(pagination.current_page + 1)"
                  :disabled="!pagination.next"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Siguiente
                </button>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ pagination.from }}</span>
                    a
                    <span class="font-medium">{{ pagination.to }}</span>
                    de
                    <span class="font-medium">{{ pagination.total }}</span>
                    resultados
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button
                      @click="goToPage(pagination.current_page - 1)"
                      :disabled="!pagination.prev"
                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <span class="sr-only">Anterior</span>
                      <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                    </button>
                    
                    <template v-for="page in getVisiblePages()" :key="page">
                      <button
                        v-if="page !== '...'"
                        @click="() => goToPage(Number(page))"
                        :class="[
                          page === pagination.current_page
                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]"
                      >
                        {{ page }}
                      </button>
                      <span
                        v-else
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                      >
                        ...
                      </span>
                    </template>
                    
                    <button
                      @click="goToPage(pagination.current_page + 1)"
                      :disabled="!pagination.next"
                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <span class="sr-only">Siguiente</span>
                      <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                      </svg>
                    </button>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Create Modal -->
      <CreateModal
        :show="showCreateModal"
        @close="closeCreateModal"
        @created="handleCreated"
      />

      <!-- Edit Modal -->
      <EditModal
        :show="showEditModal"
        :researcher="selectedResearcher"
        @close="closeEditModal"
        @updated="handleUpdated"
      />

      <!-- Delete Confirmation Modal -->
      <DeleteModal
        :show="showDeleteModal"
        :researcher="selectedResearcher"
        @close="closeDeleteModal"
        @deleted="handleDeleted"
      />

      <!-- Success/Error Toast -->
      <div
        v-if="toast.show"
        :class="[
          'fixed top-4 right-4 z-50 max-w-sm w-full shadow-lg rounded-xl pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transition-all duration-300 transform',
          toast.show ? 'translate-x-0 scale-100' : 'translate-x-full scale-95',
          toast.type === 'success' ? 'bg-gradient-to-r from-green-50 to-emerald-50' : 'bg-gradient-to-r from-red-50 to-pink-50'
        ]"
      >
        <div class="p-4">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <div :class="[
                'w-8 h-8 rounded-full flex items-center justify-center',
                toast.type === 'success' ? 'bg-green-100' : 'bg-red-100'
              ]">
                <svg
                  v-if="toast.type === 'success'"
                  class="h-5 w-5 text-green-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <svg
                  v-else
                  class="h-5 w-5 text-red-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
              <p :class="[
                'text-sm font-medium',
                toast.type === 'success' ? 'text-green-900' : 'text-red-900'
              ]">
                {{ toast.message }}
              </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
              <button
                @click="hideToast"
                :class="[
                  'rounded-md inline-flex focus:outline-none focus:ring-2 focus:ring-offset-2 p-1',
                  toast.type === 'success' 
                    ? 'text-green-400 hover:text-green-500 focus:ring-green-500' 
                    : 'text-red-400 hover:text-red-500 focus:ring-red-500'
                ]"
              >
                <span class="sr-only">Cerrar</span>
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { researcherService } from '@/services/researcherService'
import CreateModal from './CreateModal.vue'
import EditModal from './EditModal.vue'
import DeleteModal from './DeleteModal.vue'
import type { Researcher, PaginatedResponse, Pagination } from '@/types/researcher'

// State
const loading = ref(false)
const error = ref('')
const researchers = ref<Researcher[]>([])
const pagination = ref<Pagination>({
  current_page: 1,
  last_page: 1,
  per_page: 2,
  total: 0,
  from: 0,
  to: 0,
  prev: null,
  next: null
})

// Modal states
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const selectedResearcher = ref<Researcher | null>(null)

// Toast state
const toast = ref({
  show: false,
  message: '',
  type: 'success' as 'success' | 'error'
})

// Methods
const loadResearchers = async (page = 1) => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await researcherService.getAll(page)
    if (response.data) {
      researchers.value = response.data.data
      pagination.value = {
        ...response.data.pagination,
        prev: response.data.links.prev,
        next: response.data.links.next
      }
    }
  } catch (err: any) {
    error.value = err.message || 'Error al cargar los investigadores'
  } finally {
    loading.value = false
  }
}

const goToPage = (page: number) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    loadResearchers(page)
  }
}

const getVisiblePages = () => {
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const pages: (number | string)[] = []
  
  if (last <= 7) {
    for (let i = 1; i <= last; i++) {
      pages.push(i)
    }
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    } else if (current >= last - 3) {
      pages.push(1)
      pages.push('...')
      for (let i = last - 4; i <= last; i++) {
        pages.push(i)
      }
    } else {
      pages.push(1)
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    }
  }
  
  return pages
}


const openCreateModal = () => {
  showCreateModal.value = true
}

const closeCreateModal = () => {
  showCreateModal.value = false
}

const openEditModal = (researcher: Researcher) => {
  selectedResearcher.value = researcher
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  selectedResearcher.value = null
}

const confirmDelete = (researcher: Researcher) => {
  selectedResearcher.value = researcher
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  selectedResearcher.value = null
}

const handleCreated = () => {
  closeCreateModal()
  loadResearchers(1) 
  showToast('Investigador creado exitosamente', 'success')
}

const handleUpdated = () => {
  closeEditModal()
  loadResearchers(pagination.value.current_page)
  showToast('Investigador actualizado exitosamente', 'success')
}

const handleDeleted = () => {
  closeDeleteModal()
  loadResearchers(pagination.value.current_page) 
  showToast('Investigador eliminado exitosamente', 'success')
}

const showToast = (message: string, type: 'success' | 'error' = 'success') => {
  toast.value = { show: true, message, type }
  setTimeout(hideToast, 4000)
}

const hideToast = () => {
  toast.value.show = false
}

onMounted(() => {
  loadResearchers()
})
</script>

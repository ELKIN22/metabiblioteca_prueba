<template>
  <TransitionRoot :show="show" as="template">
    <Dialog as="div" class="relative z-50" @close="$emit('close')">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6 dark:bg-gray-800">
              <div>
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-800 dark:to-blue-700">
                  <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                  <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                    Crear Nuevo Investigador
                  </DialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                      Ingresa el ORCID del investigador para obtener sus datos automáticamente desde la base de datos oficial.
                    </p>
                  </div>
                </div>
              </div>

              <form @submit.prevent="handleSubmit" class="mt-6">
                <div>
                  <label for="orcid" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">
                    ORCID
                  </label>
                  <div class="relative mt-1">
                    <input
                      id="orcid"
                      v-model="form.orcid"
                      type="text"
                      placeholder="0000-0000-0000-0000"
                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3 transition-colors duration-200 bg-white text-gray-900 placeholder-gray-500 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                      :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-300 dark:placeholder-red-400': errors.orcid }"
                      required
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                  </div>
                  <p v-if="errors.orcid" class="mt-2 text-sm text-red-600">{{ errors.orcid }}</p>
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Formato: 0000-0000-0000-0000
                  </p>
                </div>

                <div v-if="errors.general" class="mt-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-md p-3">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-red-400 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Error</h3>
                      <div class="mt-1 text-sm text-red-700 dark:text-red-300">
                        {{ errors.general }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                  <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex w-full justify-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:from-blue-700 hover:to-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed sm:col-start-2 transition-all duration-200"
                  >
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ loading ? 'Creando...' : 'Crear Investigador' }}
                  </button>
                  <button
                    type="button"
                    @click="$emit('close')"
                    :disabled="loading"
                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed sm:col-start-1 sm:mt-0 transition-colors duration-200"
                  >
                    Cancelar
                  </button>
                </div>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { researcherService } from '@/services/researcherService'

interface Props {
  show: boolean
}

interface Emits {
  close: []
  created: []
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Form state
const form = ref({
  orcid: ''
})

const loading = ref(false)
const errors = ref<{ [key: string]: string }>({})

// Methods
const resetForm = () => {
  form.value = { orcid: '' }
  errors.value = {}
}

const validateForm = () => {
  errors.value = {}
  
  if (!form.value.orcid.trim()) {
    errors.value.orcid = 'El ORCID es obligatorio'
    return false
  }
  
  // Basic ORCID format validation
  const orcidPattern = /^\d{4}-\d{4}-\d{4}-\d{3}[\dX]$/
  if (!orcidPattern.test(form.value.orcid)) {
    errors.value.orcid = 'Formato de ORCID inválido (ej: 0000-0000-0000-0000)'
    return false
  }
  
  return true
}

const handleSubmit = async () => {
  if (!validateForm()) return
  
  loading.value = true
  errors.value = {}
  
  try {
    await researcherService.create({
      orcid: form.value.orcid.trim()
    })
    
    emit('created')
    resetForm()
  } catch (error: any) {
    errors.value.general = error.message
  } finally {
    loading.value = false
  }
}

// Watch for modal visibility changes
watch(() => props.show, (newValue) => {
  if (newValue) {
    resetForm()
  }
})
</script>

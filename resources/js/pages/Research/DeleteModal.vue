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
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-r from-red-100 to-red-200 dark:from-red-800 dark:to-red-700 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                  <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                    Eliminar Investigador
                  </DialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                      ¿Estás seguro de que deseas eliminar al investigador
                      <span class="font-semibold text-gray-900 dark:text-white">{{ researcher?.given_names }} {{ researcher?.family_name }}</span>?
                      Esta acción no se puede deshacer y se perderán todos los datos asociados.
                    </p>
                  </div>
                  
                  <div v-if="researcher" class="mt-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                    <div class="text-sm">
                      <div class="font-semibold text-gray-900 dark:text-white text-base">{{ researcher.given_names }} {{ researcher.family_name }}</div>
                      <div class="text-gray-600 dark:text-gray-300 font-mono mt-1 bg-white dark:bg-gray-800 px-2 py-1 rounded text-xs inline-block">{{ researcher.orcid }}</div>
                      <div v-if="researcher.primary_email" class="text-gray-600 dark:text-gray-300 mt-2">📧 {{ researcher.primary_email }}</div>
                      <div v-if="researcher.keywords.length > 0" class="mt-3">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Palabras clave:</div>
                        <div class="flex flex-wrap gap-1">
                          <span
                            v-for="keyword in researcher.keywords.slice(0, 3)"
                            :key="keyword"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200"
                          >
                            {{ keyword }}
                          </span>
                          <span
                            v-if="researcher.keywords.length > 3"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300"
                          >
                            +{{ researcher.keywords.length - 3 }} más
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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

              <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse sm:gap-3">
                <button
                  type="button"
                  @click="handleDelete"
                  :disabled="loading"
                  class="inline-flex w-full justify-center rounded-lg bg-gradient-to-r from-red-600 to-red-700 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:from-red-700 hover:to-red-800 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto transition-all duration-200"
                >
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Eliminando...' : 'Confirmar Eliminación' }}
                </button>
                <button
                  type="button"
                  @click="$emit('close')"
                  :disabled="loading"
                  class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed sm:mt-0 sm:w-auto transition-colors duration-200"
                >
                  Cancelar
                </button>
              </div>
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
import type { Researcher } from '@/types/researcher'

interface Props {
  show: boolean
  researcher: Researcher | null
}

interface Emits {
  close: []
  deleted: []
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// State
const loading = ref(false)
const errors = ref<{ [key: string]: string }>({})

// Methods
const resetErrors = () => {
  errors.value = {}
}

const handleDelete = async () => {
  if (!props.researcher) return
  
  loading.value = true
  errors.value = {}
  
  try {
    await researcherService.delete(props.researcher.orcid)
    emit('deleted')
  } catch (error: any) {
    errors.value.general = error.message
  } finally {
    loading.value = false
  }
}

// Watch for modal visibility changes
watch(() => props.show, (newValue) => {
  if (newValue) {
    resetErrors()
  }
})
</script>

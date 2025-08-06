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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                  <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                    Editar Investigador
                  </DialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                      Modifica la información del investigador. El ORCID no se puede cambiar una vez registrado.
                    </p>
                  </div>
                </div>
              </div>

              <form @submit.prevent="handleSubmit" class="mt-6 space-y-5">
                <div>
                  <label for="orcid" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">
                    ORCID
                  </label>
                  <div class="mt-1">
                    <input
                      id="orcid"
                      :value="researcher?.orcid"
                      type="text"
                      disabled
                      class="block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm sm:text-sm font-mono px-4 py-3 text-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    />
                  </div>
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">El ORCID no se puede modificar</p>
                </div>

                <div>
                  <label for="given_names" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">
                    Nombres
                  </label>
                  <div class="mt-1">
                    <input
                      id="given_names"
                      v-model="form.given_names"
                      type="text"
                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3 transition-colors duration-200 bg-white text-gray-900 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                      :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-300': errors.given_names }"
                    />
                  </div>
                  <p v-if="errors.given_names" class="mt-2 text-sm text-red-600">{{ errors.given_names }}</p>
                </div>

                <div>
                  <label for="family_name" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">
                    Apellidos
                  </label>
                  <div class="mt-1">
                    <input
                      id="family_name"
                      v-model="form.family_name"
                      type="text"
                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3 transition-colors duration-200 bg-white text-gray-900 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                      :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-300': errors.family_name }"
                    />
                  </div>
                  <p v-if="errors.family_name" class="mt-2 text-sm text-red-600">{{ errors.family_name }}</p>
                </div>

                <div>
                  <label for="primary_email" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">
                    Email Principal
                  </label>
                  <div class="mt-1">
                    <input
                      id="primary_email"
                      v-model="form.primary_email"
                      type="email"
                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3 transition-colors duration-200 bg-white text-gray-900 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                      :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-300': errors.primary_email }"
                    />
                  </div>
                  <p v-if="errors.primary_email" class="mt-2 text-sm text-red-600">{{ errors.primary_email }}</p>
                </div>

                <div>
                  <label for="keywords" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">
                    Palabras Clave
                  </label>
                  <div class="mt-1">
                    <textarea
                      id="keywords"
                      v-model="keywordsText"
                      rows="4"
                      placeholder="Ingresa las palabras clave separadas por comas"
                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3 transition-colors duration-200 bg-white text-gray-900 placeholder-gray-500 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                      :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-300 dark:placeholder-red-400': errors.keywords }"
                    />
                  </div>
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Separa las palabras clave con comas (ej: inteligencia artificial, machine learning, datos)</p>
                  <p v-if="errors.keywords" class="mt-2 text-sm text-red-600">{{ errors.keywords }}</p>
                </div>

                <div v-if="errors.general" class="bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-md p-3">
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

                <div class="mt-6 sm:mt-8 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                  <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex w-full justify-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:from-blue-700 hover:to-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed sm:col-start-2 transition-all duration-200"
                  >
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ loading ? 'Actualizando...' : 'Actualizar Investigador' }}
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
import { ref, watch, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { researcherService } from '@/services/researcherService'
import type { Researcher } from '@/types/researcher'

interface Props {
  show: boolean
  researcher: Researcher | null
}

interface Emits {
  close: []
  updated: []
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Form state
const form = ref({
  given_names: '',
  family_name: '',
  primary_email: '',
  keywords: [] as string[]
})

const loading = ref(false)
const errors = ref<{ [key: string]: string }>({})

// Computed for keywords as text
const keywordsText = computed({
  get: () => form.value.keywords.join(', '),
  set: (value: string) => {
    form.value.keywords = value
      .split(',')
      .map(k => k.trim())
      .filter(k => k.length > 0)
  }
})

// Methods
const resetForm = () => {
  if (props.researcher) {
    form.value = {
      given_names: props.researcher.given_names || '',
      family_name: props.researcher.family_name || '',
      primary_email: props.researcher.primary_email || '',
      keywords: [...props.researcher.keywords]
    }
  }
  errors.value = {}
}

const validateForm = () => {
  errors.value = {}
  
  if (form.value.primary_email && form.value.primary_email.trim()) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailPattern.test(form.value.primary_email)) {
      errors.value.primary_email = 'Formato de email inválido'
      return false
    }
  }
  
  return true
}

const handleSubmit = async () => {
  if (!props.researcher) return
  if (!validateForm()) return
  
  loading.value = true
  errors.value = {}
  
  try {
    await researcherService.update({
      orcid: props.researcher.orcid,
      given_names: form.value.given_names.trim() || undefined,
      family_name: form.value.family_name.trim() || undefined,
      primary_email: form.value.primary_email.trim() || undefined,
      keywords: form.value.keywords.length > 0 ? form.value.keywords : undefined
    })
    
    emit('updated')
  } catch (error: any) {
    errors.value.general = error.message
  } finally {
    loading.value = false
  }
}

// Watch for modal visibility and researcher changes
watch([() => props.show, () => props.researcher], ([newShow, newResearcher]) => {
  if (newShow && newResearcher) {
    resetForm()
  }
}, { immediate: true })
</script>

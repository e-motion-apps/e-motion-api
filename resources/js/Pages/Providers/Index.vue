<script setup>
import Provider from '../../Shared/Components/Provider.vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'
import { onClickOutside } from '@vueuse/core'
import { debounce } from 'lodash/function'
import Pagination from '@/Shared/Components/Pagination.vue'
import PaginationInfo from '@/Shared/Components/PaginationInfo.vue'
import PrimarySaveButton from '@/Shared/Components/PrimarySaveButton.vue'
import { useToast } from 'vue-toastification'
import { __ } from '@/translate'
import UploadFileButton from '../../Shared/Components/UploadFileButton.vue'


const page = usePage()
const toast = useToast()

const props = defineProps({
  providers: Object,
  errors: Object,
})

function storeProvider() {
  storeProviderForm.post('/admin/providers/', {
    onSuccess: () => {
      storeProviderForm.reset()
      toast.success(__('Provider created successfully.'))
      toggleStoreDialog()
    },
    onError: () => {
      toast.error(__('There was an error creating the provider.'))
    },
  })
}

const storeProviderForm = useForm({
  name: '',
  url: '',
  color: '',
  file: '',
})

const isStoreDialogOpened = ref(false)
const storeDialog = ref(null)
onClickOutside(storeDialog, () => (isStoreDialogOpened.value = false))

function toggleStoreDialog() {
  isStoreDialogOpened.value = !isStoreDialogOpened.value
}

const searchInput = ref('')

watch(searchInput, debounce(() => {
  router.get(`/admin/providers?search=${searchInput.value}`, {}, {
    preserveState: true,
    replace: true,
  })
}, 300), { deep: true })

function clearInput() {
  searchInput.value = ''
}

const isSortDialogOpened = ref(false)
const sortDialog = ref(null)
onClickOutside(sortDialog, () => (isSortDialogOpened.value = false))

const formattedColor = computed({
  get() {
    return storeProviderForm.color
  },
  set: function (colorValue) {
    colorValue = colorValue.startsWith('#') ? colorValue : `#${colorValue}`
    storeProviderForm.color = colorValue
  },
},
)

const formattedName = computed({
  get() {
    return storeProviderForm.name
  },
  set: function (nameValue) {
    nameValue = nameValue.charAt(0).toUpperCase() + nameValue.slice(1)
    storeProviderForm.name = nameValue
  },
},
)
</script>

<template>
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <AdminNavigation :url="page.url" />

    <div class="flex w-full md:justify-end">
      <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
        <div class="m-4 flex flex-col lg:mx-8">
          <div v-if="isStoreDialogOpened" class="fixed inset-0 z-50 flex items-center bg-black/50">
            <div ref="storeDialog"
                 class="mx-auto w-11/12 rounded-lg bg-white shadow-lg sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-1/3"
            >
              <div class="flex w-full justify-end">
                <button class="px-4 pt-4" @click="toggleStoreDialog">
                  <XMarkIcon class="h-6 w-6" />
                </button>
              </div>
              <div class="flex flex-col p-6 pt-0">
                <h1 class="mb-3 text-lg font-bold text-gray-800">
                  {{ __('Create provider') }}
                </h1>
                <form class="flex flex-col text-xs font-bold text-gray-600" @submit.prevent="storeProvider">
                  <label class="mb-1 mt-4">{{ __('Name') }}</label>
                  <input v-model="formattedName"
                         class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3"
                         type="text"
                  >
                  <ErrorMessage :message="storeProviderForm.errors.name" />
                  <label class="mb-1 mt-4">{{ __('Url') }}</label>
                  <input v-model="storeProviderForm.url"
                         class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3"
                         type="text"
                  >
                  <ErrorMessage :message="storeProviderForm.errors.url" />
                  <label class="mb-1 mt-4">{{ __('Color') }}</label>
                  <input v-model="formattedColor"
                         class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3"
                         type="text"
                  >
                  <ErrorMessage :message="storeProviderForm.errors.color" />
                  <label class="mb-1 mt-4">{{ __('Logo') }}</label>
                  <UploadFileButton type="file" accept="image/png"
                                    @input="storeProviderForm.file = $event.target.files[0]"
                  />
                  <ErrorMessage :message="storeProviderForm.errors.file" />
                  <div class="flex w-full justify-end">
                    <PrimarySaveButton>
                      {{ __('Save') }}
                    </PrimarySaveButton>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="mb-3 mt-4 flex flex-wrap items-center justify-end md:justify-between">
            <button
              class="mr-1 rounded bg-gray-200 px-5 py-3 text-sm font-medium text-white shadow-md hover:bg-gray-300 md:py-2" @click=""
            >
              {{ __('Create provider') }}
            </button>

            <div class="m-1 flex w-full rounded-md shadow-sm md:w-fit">
              <div class="relative flex grow items-stretch focus-within:z-10">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-800" />
                </div>
                <input v-model.trim="searchInput" type="text"
                       class="block w-full rounded border-0 py-3 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blumilk-300 sm:text-sm sm:leading-6 md:py-1.5"
                       :placeholder="__('Search provider')"
                >
              </div>
              <button v-if="searchInput.length" type="button"
                      class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-blumilk-25"
                      @click="clearInput"
              >
                <XMarkIcon class="h-5 w-5" />
              </button>
            </div>
          </div>

          <div class="flex w-full flex-wrap items-center justify-between">
            <div v-if="props.providers.data.length" class="w-1/2">
              <PaginationInfo :meta="props.providers.meta" />
            </div>
          </div>

          <div v-if="props.providers.data.length" class="rounded-lg ring-gray-300 sm:ring-1">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th scope="col"
                      class="py-3.5 pl-5 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:table-cell"
                  >
                    {{ __('Name') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ __('Url') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ __('Color') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="provider in props.providers.data" :key="provider.name" class="border-t">
                  <Provider :provider="provider" />
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else>
            <p class="mt-6 text-lg font-medium text-gray-500">
              {{ __('Sorry we couldn`t find any providers.') }}
            </p>
          </div>

          <Pagination :meta="props.providers.meta" :links="props.providers.links" />
        </div>
      </div>
    </div>
  </div>
</template>

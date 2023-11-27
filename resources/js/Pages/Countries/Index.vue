<script setup>
import Country from '../../Shared/Components/Country.vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { MagnifyingGlassIcon, XMarkIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'
import { onClickOutside } from '@vueuse/core'
import { debounce } from 'lodash/function'
import Pagination from '@/Shared/Components/Pagination.vue'
import PaginationInfo from '@/Shared/Components/PaginationInfo.vue'
import PrimarySaveButton from '@/Shared/Components/PrimarySaveButton.vue'
import { useToast } from 'vue-toastification'
import { __ } from '@/translate'

const page = usePage()
const toast = useToast()

function storeCountry() {
  storeCountryForm.post('/admin/countries/', {
    onSuccess: () => {
      storeCountryForm.reset()
      toggleStoreDialog()
      commaInputError.value = ''
      toast.success(__('Country created successfully.'))
    },
    onError: () => {
      toast.error(__('There was an error creating the country.'))
    },
  })
}

const storeCountryForm = useForm({
  name: '',
  alternativeName: '',
  latitude: '',
  longitude: '',
  iso: '',
})

const commaInputError = ref('')

function preventCommaInput(event) {
  if (event.key === ',') {
    event.preventDefault()
    commaInputError.value = __('Use `.` instead of `,`')
  }
}

const props = defineProps({
  countries: Object,
  errors: Object,
})

const isStoreDialogOpened = ref(false)
const storeDialog = ref(null)
onClickOutside(storeDialog, () => (isStoreDialogOpened.value = false))

function toggleStoreDialog() {
  isStoreDialogOpened.value = !isStoreDialogOpened.value
}

const searchInput = ref('')

watch(searchInput, debounce(() => {
  router.get(`/admin/countries?search=${searchInput.value}`, {}, {
    preserveState: true,
    replace: true,
  })
}, 300), { deep: true })



function clearInput() {
  searchInput.value = ''
}

const sortingOptions = [
  { name: 'Latest', href: '/admin/countries?order=latest' },
  { name: 'Oldest', href: '/admin/countries?order=oldest' },
  { name: 'By name', href: '/admin/countries?order=name' },
]

const isSortDialogOpened = ref(false)
const sortDialog = ref(null)
onClickOutside(sortDialog, () => (isSortDialogOpened.value = false))

function toggleSortDialog() {
  isSortDialogOpened.value = !isSortDialogOpened.value
}

</script>

<template>
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <AdminNavigation :url="page.url" />

    <div class="flex w-full md:justify-end">
      <div class="mt-16 h-full w-full md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
        <div class="m-4 flex flex-col lg:mx-8">
          <div v-if="isStoreDialogOpened" class="fixed inset-0 z-50 flex items-center bg-black/50">
            <div ref="storeDialog" class="mx-auto w-11/12 rounded-lg bg-white shadow-lg sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-1/3">
              <div class="flex w-full justify-end">
                <button class="px-4 pt-4" @click="toggleStoreDialog">
                  <XMarkIcon class="h-6 w-6" />
                </button>
              </div>

              <div class="flex flex-col p-6 pt-0">
                <h1 class="mb-3 text-lg font-bold text-gray-800">
                  {{ __('Create country') }}
                </h1>

                <form class="flex flex-col text-xs font-bold text-gray-600" @submit.prevent="storeCountry">
                  <label class="mb-1 mt-4">{{ __('Name') }}</label>
                  <input v-model="storeCountryForm.name" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" required>
                  <ErrorMessage :message="storeCountryForm.errors.name" />
                  <label class="mb-1 mt-4">{{ __('Alternative name') }}</label>
                  <input v-model="storeCountryForm.alternativeName" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text">
                  <ErrorMessage :message="storeCountryForm.errors.alternativeName" />
                  <label class="mb-1 mt-4">{{ __('Latitude') }}</label>
                  <input v-model="storeCountryForm.latitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" required @keydown="preventCommaInput">
                  <ErrorMessage :message="storeCountryForm.errors.latitude" />
                  <label class="mb-1 mt-4">{{ __('Longitude') }}</label>
                  <input v-model="storeCountryForm.longitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" required @keydown="preventCommaInput">
                  <ErrorMessage :message="storeCountryForm.errors.longitude" />
                  <label class="mb-1 mt-4">ISO</label>
                  <input v-model="storeCountryForm.iso" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" required>
                  <ErrorMessage :message="storeCountryForm.errors.iso" />
                  <small class="text-rose-600">{{ commaInputError }}</small>

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
            <button class="mr-1 rounded bg-blumilk-500 px-5 py-3 text-sm font-medium text-white shadow-md hover:bg-blumilk-400 md:py-2" @click="toggleStoreDialog">
              {{ __('Create country') }}
            </button>

            <div class="m-1 flex w-full rounded-md shadow-sm md:w-fit">
              <div class="relative flex grow items-stretch focus-within:z-10">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-800" />
                </div>
                <input v-model.trim="searchInput" type="text" class="block w-full rounded border-0 py-3 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blumilk-300 sm:text-sm sm:leading-6 md:py-1.5" :placeholder="__('Search country')">
              </div>
              <button v-if="searchInput.length" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-blumilk-25" @click="clearInput">
                <XMarkIcon class="h-5 w-5" />
              </button>
            </div>
          </div>

          <div
            :class="props.countries.data.length ? 'justify-between' : 'justify-end'"
            class="flex w-full flex-wrap items-center"
          >
            <div v-if="props.countries.data.length">
              <PaginationInfo :meta="props.countries.meta" />
            </div>

            <div class="relative inline-block text-left">
              <div>
                <button ref="sortDialog" class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900" aria-expanded="false" aria-haspopup="true" @click="toggleSortDialog">
                  {{ __('Sort') }}
                  <ChevronDownIcon class="ml-1 h-5 w-5" />
                </button>
              </div>

              <div v-if="isSortDialogOpened" class="absolute right-1 z-10 mt-3.5 w-max rounded-md bg-white shadow-lg shadow-gray-300 ring-1 ring-gray-300 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <InertiaLink v-for="option in sortingOptions" :key="option.href"
                               :href="option.href" class="block px-4 py-2 text-sm text-gray-500 hover:text-blumilk-400" role="menuitem" tabindex="-1"
                  >
                    <span :class="{'font-medium text-blumilk-400': page.url.startsWith(option.href) || ((page.url === '/admin/countries' || page.url.startsWith('/admin/countries?search=') || page.url.startsWith('/admin/countries?page=')) && option.href.startsWith('/admin/countries?order=latest'))}">
                      {{ __(option.name) }}
                    </span>
                  </InertiaLink>
                </div>
              </div>
            </div>
          </div>

          <div v-if="props.countries.data.length" class="rounded-lg ring-gray-300 sm:ring-1">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="py-3.5 pl-5 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:table-cell">
                    {{ __('Name') }}
                  </th>
                  <th scope="col" class="table-cell py-3.5 text-left text-sm font-semibold text-gray-900">
                    {{ __('Alternative name') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 xl:table-cell">
                    {{ __('Latitude') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 xl:table-cell">
                    {{ __('Longitude') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 xl:table-cell">
                    ISO
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="country in props.countries.data" :key="country.id" class="border-t">
                  <Country :country="country" />
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else>
            <p class="mt-6 text-lg font-medium text-gray-500">
              {{ __(`Sorry we couldn't find any countries.`) }}
            </p>
          </div>
          <Pagination :meta="props.countries.meta" :links="props.countries.links" />
        </div>
      </div>
    </div>
  </div>
</template>

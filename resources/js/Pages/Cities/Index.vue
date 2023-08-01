<script setup>
import City from './Components/City.vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { XMarkIcon,  MagnifyingGlassIcon } from '@heroicons/vue/24/outline'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'
import { onClickOutside } from '@vueuse/core'
import { debounce } from 'lodash/function'
import Pagination from '@/Shared/Components/Pagination.vue'
import PaginationInfo from '@/Shared/Components/PaginationInfo.vue'
import PrimarySaveButton from '@/Shared/Components/PrimarySaveButton.vue'

const page = usePage()

const props = defineProps({
  cities: Object,
  providers: Object,
  countries: Object,
})

const storeErrors = ref([])

const commaInputError = ref('')

function storeCity() {
  commaInputError.value = ''
  storeCityForm.post('/admin/cities', {
    onSuccess: () => {
      storeCityForm.reset()
      toggleStoreDialog()
    },
    onError: (errors) => {
      storeErrors.value = errors
    },
  })
}

const storeCityForm = useForm({
  name: '',
  latitude: '',
  longitude: '',
  country_id: '',
})

const isStoreDialogOpened = ref(false)
const storeDialog = ref(null)
onClickOutside(storeDialog, () => (isStoreDialogOpened.value = false))

function toggleStoreDialog() {
  isStoreDialogOpened.value = !isStoreDialogOpened.value
}

function preventCommaInput(event) {
  if (event.key === ',') {
    event.preventDefault()
    commaInputError.value = 'Use \'.\' instead of \',\''
  }
}

const searchInput = ref('')


watch(searchInput, debounce(() => {
  router.get(`/admin/cities?search=${searchInput.value}`, {}, {
    preserveState: true,
    replace: true,
  })
}, 300), { deep: true })


function clearInput() {
  searchInput.value = ''
}

</script>

<template>
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <AdminNavigation :url="page.url" />

    <div class="flex w-full md:justify-end">
      <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
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
                  {{ $t('CRUD.Create_city') }}
                </h1>

                <form class="flex flex-col text-xs font-bold text-gray-600" @submit.prevent="storeCity">
                  <label class="mb-1 mt-4">{{ $t('Auth.Name') }}</label>
                  <input v-model="storeCityForm.name" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" required>
                  <ErrorMessage :message="storeCityForm.errors.name" />

                  <label class="mb-1 mt-4">{{ $t('Technical.Latitude') }}</label>
                  <input v-model="storeCityForm.latitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                         required @keydown="preventCommaInput"
                  >
                  <ErrorMessage :message="storeCityForm.errors.latitude" />

                  <label class="mb-1 mt-4">{{ $t('Technical.Longitude') }}</label>
                  <input v-model="storeCityForm.longitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                         required @keydown="preventCommaInput"
                  >
                  <ErrorMessage :message="storeCityForm.errors.longitude" />
                  <p v-if="commaInputError" class="text-xs text-rose-600">
                    {{ commaInputError }}
                  </p>
                  <label class="mb-1 mt-4">{{ $t('Technical.Country') }}</label>
                  <select v-model="storeCityForm.country_id" required class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3">
                    <option v-for="country in props.countries" :key="country.id" class="m-6 p-6 " :value="country.id">
                      {{ country.name }}
                    </option>
                  </select>

                  <div class="flex w-full justify-end">
                    <PrimarySaveButton>
                      {{ $t('CRUD.Save') }}
                    </PrimarySaveButton>
                  </div>
                </form>
              </div>
            </div>
          </div>


          <div class="mb-3 mt-4 flex flex-wrap items-center justify-end md:justify-between">
            <button class="m-1 rounded bg-blumilk-500 px-5 py-3 text-sm font-medium text-white shadow-md md:py-2" @click="toggleStoreDialog">
              {{ $t('CRUD.Create_city') }}
            </button>

            <div class="m-1 flex w-full rounded-md shadow-sm md:w-fit">
              <div class="relative flex grow items-stretch focus-within:z-10">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-800" />
                </div>
                <input v-model.trim="searchInput" type="text" class="block w-full rounded border-0 py-3 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blumilk-300 sm:text-sm sm:leading-6 md:py-1.5" placeholder="Search city">
              </div>
              <button v-if="searchInput.length" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-blumilk-25" @click="clearInput">
                <XMarkIcon class="h-5 w-5" />
              </button>
            </div>
          </div>

          <div v-if="props.cities.data.length">
            <PaginationInfo :meta="props.cities.meta" />
          </div>

          <div v-if="props.cities.data.length" class="rounded-lg ring-gray-300 sm:ring-1">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="py-3.5 pl-5 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:table-cell">
                    {{ $t('Auth.Name') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ $t('Technical.Longitude') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ $t('Technical.Latitude') }}
                  </th>
                  <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ $t('Technical.Providers') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="city in props.cities.data" :key="city.id" class="border-t">
                  <City :providers="providers" :city="city" />
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else>
            <p class="mt-6 text-lg font-medium text-gray-500">
              {{ $t('Prompt.Sorry_we_couldnt_cities') }}
            </p>
          </div>

          <Pagination :meta="props.cities.meta" :links="props.cities.links" />
        </div>
      </div>
    </div>
  </div>
</template>

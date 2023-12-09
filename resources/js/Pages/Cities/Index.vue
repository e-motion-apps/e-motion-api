<script setup>
import City from '@/Shared/Components/City.vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { TrashIcon, MagnifyingGlassIcon, ChevronDownIcon, PlusCircleIcon, PencilSquareIcon, XMarkIcon } from '@heroicons/vue/24/outline'
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

const props = defineProps({
  cities: Object,
  providers: Object,
  countries: Object,
  citiesWithoutAssignedCountry: Object,
})

const countCitiesWithoutAssignedCountry = computed(() => page.props.countCitiesWithoutAssignedCountry)
const countCitiesWithoutCoordinates = computed(() => page.props.countCitiesWithoutCoordinates)

const storeErrors = ref([])

const commaInputError = ref('')

function storeCity() {
  commaInputError.value = ''
  storeCityForm.post('/admin/cities', {
    onSuccess: () => {
      storeCityForm.reset()
      toggleStoreDialog()
      toast.success(__('City created successfully.'))
    },
    onError: (errors) => {
      storeErrors.value = errors
      toast.error(__('There was an error creating the city.'))
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
    commaInputError.value = __('Use `.` instead of `,`')
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

const sortingOptions = [
  { name: 'Latest', href: '/admin/cities?order=latest' },
  { name: 'Oldest', href: '/admin/cities?order=oldest' },
  { name: 'By name', href: '/admin/cities?order=name' },
  { name: 'By providers', href: '/admin/cities?order=providers' },
  { name: 'By country', href: '/admin/cities?order=country' },
]

const isSortDialogOpened = ref(false)
const sortDialog = ref(null)
onClickOutside(sortDialog, () => (isSortDialogOpened.value = false))

function toggleSortDialog() {
  isSortDialogOpened.value = !isSortDialogOpened.value
}

const isCityWithoutCountriesListDialogOpened = ref(false)
const cityWithoutCountriesListDialog = ref(null)
onClickOutside(cityWithoutCountriesListDialog, () => (isCityWithoutCountriesListDialogOpened.value = false))

function toggleCityWithoutCountriesListDialog() {
  isCityWithoutCountriesListDialogOpened.value = !isCityWithoutCountriesListDialogOpened.value
}

function deleteCityWithoutAssignedCountry(city) {
  router.delete(`/delete-city-without-assigned-country/${city.id}`, {
    onSuccess: () => {
      toast.success(__('City deleted successfully.'))
    },
    onError: () => {
      toast.error(__('There was an error deleting the city.'))
    },
  })
}

function deleteAllCitiesWithoutCountry() {
  router.post('/delete-all-cities-without-assigned-country', {}, {
    onSuccess: () => {
      toast.success(__('Cities deleted successfully.'))
    },
    onError: () => {
      toast.error(__('There was an error deleting cities.'))
    },
  })
}

function searchCity(city) {
  searchInput.value = city.city_name
  toggleCityWithoutCountriesListDialog()
}

function sendCityToCreateForm(city) {
  storeCityForm.name = city.city_name
  toggleCityWithoutCountriesListDialog()
  toggleStoreDialog()
}

function goToGoogleMaps(city) {
  window.open('https://www.google.com/maps/search/' + city.city_name)
}

const searchCityWithoutCountryInput = ref('')

function clearCityWithoutCountryInput() {
  searchCityWithoutCountryInput.value = ''
}

const filteredCitiesWithoutCountry = computed(() => {
  return props.citiesWithoutAssignedCountry.filter(city => {
    return city.city_name.toLowerCase().includes(searchCityWithoutCountryInput.value.toLowerCase())
  })
})


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
                  {{ __('Create city') }}
                </h1>

                <form class="flex flex-col text-xs font-bold text-gray-600" @submit.prevent="storeCity">
                  <label class="mb-1 mt-4">{{ __('Name') }}</label>
                  <input v-model="storeCityForm.name" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" required>
                  <ErrorMessage :message="storeCityForm.errors.name" />

                  <label class="mb-1 mt-4">{{ __('Latitude') }}</label>
                  <input v-model="storeCityForm.latitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                         required @keydown="preventCommaInput"
                  >
                  <ErrorMessage :message="storeCityForm.errors.latitude" />

                  <label class="mb-1 mt-4">{{ __('Longitude') }}</label>
                  <input v-model="storeCityForm.longitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                         required @keydown="preventCommaInput"
                  >
                  <ErrorMessage :message="storeCityForm.errors.longitude" />
                  <p v-if="commaInputError" class="text-xs text-rose-600">
                    {{ commaInputError }}
                  </p>
                  <label class="mb-1 mt-4">{{ __('Country') }}</label>
                  <select v-model="storeCityForm.country_id" required class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3">
                    <option v-for="country in props.countries" :key="country.id" class="m-6 p-6 " :value="country.id">
                      {{ country.name }}
                    </option>
                  </select>

                  <div class="flex w-full justify-end">
                    <PrimarySaveButton>
                      {{ __('Save') }}
                    </PrimarySaveButton>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="mb-3 mt-4 flex w-full flex-wrap items-center justify-end md:justify-between">
            <button class="mr-1 rounded bg-blumilk-500 px-5 py-3 text-sm font-medium text-white shadow-md hover:bg-blumilk-400 md:py-2" @click="toggleStoreDialog">
              {{ __('Create city') }}
            </button>

            <div class="m-1 flex w-full rounded-md shadow-sm md:w-fit">
              <div class="relative flex grow items-stretch focus-within:z-10">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-800" />
                </div>
                <input v-model.trim="searchInput" type="text" class="block w-full rounded border-0 py-3 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blumilk-300 sm:text-sm sm:leading-6 md:py-1.5" :placeholder="__('Search city')">
              </div>
              <button v-if="searchInput.length" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-blumilk-25" @click="clearInput">
                <XMarkIcon class="h-5 w-5" />
              </button>
            </div>
          </div>

          <div v-if="countCitiesWithoutAssignedCountry" class="scrollbar mt-3 flex w-full justify-start overflow-auto">
            <button class="flex items-center rounded border border-rose-500 bg-white p-2 text-sm font-medium text-rose-500 hover:bg-rose-50" @click="toggleCityWithoutCountriesListDialog">
              <PencilSquareIcon class="mr-1 h-5 w-5" />
              {{ __('Cities with no country assigned') }}: {{ countCitiesWithoutAssignedCountry }}
            </button>
          </div>

          <div v-if="countCitiesWithoutCoordinates" class="scrollbar my-2 flex w-full justify-start overflow-auto">
            <InertiaLink :href="'/admin/cities?order=empty-coordinates'" class="flex items-center rounded border border-rose-500 bg-white p-2 text-sm font-medium text-rose-500 hover:bg-rose-50">
              <PencilSquareIcon class="mr-1 h-5 w-5" />
              {{ __('Cities with no coordinates assigned') }}: {{ countCitiesWithoutCoordinates }}
            </InertiaLink>
          </div>

          <div v-if="isCityWithoutCountriesListDialogOpened" class="flex flex-col">
            <div class="fixed inset-0 z-10 flex items-center bg-black/50 py-8">
              <div ref="cityWithoutCountriesListDialog" class="scrollbar mx-auto h-fit max-h-full w-11/12 overflow-y-auto rounded-lg bg-white pb-6 sm:w-5/6 md:w-3/4 lg:w-1/2 xl:w-1/3">
                <div class="flex w-full justify-end">
                  <button class="px-4 pt-4" @click="toggleCityWithoutCountriesListDialog">
                    <XMarkIcon class="h-6 w-6" />
                  </button>
                </div>
                <div class="flex flex-col">
                  <div class="h-full w-full flex-col px-6">
                    <h1 class="text-xl font-bold text-gray-800">
                      {{ __('Cities with no country assigned') }}:
                    </h1>
                    <div class="mb-2 mt-6 flex w-full rounded-md shadow-sm">
                      <div class="relative flex grow items-stretch focus-within:z-10">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                          <MagnifyingGlassIcon class="h-5 w-5 text-gray-800" />
                        </div>
                        <input v-model.trim="searchCityWithoutCountryInput" type="text" class="block w-full rounded border-0 py-3 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blumilk-300 sm:text-sm sm:leading-6 md:py-1.5" :placeholder="__('Search city')">
                      </div>
                      <button v-if="searchCityWithoutCountryInput.length" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-blumilk-25" @click="clearCityWithoutCountryInput">
                        <XMarkIcon class="h-5 w-5" />
                      </button>
                    </div>

                    <div v-if="filteredCitiesWithoutCountry.length" class="flex justify-end">
                      <button class="my-2 flex items-center rounded border border-rose-500 p-2 text-xs font-medium text-rose-500 hover:bg-rose-100" @click="deleteAllCitiesWithoutCountry">
                        <TrashIcon class="mr-1 h-4 w-4 shrink-0" />
                        {{ __('Delete all cities with no country assigned') }}
                      </button>
                    </div>

                    <div v-if="filteredCitiesWithoutCountry.length">
                      <div v-for="city in filteredCitiesWithoutCountry" :key="city.id"
                           class="mb-4 flex flex-col justify-center rounded border p-2 font-light"
                      >
                        <p class="cursor-pointer font-bold text-gray-800 hover:text-gray-500" @click="goToGoogleMaps(city)">
                          {{ city.city_name }}
                        </p>
                        <p class="text-sm font-medium text-blumilk-500">
                          {{ city.country_name }}
                        </p>
                        <div class="flex justify-end">
                          <button class="rounded-full p-1 hover:bg-gray-100">
                            <TrashIcon class="h-7 w-7 sm:h-5 sm:w-5" @click="deleteCityWithoutAssignedCountry(city)" />
                          </button>

                          <button class="ml-2 rounded-full p-1 hover:bg-gray-100">
                            <MagnifyingGlassIcon class="h-7 w-7 sm:h-5 sm:w-5" @click="searchCity(city)" />
                          </button>
                          <button class="ml-2 rounded-full p-1 hover:bg-gray-100">
                            <PlusCircleIcon class="h-7 w-7 sm:h-5 sm:w-5" @click="sendCityToCreateForm(city)" />
                          </button>
                        </div>
                      </div>
                    </div>

                    <p v-else class="mt-6 flex text-sm font-medium text-gray-500">
                      {{ __(`Didn't find anything. Just empty space.`) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            :class="props.cities.data.length ? 'justify-between' : 'justify-end'"
            class="flex w-full flex-wrap items-center"
          >
            <div v-if="props.cities.data.length" class="w-1/2">
              <PaginationInfo :meta="props.cities.meta" />
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
                    <span :class="{'font-medium text-blumilk-400': page.url.startsWith(option.href) || ((page.url === '/admin/cities' || page.url.startsWith('/admin/cities?search=') || page.url.startsWith('/admin/cities?page=')) && option.href.startsWith('/admin/cities?order=latest'))}">
                      {{ __(option.name) }}
                    </span>
                  </InertiaLink>
                </div>
              </div>
            </div>
          </div>

          <div v-if="props.cities.data.length" class="rounded-lg ring-gray-300 sm:ring-1">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="py-3.5 pl-5 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:table-cell">
                    {{ __('Name') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ __('Longitude') }}
                  </th>
                  <th scope="col" class="hidden py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ __('Latitude') }}
                  </th>
                  <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    {{ __('Providers') }}
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
              {{ __(`Sorry we couldn't find any cities.`) }}
            </p>
          </div>

          <Pagination :meta="props.cities.meta" :links="props.cities.links" />
        </div>
      </div>
    </div>
  </div>
</template>

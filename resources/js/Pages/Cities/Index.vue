<script setup>
import City from './Components/City.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'
import AdminNavigation from '../../Shared/Components/AdminNavigation.vue'
import { FolderOpenIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import ErrorMessage from '../../Shared/Components/ErrorMessage.vue'

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
  storeCityForm.post('/admin/dashboard/cities', {
    onSuccess: () => {
      storeCityForm.reset()
      storeCityForm.country_id = '1'
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


function preventCommaInput(event) {
  if (event.key === ',') {
    event.preventDefault()
    commaInputError.value = 'Use \'.\' instead of \',\''
  }
}

const searchInput = ref('')

const filteredCities = computed(() => {
  return props.cities.filter(city => {
    if (city.name.toLowerCase().includes(searchInput.value.toLowerCase()) || city.city_alternative_names.some(alternativeName =>
      alternativeName.name.toLowerCase().includes(searchInput.value.toLowerCase()))) {
      return true
    }

    return false
  })
})

function clearInput() {
  searchInput.value = ''
}

onMounted(() => {
  storeCityForm.country_id = '1'
})

</script>

<template>
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <AdminNavigation :url="page.url" />

    <div class="flex w-full md:justify-end">
      <div class="mt-16 h-full w-full md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
        <div class="m-4 flex flex-col lg:mx-8">
          <div class="flex flex-col">
            <h1 class="mb-1 text-lg font-bold text-gray-800">
              Create city
            </h1>
            <div class="rounded border border-blumilk-50 bg-blumilk-25 p-3 shadow-lg lg:w-1/2 xl:w-2/5">
              <form class="flex flex-col space-y-2" @submit.prevent="storeCity">
                <input v-model="storeCityForm.name" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text"
                       placeholder="Name" required
                >
                <ErrorMessage :message="storeCityForm.errors.name" />
                <input v-model="storeCityForm.latitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                       placeholder="Latitude" required @keydown="preventCommaInput"
                >
                <ErrorMessage :message="storeCityForm.errors.latitude" />
                <input v-model="storeCityForm.longitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                       placeholder="Longitude" required @keydown="preventCommaInput"
                >
                <ErrorMessage :message="storeCityForm.errors.longitude" />
                <p v-if="commaInputError" class="text-xs text-rose-600">
                  {{ commaInputError }}
                </p>
                <select v-model="storeCityForm.country_id" class="rounded-md border border-blumilk-50 bg-blumilk-50 p-4 text-sm font-semibold text-gray-800 shadow-md md:p-3">
                  <option v-for="country in props.countries" :key="country.id" class="m-6 p-6 " :value="country.id">
                    {{ country.name }}
                  </option>
                </select>

                <div class="flex w-full justify-end">
                  <button type="submit" class="mt-4 flex w-full shrink-0 rounded bg-emerald-500 px-5 py-3 text-white hover:bg-emerald-600 md:w-fit md:py-2">
                    <span class="flex flex-wrap items-center justify-center space-x-2">
                      <span class="font-bold">Save</span>
                      <FolderOpenIcon class="h-5 w-5" />
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="mb-4 mt-8 flex flex-col">
            <label class="relative block shadow-lg lg:w-1/2 xl:w-2/5">
              <input
                v-model.trim="searchInput"
                class="w-full rounded-md border border-blumilk-50 bg-blumilk-25 py-4 pl-3 text-sm font-semibold text-gray-800"
                type="text"
                placeholder="Search city"
              >

              <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                <button v-if="searchInput.length" class="px-1" @click="clearInput">
                  <XMarkIcon class="h-5 w-5" />
                </button>
              </span>
            </label>
          </div>

          <div v-for="city in filteredCities" :key="city.id">
            <City :providers="providers" :city="city" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

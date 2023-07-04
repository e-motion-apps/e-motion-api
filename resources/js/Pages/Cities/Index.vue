<script setup>
import City from './Components/City.vue'
import { useForm } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
  cities: Object,
  providers: Object,
  countries: Object,
  errors: Object,
})

const commaInputError = ref('')

function storeCity() {
  commaInputError.value = ''
  storeCityForm.post('/admin/dashboard/cities', {
    onSuccess: () => {
      storeCityForm.reset()
      storeCityForm.country_id = '167'
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
  storeCityForm.country_id = '167'
})

</script>

<template>
  <div class="flex flex-col">
    <div class="m-2 flex flex-col rounded border bg-gray-50 p-2">
      <div v-for="(error, index) in props.errors" :key="index">
        <p class="text-xs text-red-600">
          {{ error }}
        </p>
      </div>
      <div class="my-2 space-y-2 rounded border p-3 shadow-lg md:w-2/3 lg:w-1/2">
        <p class="my-3 text-xs font-bold">
          Store city
        </p>

        <form class="flex flex-col" @submit.prevent="storeCity">
          <div class="flex flex-col space-y-2">
            <input v-model="storeCityForm.name" class="border px-2 py-1 shadow" type="text" placeholder="Name" required>
            <input v-model="storeCityForm.latitude" class="border px-2 py-1 shadow" type="text" placeholder="Latitude" required @keydown="preventCommaInput">
            <input v-model="storeCityForm.longitude" class="border px-2 py-1 shadow" type="text" placeholder="Longitude" required @keydown="preventCommaInput">
            <p v-if="commaInputError" class="text-xs text-rose-600">
              {{ commaInputError }}
            </p>
            <select v-model="storeCityForm.country_id" class="border bg-zinc-100 px-2 py-1 shadow">
              <option v-for="country in props.countries" :key="country.id" :value="country.id">
                {{ country.name }}
              </option>
            </select>
          </div>
          <div class="flex w-full justify-end">
            <button type="submit" class="mt-6 flex w-full shrink-0 rounded bg-green-600 px-5 py-3 text-white md:w-fit md:py-2">
              <span class="flex items-center justify-center space-x-2">
                <span>Save</span>
                <img class="shrink-0" width="18" src="https://img.icons8.com/ios/50/FFFFFF/save--v1.png" alt="save--v1">
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="mx-2 mb-4 mt-8 flex flex-col">
      <label class="relative block shadow-lg md:w-2/3 lg:w-1/2">
        <input
          v-model.trim="searchInput"
          class="w-full rounded border bg-gray-50 py-4 pl-3"
          type="text"
          placeholder="Search city"
        >

        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
          <button class="px-1" @click="clearInput">
            <img alt="" src="https://img.icons8.com/ios-filled/20/676767/delete-sign--v1.png">
          </button>
        </span>
      </label>
    </div>

    <div v-for="city in filteredCities" :key="city.id">
      <City :providers="providers" :city="city" />
    </div>
  </div>
</template>

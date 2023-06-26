<script setup>
import Country from './Components/Country.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

function storeCountry() {
  storeCountryForm.post('/admin/dashboard/countries/', {
    onSuccess: () => {
      storeCountryForm.reset()
      commaInputError.value = ''
    },
  })
}

const storeCountryForm = useForm({
  name: '',
  alternative_name: '',
  latitude: '',
  longitude: '',
  iso: '',
})

const commaInputError = ref('')

function preventCommaInput(event) {
  if (event.key === ',') {
    event.preventDefault()
    commaInputError.value = 'Use \'.\' instead of \',\''
  }
}

const props = defineProps({
  countries: Object,
  errors: Object,
})

</script>

<template>
  <div>
    <div class="flex flex-col">
      <div class="m-2 flex flex-col rounded border bg-gray-50 p-2">
        <div v-for="(error, index) in props.errors" :key="index">
          <p class="text-xs text-red-600">
            {{ error }}
          </p>
        </div>

        <div class="mt-2 w-full space-y-2">
          <h1 class="text-xl">
            Store country
          </h1>
          <form class="flex w-1/2 flex-col space-y-2" @submit.prevent="storeCountry">
            <input v-model="storeCountryForm.name" class="border px-2 py-1" type="text" placeholder="Name" required>
            <input v-model="storeCountryForm.alternative_name" class="border px-2 py-1" type="text" placeholder="Alternative name">
            <input v-model="storeCountryForm.latitude" class="border px-2 py-1" type="text" placeholder="Latitude" required @keydown="preventCommaInput">
            <input v-model="storeCountryForm.longitude" class="border px-2 py-1" type="text" placeholder="Longitude" required @keydown="preventCommaInput">
            <input v-model="storeCountryForm.iso" class="border px-2 py-1" type="text" placeholder="ISO 3166" required>
            <small class="text-rose-600">{{ commaInputError }}</small>

            <button type="submit" class="flex w-fit items-center rounded bg-green-600 px-5 py-2 text-white">
              <img width="18" src="https://img.icons8.com/ios/50/FFFFFF/save--v1.png" alt="save--v1">
            </button>
          </form>
        </div>
      </div>
      <div v-for="country in countries" :key="country.id">
        <Country :country="country" />
      </div>
    </div>
  </div>
</template>

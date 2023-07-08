<script setup>
import Country from './Components/Country.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminNavigation from '../../Shared/Components/AdminNavigation.vue'
import { FolderOpenIcon } from '@heroicons/vue/24/outline'

const page = usePage()

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
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <AdminNavigation :url="page.url" />

    <div class="mx-auto mt-16 h-full w-full md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
      <div class="m-4 flex flex-col lg:mx-8">
        <div class="flex flex-col">
          <div v-for="(error, index) in props.errors" :key="index">
            <p class="text-xs text-red-600">
              {{ error }}
            </p>
          </div>

          <div class="mb-6 rounded border border-blumilk-50 bg-blumilk-25 p-3 shadow-lg lg:w-1/2 xl:w-2/5">
            <div class="w-full space-y-2">
              <h1 class="mb-3 text-lg font-bold text-gray-800">
                Store country
              </h1>

              <form class="flex flex-col space-y-2" @submit.prevent="storeCountry">
                <input v-model="storeCountryForm.name" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" placeholder="Name" required>
                <input v-model="storeCountryForm.alternative_name" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" placeholder="Alternative name">
                <input v-model="storeCountryForm.latitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" placeholder="Latitude" required @keydown="preventCommaInput">
                <input v-model="storeCountryForm.longitude" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" placeholder="Longitude" required @keydown="preventCommaInput">
                <input v-model="storeCountryForm.iso" class="rounded-md border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 md:p-3" type="text" placeholder="ISO 3166" required>
                <small class="text-rose-600">{{ commaInputError }}</small>

                <div class="flex w-full justify-end">
                  <button type="submit" class="mt-2 flex w-full shrink-0 rounded bg-emerald-500 px-5 py-3 text-white hover:bg-emerald-600 md:w-fit md:py-2">
                    <span class="flex flex-wrap items-center justify-center space-x-2">
                      <span class="font-bold">Save</span>
                      <FolderOpenIcon class="h-5 w-5" />
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div v-for="country in countries" :key="country.id">
            <Country :country="country" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
  country: Object,
})

function destroyCountry(countryId) {
  router.delete(`/admin/dashboard/countries/${countryId}`)
}

function updateCountry(countryId) {
  updateCountryForm.patch(`/admin/dashboard/countries/${countryId}`, {
    onSuccess: () => {
      openEditWindow()
    },
  })
}

const updateCountryForm = useForm({
  name: props.country.name,
  alternative_name: props.country.alternative_name,
  latitude: props.country.latitude,
  longitude: props.country.longitude,
  iso: props.country.iso,
})

const error = ref('')

function preventCommaInput(event) {
  if (event.key === ',') {
    event.preventDefault()
    error.value = 'Use \'.\' instead of \',\''
  }
}

const isEditWindowOpened = ref(false)

function openEditWindow() {
  isEditWindowOpened.value = !isEditWindowOpened.value
  error.value = ''
}

</script>

<template>
  <div class="m-2 flex flex-col rounded border bg-gray-50 p-2">
    <div class="huge flat flags flex items-center space-x-2">
      <i :class="country.iso" class="flat flag" />
      <p>{{ country.name }}</p>
    </div>

    <div v-if="isEditWindowOpened" class="mt-2 w-full space-y-2">
      <form class="flex w-1/2 flex-col space-y-2" @submit.prevent="updateCountry(country.id)">
        <input v-model="updateCountryForm.name" class="border px-2 py-1" type="text" placeholder="Name" required>
        <input v-model="updateCountryForm.alternative_name" class="border px-2 py-1" type="text" placeholder="Alternative name">
        <input v-model="updateCountryForm.latitude" class="border px-2 py-1" type="text" placeholder="Latitude" required @keydown="preventCommaInput">
        <input v-model="updateCountryForm.longitude" class="border px-2 py-1" type="text" placeholder="Longitude" required @keydown="preventCommaInput">
        <input v-model="updateCountryForm.iso" class="border px-2 py-1" type="text" placeholder="ISO 3166" required>
        <small class="text-rose-600">{{ error }}</small>

        <div class="flex justify-between">
          <button type="submit" class="flex w-fit rounded border border-blue-500 bg-white px-5 py-2 text-white">
            <img width="18" src="https://img.icons8.com/ios/50/3382f6/save--v1.png" alt="">
          </button>

          <button class="border-light flex w-fit items-center space-x-1 rounded-full border border-gray-500 bg-gray-100 px-2" @click="openEditWindow">
            <span class="text-xs font-light">Close</span>
            <img width="16" src="https://img.icons8.com/ios/50/delete-sign--v1.png" alt="">
          </button>
        </div>
      </form>
    </div>

    <div v-if="!isEditWindowOpened" class="mt-2 flex space-x-2">
      <button class="flex w-fit rounded bg-[#527aba] px-5 py-2 text-white" @click="openEditWindow">
        <img width="18" src="https://img.icons8.com/ios-filled/50/FFFFFF/edit--v1.png" alt="">
      </button>

      <button class="flex w-fit items-center rounded bg-rose-500 px-5 py-2 text-white" @click="destroyCountry(country.id)">
        <img width="18" src="https://img.icons8.com/ios/50/FFFFFF/trash--v1.png" alt="">
      </button>
    </div>
  </div>
</template>

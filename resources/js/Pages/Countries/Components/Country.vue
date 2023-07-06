<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { FolderOpenIcon, PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'

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

const commaInputError = ref('')

function preventCommaInput(event) {
  if (event.key === ',') {
    event.preventDefault()
    commaInputError.value = 'Use \'.\' instead of \',\''
  }
}

const isEditWindowOpened = ref(false)

function openEditWindow() {
  isEditWindowOpened.value = !isEditWindowOpened.value
  commaInputError.value = ''
}

</script>

<template>
  <div class="my-2 flex flex-col rounded border border-blumilk-50 bg-blumilk-25 p-4">
    <div class="huge flat flags flex items-center space-x-2 break-all">
      <i :class="country.iso" class="flat flag shrink-0" />
      <p class="text-lg font-semibold text-gray-800">
        {{ country.name }}
      </p>
    </div>

    <div v-if="isEditWindowOpened" class="mt-2 w-full space-y-2">
      <form class="flex flex-col space-y-2 lg:w-3/4 xl:w-1/2" @submit.prevent="updateCountry(country.id)">
        <input v-model="updateCountryForm.name" class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text" placeholder="Name" required>
        <input v-model="updateCountryForm.alternative_name" class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text" placeholder="Alternative name">
        <input v-model="updateCountryForm.latitude" class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text" placeholder="Latitude" required @keydown="preventCommaInput">
        <input v-model="updateCountryForm.longitude" class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text" placeholder="Longitude" required @keydown="preventCommaInput">
        <input v-model="updateCountryForm.iso" class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text" placeholder="ISO 3166" required>
        <small class="text-rose-600">{{ commaInputError }}</small>

        <div class="flex items-center justify-between">
          <button type="submit" class="flex w-fit items-center rounded border border-blumilk-500 bg-white px-5 py-2 text-blumilk-500">
            <span class="flex items-center justify-end space-x-2">
              <span class="font-bold">Save</span>
              <FolderOpenIcon class="h-5 w-5" />
            </span>
          </button>
        </div>
      </form>
      <div class="flex justify-end">
        <button
          class="my-1 flex w-fit shrink-0 items-center rounded-full border border-zinc-300 bg-white p-2 shadow-xl"
          @click="openEditWindow"
        >
          <XMarkIcon class="h-5 w-5" />
        </button>
      </div>
    </div>

    <div v-if="!isEditWindowOpened" class="mt-2 flex flex-wrap">
      <button class="my-1 mr-1 flex w-fit rounded bg-blumilk-500 px-5 py-2 text-white" @click="openEditWindow">
        <PencilIcon class="h-5 w-5" />
      </button>

      <button class="my-1 mr-1 flex w-fit items-center rounded bg-rose-500 px-5 py-2 text-white" @click="destroyCountry(country.id)">
        <TrashIcon class="h-5 w-5" />
      </button>
    </div>
  </div>
</template>

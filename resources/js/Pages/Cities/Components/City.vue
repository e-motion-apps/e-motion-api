<script setup>
import { onMounted, reactive, ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
  city: Object,
  providers: Object,
})

function destroyCity(cityId) {
  router.delete(`/admin/dashboard/cities/${cityId}`)
}

function updateCity(cityId) {
  updateCityForm.patch(`/admin/dashboard/cities/${cityId}`, {
    onSuccess: () => {
      openEditWindow()
    },
  })
}

const updateCityForm = useForm({
  name: props.city.name,
  latitude: props.city.latitude,
  longitude: props.city.longitude,
})

function storeAlternativeCityName(cityId) {
  router.post('/city-alternative-name', {
    name: storeCityAlternativeNameForm.name,
    city_id: cityId,
  }, {
    onSuccess: () => {
      storeCityAlternativeNameForm.name = ''
    },
  })
}

const storeCityAlternativeNameForm = reactive({
  name: '',
})

function destroyAlternativeCityName(alternativeCityNameId) {
  router.delete(`/city-alternative-name/${alternativeCityNameId}`, { replace: true })
}

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

const selectedProviders = reactive([])

onMounted(() => {
  props.city.providers.forEach(provider => {
    selectedProviders.push(provider.provider_list_id)
  })
})

function toggleProviderSelection(provider) {
  if (selectedProviders.includes(provider)) {
    const index = selectedProviders.indexOf(provider)
    selectedProviders.splice(index, 1)
  } else {
    selectedProviders.push(provider)
  }
}


function updateCityProviders(cityId) {
  router.patch(`/update-city-providers/${cityId}`, {
    providers: selectedProviders,
  }, {
    onSuccess: () => {
      openEditWindow()
    },
  })
}

</script>

<template>
  <div class="m-2 flex flex-col rounded border bg-gray-50 p-2">
    <div class="flex w-full justify-between">
      <div class="flex w-1/2 flex-col">
        <p class="flex flex-col items-start break-all text-lg font-bold">
          <i :class="city.country.iso" class="flat large flag mb-1 shrink-0" />
          {{ city.name }}
        </p>

        <div v-for="alternativeName in props.city.city_alternative_names" :key="alternativeName.id" class="">
          <p class="w-fit cursor-pointer break-all text-sm font-bold text-zinc-600 hover:line-through"
             @click="destroyAlternativeCityName(alternativeName.id)"
          >
            {{ alternativeName.name }}
          </p>
        </div>

        <div v-if="!isEditWindowOpened" class="flex flex-wrap">
          <button class="my-1 mr-1 flex w-fit shrink-0 rounded bg-[#527aba] px-5 py-2 text-white"
                  @click="openEditWindow"
          >
            <img width="18" src="https://img.icons8.com/ios-filled/50/FFFFFF/edit--v1.png" alt="">
          </button>

          <button class="my-1 mr-1 flex w-fit shrink-0 items-center rounded bg-rose-500 px-5 py-2 text-white"
                  @click="destroyCity(city.id)"
          >
            <img width="18" src="https://img.icons8.com/ios/50/FFFFFF/trash--v1.png" alt="">
          </button>
        </div>
      </div>

      <div v-if="!isEditWindowOpened" class="items-top ml-2 flex h-1/2 w-1/2 flex-row-reverse flex-wrap">
        <div
          v-for="provider in props.providers"
          :key="provider.id"
          :style="{'background-color': selectedProviders.includes(provider.id) ? provider.color : ''}"
          :class="selectedProviders.includes(provider.id) ? 'border-zinc-600 drop-shadow-lg' : 'hidden'"
          class="m-1 flex h-8 w-fit items-center justify-center rounded-lg border border-zinc-300 bg-zinc-300 p-1 "
        >
          <img class="w-8" :src="'/providers/' + provider.name + '.png'" alt="">
        </div>
      </div>
    </div>

    <div v-if="isEditWindowOpened" class="mt-2 flex w-full flex-col space-y-4">
      <form class="flex flex-col space-y-2 rounded border border-zinc-200 p-3 shadow-md md:w-2/3 lg:w-1/2"
            @submit.prevent="updateCity(city.id)"
      >
        <label class="my-3 text-xs font-bold">Update city</label>
        <input v-model="updateCityForm.name" class="border px-2 py-1 shadow" type="text" placeholder="Name"
               required
        >
        <input v-model="updateCityForm.latitude" class="border px-2 py-1 shadow" type="text"
               placeholder="Latitude" required @keydown="preventCommaInput"
        >
        <input v-model="updateCityForm.longitude" class="border px-2 py-1 shadow" type="text"
               placeholder="Longitude" required @keydown="preventCommaInput"
        >
        <small class="text-rose-600">{{ commaInputError }}</small>

        <div class="flex w-full justify-end">
          <button type="submit" class="mt-6 flex w-full shrink-0 rounded border border-blue-500 bg-white px-5 py-3 text-[#3382f6] hover:bg-blue-100 md:w-fit md:py-2">
            <span class="flex justify-end space-x-2">
              <span class="text-[#3382f6]">Save</span>
              <img width="18" src="https://img.icons8.com/ios/50/3382f6/save--v1.png" alt="">
            </span>
          </button>
        </div>
      </form>

      <form class="flex flex-col rounded border border-zinc-200 p-3 pt-6 shadow-md md:w-2/3 lg:w-1/2"
            @submit.prevent="storeAlternativeCityName(city.id)"
      >
        <label class="mb-3 text-xs font-bold">Add alternative city name</label>
        <div class="flex flex-col">
          <input v-model="storeCityAlternativeNameForm.name" class="rounded border px-4 py-2 shadow"
                 type="text" required
          >
          <div class="flex w-full justify-end">
            <button type="submit"
                    class="mt-6 flex w-full shrink-0 rounded border border-blue-500 bg-white px-5 py-3 text-[#3382f6] hover:bg-blue-100 md:w-fit md:py-2"
            >
              <span class="flex justify-end space-x-2">
                <span class="text-[#3382f6]">Save</span>
                <img width="18" src="https://img.icons8.com/ios/50/3382f6/save--v1.png" alt="">
              </span>
            </button>
          </div>
        </div>
      </form>

      <div class="flex flex-col rounded border border-zinc-200 p-3 shadow-md md:w-2/3 lg:w-1/2">
        <p class="my-3 flex text-xs font-bold">
          Providers
        </p>
        <div class="flex flex-wrap">
          <div
            v-for="provider in props.providers"
            :key="provider.id"
            :style="{'background-color': selectedProviders.includes(provider.id) ? provider.color : ''}"
            :class="selectedProviders.includes(provider.id) ? 'border-zinc-600 drop-shadow-lg' : ''"
            class="mx-1 my-2 flex w-fit cursor-pointer items-center justify-center rounded-lg border border-zinc-300 bg-zinc-300 p-1 "
            @click="toggleProviderSelection(provider.id)"
          >
            <input
              v-model="selectedProviders"
              class="hidden"
              type="checkbox"
            >
            <label class="cursor-pointer">
              <img class="w-14" :src="'/providers/' + provider.name + '.png'" alt="">
            </label>
          </div>
        </div>
        <div class="flex w-full justify-end">
          <button type="submit" class="mt-6 flex w-full shrink-0 rounded border border-blue-500 bg-white px-5 py-3 text-[#3382f6] hover:bg-blue-100 md:w-fit md:py-2"
                  @click="updateCityProviders(city.id)"
          >
            <span class="flex justify-end space-x-2">
              <span class="text-[#3382f6]">Save</span>
              <img width="18" src="https://img.icons8.com/ios/50/3382f6/save--v1.png" alt="">
            </span>
          </button>
        </div>
      </div>
      <div class="flex justify-end">
        <button
          class="my-1 flex w-fit shrink-0 items-center rounded-full border border-zinc-300 bg-white p-3 shadow-xl"
          @click="openEditWindow"
        >
          <img width="16" src="https://img.icons8.com/ios/50/delete-sign--v1.png" alt="">
        </button>
      </div>
    </div>
  </div>
</template>

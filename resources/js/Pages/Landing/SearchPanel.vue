<script setup>
import { useFilterStore } from '@/Shared/Stores/FilterStore'
import { computed, ref, onMounted } from 'vue'
import { TrashIcon } from '@heroicons/vue/24/outline'
import FavoriteButton from '@/Shared/Components/FavoriteButton.vue'
import axios from 'axios'

const filterStore = useFilterStore()

const props = defineProps({
  cities: Array,
  providers: Array,
  countries: Array,
})

const authenticated = ref(false)
const user = ref(null)

onMounted(() => {
  axios.get('/user')
    .then(response => {
      user.value = response.data
      authenticated.value = true
    })
    .catch(() => {
      console.error('User not authenticated.')
      authenticated.value = false
    })
})

const filteredCities = computed(() => {
  const selectedCountryId = filterStore.selectedCountryId
  const selectedProviderId = filterStore.selectedProviderId

  if (selectedCountryId === null && selectedProviderId === null) {
    return props.cities
  } else if (selectedCountryId !== null && selectedProviderId === null) {
    return props.cities.filter(city => city.country.id === selectedCountryId)
  } else if (selectedCountryId === null && selectedProviderId !== null) {
    return props.cities.filter(city =>
      city.cityProviders.some(cityProvider => cityProvider.provider_id === selectedProviderId),
    )
  } else {
    return props.cities.filter(city =>
      city.country.id === selectedCountryId &&
      city.cityProviders.some(cityProvider => cityProvider.provider_id === selectedProviderId),
    )
  }
})

const filteredProviders = computed(() => {
  const selectedCountryId = filterStore.selectedCountryId

  if (selectedCountryId === null) {
    return props.providers
  } else {
    return props.providers.filter(provider =>
      props.cities.some(city =>
        city.country.id === selectedCountryId &&
        city.cityProviders.some(cityProvider =>
          cityProvider.provider_id === provider.id,
        ),
      ),
    )
  }
})

const filteredCountries = computed(() => {
  const selectedProviderId = filterStore.selectedProviderId
  const selectedCountryId = filterStore.selectedCountryId

  if (selectedProviderId === null) {
    return props.countries.map(country => ({
      ...country,
      hasProvider: true,
      isSelected: country.id === selectedCountryId,
    }))
  } else {
    return props.countries.map(country => {
      const hasProvider = props.cities.some(city =>
        city.country.id === country.id &&
        city.cityProviders.some(cityProvider =>
          cityProvider.provider_id === selectedProviderId,
        ),
      )

      return {
        ...country,
        hasProvider,
        isSelected: country.id === selectedCountryId,
      }
    })
  }
})

function filterCountry(countryId) {
  filterStore.changeSelectedProvider(null)

  if (filterStore.selectedCountryId === countryId) {
    filterStore.changeSelectedCountry(null)
  } else {
    filterStore.changeSelectedCountry(countryId)
  }
}

function filterProvider(providerId) {
  if (filterStore.selectedProviderId === providerId) {
    filterStore.changeSelectedProvider(null)
  } else {
    filterStore.changeSelectedProvider(providerId)
  }
}

function clearFilters() {
  filterStore.changeSelectedProvider(null)
  filterStore.changeSelectedCountry(null)
}

function showCity(city) {
  filterStore.changeSelectedCity(city)
}
</script>

<template>
  <div class="mx-auto mt-12 flex w-11/12 flex-col lg:w-5/6 ">
    <h1 class="mb-1 text-[11px] font-medium text-gray-600">
      Countries
    </h1>
    <ul role="list" class="scrollbar flex space-x-2 overflow-x-auto pb-2">
      <li v-for="country in filteredCountries" :key="country.id" class="col-span-1 flex cursor-pointer rounded-md"
          :class="{ 'opacity-25': !country.hasProvider }" @click="filterCountry(country.id)"
      >
        <div class="flex w-12 shrink-0 items-center justify-center rounded-l-md bg-gray-100 py-3">
          <i class="large flat flag" :class="[country.iso, country.isSelected ? 'animate-bounce pb-0' : 'pb-3']" />
        </div>
        <div
          class="flex flex-1 items-center justify-between truncate rounded-r-md border-y border-r border-gray-100 bg-white"
        >
          <div class="flex-1 truncate px-3 text-sm">
            <span class="text-xs font-medium text-gray-600">{{ country.name }}</span>
          </div>
        </div>
      </li>
    </ul>

    <h1 class="mb-1 mt-4 text-[11px] font-medium text-gray-600">
      Providers
    </h1>
    <ul role="list" class="scrollbar flex space-x-2 overflow-x-auto">
      <li v-for="provider in filteredProviders" :key="provider.id"
          class="mb-2 flex h-8 w-fit shrink-0 cursor-pointer items-center justify-center rounded-md border border-zinc-300 p-1"
          :style="{ 'background-color': provider.color }"
          :class="{ 'opacity-25': filterStore.selectedProviderId !== null && filterStore.selectedProviderId !== provider.id }"
          @click="filterProvider(provider.id)"
      >
        <img class="w-8" :src="'/providers/' + provider.name + '.png'" alt="">
      </li>
    </ul>

    <div v-if="filterStore.selectedCountryId !== null || filterStore.selectedProviderId !== null"
         class="flex justify-end sm:justify-start"
    >
      <button
        class="mt-3 flex w-fit items-center rounded-lg bg-gray-50 px-3 py-1 text-[10px] font-medium text-gray-500 hover:bg-gray-100"
        @click="clearFilters"
      >
        <TrashIcon class="mr-1 h-4 w-4" />
        Clear filters
      </button>
    </div>

    <ul v-if="filteredCities.length" role="list" class="mt-8 flex w-full flex-col divide-y divide-gray-300">
      <li v-for="city in filteredCities" :key="city.id"
          class="group flex cursor-pointer flex-col items-start justify-between gap-x-6 pb-1 pt-4 sm:flex-row sm:pb-4"
          @click="showCity(city)"
      >
        <div class="flex min-w-max items-center">
          <i :class="city.country.iso" class="flat flag huge shrink-0" @click="filterCountry(city.country.id)" />
          <div class="ml-4 flex flex-col justify-start">
            <p class="mr-2 break-all font-bold text-gray-900 group-hover:text-gray-500">
              {{ city.name }}
            </p>
            <p class="break-all text-xs font-semibold text-blumilk-500">
              {{ city.country.name }}
            </p>
            <FavoriteButton v-if="authenticated" :cityid="city.id" />
          </div>
        </div>

        <div class="mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
          <div v-for="provider in filteredProviders" :key="provider.id">
            <div v-for="cityProvider in city.cityProviders" :key="cityProvider.provider_id">
              <div v-if="provider.id === cityProvider.provider_id" :style="{ 'background-color': provider.color }"
                   class="m-1 flex h-6 w-fit shrink-0 items-center justify-center rounded-md border border-zinc-300 p-1 hover:opacity-75"
                   @click="filterProvider(provider.id)"
              >
                <img class="w-6" :src="'/providers/' + provider.name + '.png'" alt="">
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <p v-else class="mt-8 flex justify-center font-medium text-gray-800">
      Didn't find selected provider. Just empty space.
    </p>
  </div>
</template>

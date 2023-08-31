<script setup>
import { useFilterStore } from '@/Shared/Stores/FilterStore'
import { computed, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { TrashIcon } from '@heroicons/vue/24/outline'
import FavoriteButton from '@/Shared/Components/FavoriteButton.vue'
import InfoPopup from '@/Shared/Components/InfoPopup.vue'
import { __ } from '@/translate'
import { ChevronDownIcon, FlagIcon, TruckIcon, FunnelIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'
import { onClickOutside } from '@vueuse/core'
import { DynamicScroller, DynamicScrollerItem } from 'vue-virtual-scroller'

const filterStore = useFilterStore()

const props = defineProps({
  cities: Array,
  providers: Array,
  countries: Array,
})

const isAuth = computed(() => usePage().props.auth.isAuth)


const filteredCities = computed(() => {
  const selectedCountryId = filterStore.selectedCountry ? filterStore.selectedCountry.id : null
  const selectedProviderName = filterStore.selectedProviderName

  if (selectedCountryId === null && selectedProviderName === null) {
    return props.cities
  } else if (selectedCountryId !== null && selectedProviderName === null) {
    return props.cities.filter(city => city.country.id === selectedCountryId)
  } else if (selectedCountryId === null && selectedProviderName !== null) {
    return props.cities.filter(city =>
      city.cityProviders.some(cityProvider => cityProvider.provider_name === selectedProviderName),
    )
  } else {
    return props.cities.filter(city =>
      city.country.id === selectedCountryId &&
            city.cityProviders.some(cityProvider => cityProvider.provider_name === selectedProviderName),
    )
  }
})

const filteredProviders = computed(() => {
  const selectedCountryId = filterStore.selectedCountry ? filterStore.selectedCountry.id : null

  if (selectedCountryId === null) {
    return props.providers
  } else {
    return props.providers.filter(provider =>
      props.cities.some(city =>
        city.country.id === selectedCountryId &&
                city.cityProviders.some(cityProvider =>
                  cityProvider.provider_name === provider.name,
                ),
      ),
    )
  }
})

const filteredCountries = computed(() => {
  const selectedProviderName = filterStore.selectedProviderName
  const selectedCountryId = filterStore.selectedCountry ? filterStore.selectedCountry.id : null

  const cityMap = new Map()

  for (const city of props.cities) {
    const cityProviders = city.cityProviders
    const hasProvider = cityProviders.some(cityProvider => cityProvider.provider_name === selectedProviderName)
    cityMap.set(city.country.id, (cityMap.get(city.country.id) || false) || hasProvider)
  }

  return props.countries.map(country => ({
    ...country,
    hasProvider: selectedProviderName === null ? true : cityMap.get(country.id) || false,
    isSelected: country.id === selectedCountryId,
  }))
})

function filterCountry(country) {
  filterStore.changeSelectedProvider(null)
  filterStore.changeSelectedCity(null)
  filterStore.changeSelectedCountry(country)

  if (!isIconFilterEnabled.value) {
    toggleCountryList()
  }
}

function filterProvider(providerName) {
  filterStore.changeSelectedCity(null)

  if (filterStore.selectedProviderName === providerName) {
    filterStore.changeSelectedProvider(null)
  } else {
    filterStore.changeSelectedProvider(providerName)
  }

  if (!isIconFilterEnabled.value) {
    toggleProviderList()
  }
}

function clearFilters() {
  filterStore.changeSelectedProvider(null)
  filterStore.changeSelectedCountry(null)
  filterStore.changeSelectedCity(null)
}

function showCity(city) {
  if (filterStore.selectedCity && filterStore.selectedCity.id === city.id) {
    filterStore.changeSelectedCity(null)
  } else {
    filterStore.changeSelectedCity(city)
  }
}

const isCountryListOpened = ref(false)
const countryList = ref(null)
onClickOutside(countryList, () => (isCountryListOpened.value = false))

function toggleCountryList() {
  isCountryListOpened.value = !isCountryListOpened.value
}

const isProviderListOpened = ref(false)
const providerList = ref(null)
onClickOutside(providerList, () => (isProviderListOpened.value = false))


function toggleProviderList() {
  isProviderListOpened.value = !isProviderListOpened.value
}

const isIconFilterEnabled = ref(true)

function changeFilter() {
  isIconFilterEnabled.value = !isIconFilterEnabled.value
}

function clearMap() {
  filterStore.changeSelectedCity(null)
}

function getProviderColor(providerName) {
  const provider = props.providers.find(provider => provider.name === providerName)

  return provider ? provider.color : ''
}

function goToCityPage(city) {
  router.get(`/${city.country.slug}/${city.slug}`)
}

</script>

<template>
  <div class="mx-auto mt-4 flex w-11/12 flex-col sm:mt-12">
    <div class="px-2 lg:px-3">
      <h1 v-if="isIconFilterEnabled" class="mb-1 text-[11px] font-medium text-gray-600">
        {{ __('Countries') }}
      </h1>

      <ul v-if="isIconFilterEnabled" role="list" class="scrollbar flex space-x-2 overflow-x-auto pb-2">
        <li v-for="country in filteredCountries" :key="country.id" class="col-span-1 flex cursor-pointer rounded-md"
            :class="{ 'opacity-25': !country.hasProvider }" @click="filterCountry(country)"
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

      <h1 v-if="isIconFilterEnabled" class="mb-1 mt-4 text-[11px] font-medium text-gray-600">
        {{ __('Providers') }}
      </h1>

      <ul v-if="isIconFilterEnabled" role="list" class="scrollbar flex space-x-2 overflow-x-auto pb-2">
        <li v-for="provider in filteredProviders" :key="provider.name"
            class="col-span-1 flex cursor-pointer rounded-md"
            :class="{ 'opacity-25': filterStore.selectedProviderName !== null && filterStore.selectedProviderName !== provider.name }"
            @click="filterProvider(provider.name)"
        >
          <div
            :style="{ 'background-color': provider.color }"
            class="flex h-10 w-12 shrink-0 items-center justify-center rounded-l-md  px-2 py-3"
          >
            <img loading="lazy" :src="'/providers/' + provider.name.toLowerCase() + '.png'" alt="">
          </div>
          <div
            class="flex flex-1 items-center justify-between truncate rounded-r-md border-y border-r border-gray-100 bg-white"
          >
            <div class="flex-1 truncate px-3 text-sm">
              <span class="text-xs font-medium text-gray-600">{{ provider.name }}</span>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <div class="px-2 lg:px-3">
      <div v-if="!isIconFilterEnabled" ref="countryList" class="relative">
        <div
          class="cursor-pointer rounded border border-gray-200 bg-gray-50 px-2 py-1 hover:bg-gray-100" @click="toggleCountryList"
        >
          <div v-for="country in filteredCountries" :key="country.id">
            <div v-if="country.isSelected" class="flex items-center p-2">
              <i :class="country.iso" class="flat large flag" />
              <span class="ml-2 text-sm">
                {{ country.name }}
              </span>
            </div>
          </div>

          <div v-if="!filterStore.selectedCountry" class="flex items-center px-2 py-3">
            <FlagIcon class="h-4 w-4" />
            <span class="ml-2 text-xs font-medium text-gray-600">
              {{ __('Choose country') }}
            </span>
          </div>

          <button type="button" class="absolute inset-y-0 right-0 flex items-center rounded-r-md pr-2 focus:outline-none">
            <ChevronDownIcon class="h-4 w-4" />
          </button>
        </div>

        <ul v-if="isCountryListOpened" class="scrollbar absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" role="listbox">
          <li v-for="country in filteredCountries" :key="country.id"
              :class="{ 'opacity-25': !country.hasProvider }"
              class="relative flex cursor-default select-none items-center p-2 text-gray-900 hover:cursor-pointer hover:bg-gray-100"
              role="option" tabindex="-1" @click="filterCountry(country)"
          >
            <i :class="country.iso"
               class="flat flag !h-[18px] !w-[27px]"
            />
            <span class="ml-2 block truncate text-sm">{{ country.name }}</span>
          </li>
        </ul>
      </div>

      <div v-if="!isIconFilterEnabled" ref="providerList" class="relative mt-4">
        <div class="cursor-pointer rounded border border-gray-200 bg-gray-50 px-2 py-1 hover:bg-gray-100" @click="toggleProviderList">
          <div v-if="filterStore.selectedProviderName" class="flex items-center p-2">
            <div v-for="provider in filteredProviders" :key="provider.name">
              <div v-if="provider.name === filterStore.selectedProviderName" :style="{ 'background-color': provider.color }"
                   class="flex h-7 w-fit shrink-0 items-center justify-center rounded-md border border-zinc-300 p-1 hover:opacity-75"
                   @click="filterProvider(provider.name)"
              >
                <img loading="lazy" class="w-7" :src="'/providers/' + provider.name.toLowerCase() + '.png'" alt="">
              </div>
            </div>

            <span class="ml-2 text-sm">
              {{ filterStore.selectedProviderName }}
            </span>
          </div>


          <div v-if="!filterStore.selectedProviderName" class="flex items-center px-2 py-3">
            <TruckIcon class="h-4 w-4" />
            <span class="ml-2 text-xs font-medium text-gray-600">
              {{ __('Choose provider') }}
            </span>
          </div>

          <button type="button" class="absolute inset-y-0 right-0 flex items-center rounded-r-md pr-2 focus:outline-none">
            <ChevronDownIcon class="h-4 w-4" />
          </button>
        </div>

        <ul v-if="isProviderListOpened" class="scrollbar absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" role="listbox">
          <li v-for="provider in filteredProviders" :key="provider.name" class="relative flex cursor-default select-none items-center p-2 text-gray-900 hover:cursor-pointer hover:bg-gray-100"
              role="option" tabindex="-1" @click="filterProvider(provider.name)"
          >
            <div :style="{ 'background-color': provider.color }"
                 class="flex h-5 w-fit shrink-0 items-center justify-center rounded border border-zinc-300 p-1 hover:opacity-75"
            >
              <img loading="lazy" class="w-5" :src="'/providers/' + provider.name.toLowerCase() + '.png'" alt="">
            </div>
            <span class="ml-2 block truncate text-sm">{{ provider.name }}</span>
          </li>
        </ul>
      </div>
    </div>


    <div class="mb-8 mt-2 flex w-full flex-wrap justify-between px-2 lg:px-3">
      <button v-if="filteredCities.length" class="mr-1 mt-2 flex h-fit w-fit items-center rounded-lg border border-gray-300 px-4 py-2 text-[10px] font-medium text-gray-600 hover:bg-gray-50"
              @click="changeFilter"
      >
        <FunnelIcon class="mr-1 h-4 w-4" />
        {{ __('Change filters') }}
      </button>

      <div class="flex flex-col">
        <button
          v-if="filterStore.selectedCity !== null"
          class="mt-2 flex w-fit items-center rounded-lg border border-gray-300 px-4 py-2 text-[10px] font-medium text-gray-600 hover:bg-gray-50"
          @click="clearMap"
        >
          <TrashIcon class="mr-1 h-4 w-4" />
          {{ __('Clear map') }}
        </button>
        <button
          v-if="filterStore.selectedCountry !== null || filterStore.selectedProviderName !== null"
          class="mt-2 flex w-fit items-center rounded-lg border border-gray-300 px-4 py-2 text-[10px] font-medium text-gray-600 hover:bg-gray-50"
          @click="clearFilters"
        >
          <TrashIcon class="mr-1 h-4 w-4" />
          {{ __('Clear filters') }}
        </button>
      </div>
    </div>

    <div v-if="filterStore.selectedCity" class="group flex origin-left cursor-pointer flex-col items-start justify-between gap-x-6 border-t px-2 py-6 pb-1 transition-all duration-500 ease-out hover:bg-gray-50 sm:flex-row sm:pb-4 lg:px-3"
         @click="showCity(filterStore.selectedCity)"
    >
      <div class="flex w-full justify-between sm:flex-col sm:justify-start">
        <div class="flex w-max items-center">
          <i :class="filterStore.selectedCity.country.iso" class="flat flag huge shrink-0" />

          <div class="ml-3 flex flex-col justify-start">
            <p class="mr-2 origin-left break-all rounded-full font-bold transition-all duration-500 ease-out group-hover:text-gray-500">
              {{ filterStore.selectedCity.name }}
            </p>
            <p class="break-all text-xs font-semibold text-blumilk-500">
              {{ filterStore.selectedCity.country.name }}
            </p>
          </div>
        </div>

        <div class="mt-0 flex w-fit items-center justify-end sm:mt-1 sm:justify-start">
          <div class="mt-2 flex rounded-full sm:ml-[64px] text-gray-600">
                <FavoriteButton v-if="isAuth" class="flex rounded-full py-0.5 hover:bg-gray-200" :cityid="filterStore.selectedCity.id" />
                <InfoPopup v-else class="flex rounded-full py-0.5 hover:bg-gray-200" />

                <div class="ml-2 sm:ml-1 sm:px-1 flex items-center rounded-full py-0.5 text-blumilk-500 hover:bg-gray-200" @click.stop="goToCityPage(filterStore.selectedCity)">
                    <InformationCircleIcon class="h-8 w-8 sm:h-6 sm:w-6" />
                    <p class="ml-1 hidden text-xs font-medium sm:flex">
                        {{ __('Check details') }}
                    </p>
                </div>
          </div>
        </div>
      </div>

      <div class="mb-2 mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
        <div v-for="cityProvider in filterStore.selectedCity.cityProviders" :key="cityProvider.provider_name">
          <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
               class="m-1 flex h-6 w-fit shrink-0 items-center justify-center rounded-md border border-zinc-300 p-1"
          >
            <img loading="lazy" class="w-6" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
          </div>
        </div>
      </div>
    </div>

    <DynamicScroller
      v-if="filteredCities.length"
      :items="filteredCities"
      :min-item-size="100"
      key-field="id"
      page-mode
    >
      <template #default="{ item, active }">
        <DynamicScrollerItem :size-dependencies="[item.name]"
                             :item="item" :active="active"
                             :class="filterStore.selectedCity && filterStore.selectedCity ? 'opacity-25' : ''"
                             class="group flex origin-left cursor-pointer flex-col items-start justify-between gap-x-6 border-t px-2 py-6 pb-1 transition-all duration-500 ease-out hover:bg-gray-50 sm:flex-row sm:pb-4 lg:px-3"
                             @click="showCity(item)"
        >
          <div class="flex w-full justify-between sm:flex-col sm:justify-start">
            <div class="flex w-max items-center">
              <i :class="item.country.iso" class="flat flag huge shrink-0" />

              <div class="ml-3 flex flex-col justify-start">
                <p class="mr-2 origin-left break-all rounded-full font-bold transition-all duration-500 ease-out group-hover:text-gray-500">
                  {{ item.name }}
                </p>
                <p class="break-all text-xs font-semibold text-blumilk-500">
                  {{ item.country.name }}
                </p>
              </div>
            </div>

            <div class="mt-0 flex w-fit items-center justify-end sm:ml-[64px] sm:mt-1 sm:justify-start">
              <FavoriteButton v-if="isAuth" class="flex rounded-full py-0.5 hover:bg-gray-200" :cityid="item.id" />
              <InfoPopup v-else class="flex rounded-full py-0.5 hover:bg-gray-200" />

              <div class="ml-2 flex rounded-full py-0.5 text-blumilk-500 hover:bg-gray-200" @click.stop="goToCityPage(item)">
                <InformationCircleIcon class="h-8 w-8 sm:h-6 sm:w-6" />
              </div>
            </div>
          </div>

          <div class="mb-2 mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
            <div v-for="cityProvider in item.cityProviders" :key="cityProvider.provider_name">
              <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
                   class="m-1 flex h-6 w-fit shrink-0 items-center justify-center rounded-md border border-zinc-300 p-1"
              >
                <img loading="lazy" class="w-6" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
              </div>
            </div>
          </div>
        </DynamicScrollerItem>
      </template>
    </DynamicScroller>


    <p v-else class="mt-8 flex justify-center font-medium text-gray-800">
      {{ __(`Didn't find any providers.`) }}
    </p>
  </div>
</template>

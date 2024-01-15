<script setup>
import { useFilterStore } from '@/Shared/Stores/FilterStore'
import { computed, onMounted, ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { TrashIcon, XMarkIcon, MapIcon } from '@heroicons/vue/24/outline'
import FavoriteButton from '@/Shared/Components/FavoriteButton.vue'
import InfoPopup from '@/Shared/Components/InfoPopup.vue'
import { __ } from '@/translate'
import { FlagIcon, TruckIcon, FunnelIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'
import { breakpointsTailwind, onClickOutside, useBreakpoints } from '@vueuse/core'
import { DynamicScroller, DynamicScrollerItem } from 'vue-virtual-scroller'
import SelectedCity from '@/Shared/Components/SelectedCity.vue'
import ProviderIcons from '@/Shared/Components/ProviderIcons.vue'
const breakpoints = useBreakpoints(breakpointsTailwind)
const isDesktop = ref(breakpoints.greaterOrEqual('lg'))
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
  filterStore.changeSelectedCity(null)

  if (filterStore.selectedCountry && filterStore.selectedCountry.id === country.id) {
    filterStore.changeSelectedCountry(null)
  } else {
    filterStore.changeSelectedCountry(country)
    filterStore.changeSelectedProvider(null)
  }

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
const isIconFilterEnabled = ref(false)

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
const providerAutocomplete = ref('')
const countryAutocomplete = ref('')

function rememberProviderAutocompleteValue() {
  if (filterStore.selectedProviderName) {
    providerAutocomplete.value = filterStore.selectedProviderName
  } else {
    providerAutocomplete.value = ''
  }
}

function rememberCountryAutocompleteValue() {
  if (filterStore.selectedCountry) {
    countryAutocomplete.value = filterStore.selectedCountry.name
  } else {
    countryAutocomplete.value = ''
  }
}
onMounted(() => {
  rememberProviderAutocompleteValue()
  rememberCountryAutocompleteValue()
  watch(() => filterStore.selectedProviderName, () => {
    rememberProviderAutocompleteValue()
  })
  watch(() => filterStore.selectedCountry, () => {
    rememberCountryAutocompleteValue()
  })
  watch(() => providerAutocomplete.value, () => {
    if (providerAutocomplete.value === '') {
      filterStore.changeSelectedProvider(null)
    }
  })
  watch(() => countryAutocomplete.value, () => {
    if (countryAutocomplete.value === '') {
      filterStore.changeSelectedCountry(null)
    }
  })
})

function clearProviderAutocompleteInput() {
  providerAutocomplete.value = ''
  toggleProviderList()
}

function clearCountryAutocompleteInput() {
  countryAutocomplete.value = ''
  toggleCountryList()
}
const filteredProviderSuggestions = computed(() => {
  return filteredProviders.value.filter(provider =>
    provider.name.toLowerCase().includes(providerAutocomplete.value.toLowerCase()),
  )
})
const filteredCountrySuggestions = computed(() => {
  return filteredCountries.value.filter(country =>
    country.name.toLowerCase().includes(countryAutocomplete.value.toLowerCase()),
  )
})

function selectProvider(provider) {
  providerAutocomplete.value = provider.name
  filterProvider(provider.name)
  toggleProviderList()
}

function selectCountry(country) {
  providerAutocomplete.value = ''
  countryAutocomplete.value = country.name
  filterCountry(country)
  toggleCountryList()
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
    <div v-if="!isIconFilterEnabled" class="px-2 lg:px-3">
      <div ref="countryList" class="relative">
        <div class="cursor-pointer rounded" @click="toggleCountryList">
          <div class="flex w-full rounded-xl shadow-sm">
            <div class="relative flex grow items-stretch focus-within:z-10">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i v-if="filterStore.selectedCountry" class="flat flag !h-[18px] !w-[27px]" :class="filterStore.selectedCountry.iso" />
                <FlagIcon v-else class="ml-1 h-6 w-6 text-gray-800" />
              </div>
              <input v-model.trim="countryAutocomplete" type="text"
                     :class="countryAutocomplete.length ? 'rounded-l-lg' : 'rounded-lg'"
                     class="block w-full border-0 py-4 pl-12 font-medium text-gray-800 ring-1 ring-inset ring-gray-300 placeholder:text-sm placeholder:font-normal placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blumilk-300 sm:py-3 sm:text-sm sm:leading-6"
                     :placeholder="__('Search country')"
              >
            </div>
            <button v-if="countryAutocomplete.length" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-blumilk-25" @click="clearCountryAutocompleteInput">
              <XMarkIcon class="h-5 w-5" />
            </button>
          </div>
          <ul v-if="isCountryListOpened" class="scrollbar absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" role="listbox">
            <li
              v-for="country in filteredCountrySuggestions"
              :key="country.id"
              :class="{ 'opacity-25': !country.hasProvider }"
              class="relative flex cursor-default select-none items-center p-2 text-gray-900 hover:cursor-pointer hover:bg-gray-100"
              role="option"
              tabindex="-1"
              @click="selectCountry(country)"
            >
              <i :class="country.iso" class="flat flag !h-[18px] !w-[27px]" />
              <span class="ml-2 block truncate text-sm">{{ country.name }}</span>
            </li>
            <li
              v-if="!filteredCountrySuggestions.length"
              class="relative flex cursor-default select-none items-center p-2 text-gray-900"
            >
              {{ __(`Didn't find anything. Just empty space.`) }}
            </li>
          </ul>
        </div>
      </div>
      <div v-if="!isIconFilterEnabled" ref="providerList" class="relative mt-4">
        <div class="cursor-pointer rounded" @click="toggleProviderList">
          <div class="flex w-full rounded-xl shadow-sm">
            <div class="relative flex grow items-stretch focus-within:z-10">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <div v-if="filterStore.selectedProviderName" :style="{ 'background-color': getProviderColor(filterStore.selectedProviderName) }"
                     class="flex h-5 w-fit shrink-0 items-center justify-center rounded px-1"
                >
                  <img loading="lazy" class="w-5" :src="'/providers/' + filterStore.selectedProviderName.toLowerCase() + '.png'" alt="">
                </div>
                <TruckIcon v-else class="ml-1 h-6 w-6 text-gray-800" />
              </div>
              <input v-model.trim="providerAutocomplete" type="text"
                     :class="providerAutocomplete.length ? 'rounded-l-lg' : 'rounded-lg'"
                     class="block w-full border-0 py-4 pl-12 font-medium text-gray-800 ring-1 ring-inset ring-gray-300 placeholder:text-sm placeholder:font-normal placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blumilk-300 sm:py-3 sm:text-sm sm:leading-6"
                     :placeholder="__('Search provider')"
              >
            </div>
            <button v-if="providerAutocomplete.length" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-blumilk-25" @click="clearProviderAutocompleteInput">
              <XMarkIcon class="h-5 w-5" />
            </button>
          </div>
          <ul v-if="isProviderListOpened" class="scrollbar absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" role="listbox">
            <li
              v-for="provider in filteredProviderSuggestions"
              :key="provider.name"
              class="relative flex cursor-default select-none items-center p-2 text-gray-900 hover:cursor-pointer hover:bg-gray-100"
              role="option"
              tabindex="-1"
              @click="selectProvider(provider)"
            >
              <div :style="{ 'background-color': provider.color }" class="flex h-5 w-fit shrink-0 items-center justify-center rounded border border-zinc-300 p-1 hover:opacity-75">
                <img loading="lazy" class="w-5" :src="'/providers/' + provider.name.toLowerCase() + '.png'" alt="">
              </div>
              <span class="ml-2 block truncate text-sm">{{ provider.name }}</span>
            </li>
            <li
              v-if="!filteredProviderSuggestions.length"
              class="relative flex cursor-default select-none items-center p-2 text-gray-900"
            >
              {{ __(`Didn't find anything. Just empty space.`) }}
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div :class="isDesktop ? 'justify-between' : 'justify-end'"
         class="mb-4 mt-2 flex w-full flex-wrap px-2 lg:px-3"
    >
      <button v-if="isDesktop && filteredCities.length" class="mr-1 mt-2 flex h-fit w-fit items-center rounded-lg border border-gray-300 px-4 py-2 text-[10px] font-medium text-gray-600 hover:bg-gray-50"
              @click="changeFilter"
      >
        <FunnelIcon class="mr-1 h-4 w-4" />
        {{ __('Change filters') }}
      </button>
      <div :class="[isDesktop ? 'flex-col' : 'w-full', filterStore.selectedCity ? 'justify-between' : 'justify-end']"
           class="flex"
      >
        <button
          v-if="filterStore.selectedCity !== null"
          class="mt-2 flex w-fit items-center rounded-lg border border-gray-300 px-4 py-2 text-[10px] font-medium text-gray-600 hover:bg-gray-50"
          @click="clearMap"
        >
          <MapIcon class="mr-1 h-4 w-4" />
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
    <div class="mb-4 mt-2 w-full px-2 lg:px-3">
      <p class="text-slate-500 ">
        {{ __('Results found') }}: {{ filteredCities.length }}
      </p>
    </div>
    <SelectedCity :providers="props.providers" />
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
                             :class="filterStore.selectedCity ? 'opacity-25 saturate-50' : ''"
                             class="group flex origin-left cursor-pointer flex-col justify-between gap-x-6 border-b transition-all duration-500 ease-out hover:brightness-105 hover:drop-shadow-xl sm:flex-row md:items-center"
                             @click="showCity(item)"
        >
          <div class="flex w-full justify-between px-2 py-6 pb-1 sm:flex-col sm:justify-start sm:pb-4 lg:px-3">
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
              <div class="hover:drop-shadow">
                <FavoriteButton v-if="isAuth" class="flex rounded-full py-0.5 hover:drop-shadow" :cityid="item.id" />
                <InfoPopup v-else class="flex rounded-full py-0.5 hover:drop-shadow" />
              </div>
              <div class="ml-2 flex rounded-full py-0.5 text-blumilk-500 hover:drop-shadow" @click.stop="goToCityPage(item)">
                <InformationCircleIcon class="h-8 w-8 hover:drop-shadow sm:h-6 sm:w-6" />
              </div>
            </div>
          </div>
          <ProviderIcons :item="item" :providers="props.providers" />
        </DynamicScrollerItem>
      </template>
    </DynamicScroller>
    <p v-else class="mt-8 flex justify-center font-medium text-gray-800">
      {{ __(`Didn't find any providers.`) }}
    </p>
  </div>
</template>

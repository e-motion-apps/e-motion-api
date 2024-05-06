<script setup>

import { InformationCircleIcon } from '@heroicons/vue/24/outline'
import { __ } from '@/translate'
import { useFilterStore } from '../Stores/FilterStore'
import { router } from '@inertiajs/vue3'
import ProviderIcons from './ProviderIcons.vue'

const filterStore = useFilterStore()

const props = defineProps({
  providers: Object,
})

function showCity(city) {
  if (filterStore.selectedCity && filterStore.selectedCity.id === city.id) {
    filterStore.changeSelectedCity(null)
  } else {
    filterStore.changeSelectedCity(city)
  }
}

function goToCityPage(city) {
  router.get(`/${city.country.slug}/${city.slug}`)
}

</script>

<template>
  <div v-if="filterStore.selectedCity" class="group mb-4 flex origin-left cursor-pointer flex-col justify-between gap-x-6 rounded-lg border border-gray-100 shadow-md transition-all duration-500 ease-out hover:shadow-lg hover:drop-shadow-xl sm:flex-row md:items-center"
       @click="showCity(filterStore.selectedCity)"
  >
    <div class="flex w-full justify-between px-2 py-6 pb-1 sm:flex-col sm:justify-start sm:pb-4 lg:px-3">
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
        <div class="mt-2 flex rounded-full text-gray-600 sm:ml-[64px]">
          <div class="flex items-center rounded-full py-0.5 text-blumilk-500 hover:drop-shadow" @click.stop="goToCityPage(filterStore.selectedCity)">
            <InformationCircleIcon class="size-8 hover:drop-shadow sm:size-6" />
            <p class="ml-1 hidden text-xs font-medium sm:flex">
              {{ __('Check details') }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <ProviderIcons :item="filterStore.selectedCity" :providers="props.providers" />
  </div>
</template>

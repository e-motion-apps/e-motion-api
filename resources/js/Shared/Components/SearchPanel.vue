<script setup>

import { useMapMarkerStore } from '../Stores/MapMarkerStore'

const mapMarkerStore = useMapMarkerStore()

const props = defineProps({
  cities: Object,
  providers: Object,
})

function showCity(city) {
  mapMarkerStore.changeMarker(city)
}

</script>

<template>
  <div class="mx-auto mt-12 flex w-11/12 lg:w-5/6">
    <ul role="list" class="divide-y divide-gray-300 ">
      <li v-for="city in props.cities" :key="city.id" class="group flex cursor-pointer flex-col items-start justify-between gap-x-6 py-5 md:flex-row" @click="showCity(city)">
        <div class="flex w-1/2 items-center">
          <i :class="city.country.iso" class="flat flag huge shrink-0" />
          <div class="ml-4 flex flex-col justify-start">
            <p class="mr-2 break-all font-bold text-gray-900 group-hover:text-gray-500">
              {{ city.name }}
            </p>
            <p class="break-all text-xs font-semibold text-blumilk-500">
              {{ city.country.name }}
            </p>
          </div>
        </div>

        <div class="mt-4 flex w-full flex-wrap items-center sm:flex-row-reverse md:mt-0 md:w-1/2">
          <div v-for="provider in props.providers" :key="provider.id">
            <div v-for="cityProvider in city.providers" :key="cityProvider.provider_list_id">
              <div
                v-if="provider.id === cityProvider.provider_list_id"
                :style="{'background-color': provider.id === cityProvider.provider_list_id ? provider.color : ''}" class="m-1 flex h-8 w-fit shrink-0 items-center justify-center rounded-md border border-zinc-300 bg-zinc-300 p-1 "
              >
                <img class="w-8" :src="'/providers/' + provider.name + '.png'" alt="">
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

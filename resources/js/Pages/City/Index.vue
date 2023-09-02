<script setup>
import Nav from '@/Shared/Layout/Nav.vue'
import Map from '@/Shared/Layout/Map.vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'
import { computed, onUnmounted, ref } from 'vue'
import { MapIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { useFilterStore } from '@/Shared/Stores/FilterStore'
import FavoriteButton from '@/Shared/Components/FavoriteButton.vue'
import ProviderIcons from '@/Shared/Components/ProviderIcons.vue'

const props = defineProps({
  city: Object,
  providers: Array,
})

const breakpoints = useBreakpoints(breakpointsTailwind)
const isMobile = ref(breakpoints.smaller('lg'))
const isDesktop = ref(breakpoints.greaterOrEqual('lg'))

const shouldShowMap = ref(false)

function switchMap() {
  shouldShowMap.value = !shouldShowMap.value
}

const buttonIcon = computed(() => {
  return shouldShowMap.value ? XMarkIcon : MapIcon
})

const filterStore = useFilterStore()

onUnmounted(() => {
  filterStore.changeSelectedCity(null)
})
</script>

<template>
  <div class="flex h-screen flex-col">
    <Nav ref="nav" class="z-30" />

    <div class="mt-16 flex grow flex-col lg:flex-row">
      <div v-if="isDesktop || !shouldShowMap" class="grow lg:w-1/2">
        <div class="mx-auto mt-4 flex w-11/12 flex-col sm:mt-12">
          <div class="flex items-end justify-between md:items-center">
            <h1 class="flex text-4xl font-bold md:text-5xl">
              {{ city.name }}
            </h1>
            <div class="hover:drop-shadow">
              <FavoriteButton :cityid="city.id" :grow-up="true" class="ml-3 flex hover:drop-shadow" />
            </div>
          </div>

          <div class="mt-3 flex items-center">
            <i class="flat flag large ml-1" :class="city.country.iso" />
            <h2 class="ml-2 text-xl font-medium text-blumilk-500">
              {{ city.country.name }}
            </h2>
          </div>
          <h2 class="ml-1 mt-1 text-sm text-gray-400 ">
            {{ city.latitude }}, {{ city.longitude }}
          </h2>
          <ProviderIcons class="pt-4" :item="city" :providers="props.providers" />
        </div>
      </div>

      <div v-if="isDesktop || shouldShowMap" class="h-full lg:w-1/2">
        <Map :cities="[props.city]" :is-city-page="true" class="z-10" />
      </div>

      <div v-if="isMobile" class="flex justify-center">
        <button class="hover:blumilk-600 fixed bottom-5 z-20 flex items-center justify-center rounded-full bg-blumilk-500 px-2 py-1.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                @click="switchMap"
        >
          <component :is="buttonIcon" class="h-6 w-6" />
        </button>
      </div>
    </div>
  </div>
</template>


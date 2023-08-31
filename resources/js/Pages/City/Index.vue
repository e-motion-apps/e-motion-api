<script setup>
import Nav from '@/Shared/Layout/Nav.vue'
import Map from '@/Shared/Layout/Map.vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'
import { computed, ref } from 'vue'
import { MapIcon, XMarkIcon } from '@heroicons/vue/24/outline'

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

function getProviderColor(providerName) {
  const provider = props.providers.find(provider => provider.name === providerName)

  return provider ? provider.color : ''
}

</script>

<template>
  <div class="flex h-screen flex-col">
    <Nav ref="nav" class="z-30" />

    <div class="mt-16 flex grow flex-col lg:flex-row">
      <div v-if="isDesktop || !shouldShowMap" class="grow lg:w-1/2">
        <div class="mx-auto mt-4 flex w-11/12 flex-col sm:mt-12">
          <h1 class="text-5xl font-bold">
            {{ city.name }}
          </h1>
          <div class="mt-3 flex items-center">
            <i class="flat flag large" :class="city.country.iso" />
            <h2 class="ml-2 text-xl font-medium text-blumilk-500">
              {{ city.country.name }}
            </h2>
          </div>


          <h2 class="mt-1 text-sm text-gray-400 ">
            {{ city.latitude }}, {{ city.longitude }}
          </h2>
          <div class="flex w-fit flex-row-reverse flex-wrap items-center justify-end pt-8 sm:mt-0 sm:justify-start">
            <div v-for="cityProvider in city.cityProviders" :key="cityProvider.provider_name">
              <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
                   class="m-1 flex h-9 w-fit shrink-0 items-center justify-center rounded-md border border-zinc-300 p-1"
              >
                <img loading="lazy" class="w-12" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
              </div>
            </div>
          </div>
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


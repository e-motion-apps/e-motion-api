<script setup>
import Nav from '@/Shared/Layout/Nav.vue'
import Map from '@/Shared/Layout/Map.vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'
import { computed, onUnmounted, ref } from 'vue'
import {
  InformationCircleIcon,
  MapIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import { DynamicScroller, DynamicScrollerItem } from 'vue-virtual-scroller'
import FavoriteButton from '@/Shared/Components/FavoriteButton.vue'
import InfoPopup from '@/Shared/Components/InfoPopup.vue'
import { router, usePage } from '@inertiajs/vue3'
import { useFilterStore } from '@/Shared/Stores/FilterStore'
import { __ } from '@/translate'
import SelectedCity from '../../Shared/Components/SelectedCity.vue'
import ProviderIcons from '../../Shared/Components/ProviderIcons.vue'

const page = usePage()
const isAuth = computed(() => page.props.auth.isAuth)

const props = defineProps({
  cities: Object,
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

function goToCityPage(city) {
  router.get(`/${city.country.slug}/${city.slug}`)
}

const filterStore = useFilterStore()

function showCity(city) {
  if (filterStore.selectedCity && filterStore.selectedCity.id === city.id) {
    filterStore.changeSelectedCity(null)
  } else {
    filterStore.changeSelectedCity(city)
  }
}

const buttonAnimation = computed(() => {
  return filterStore.selectedCity && buttonIcon.value === MapIcon ? 'animate-bounce' : ''
})

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
          <h1 class="mb-2 px-2 text-3xl font-bold md:mb-10 md:text-4xl">
            {{ __('Favorite cities') }}
          </h1>

          <SelectedCity :providers="props.providers" />

          <DynamicScroller
            v-if="props.cities.length"
            :items="props.cities"
            :min-item-size="100"
            key-field="id"
            page-mode
          >
            <template #default="{ item, active }">
              <DynamicScrollerItem :size-dependencies="[item.name]"
                                   :item="item" :active="active"
                                   :class="filterStore.selectedCity ? 'opacity-25' : ''"
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
                      <FavoriteButton v-if="isAuth" :is-favorite-cities-page="true" class="flex rounded-full py-0.5 hover:drop-shadow" :cityid="item.id" />
                      <InfoPopup v-else class="flex rounded-full py-0.5 hover:bg-gray-200" />
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

          <p v-else class="mt-3 flex px-2 text-lg font-medium text-gray-500">
            {{ __(`Didn't find anything. Just empty space.`) }}
          </p>
        </div>
      </div>

      <div v-if="isDesktop || shouldShowMap" class="h-full lg:w-1/2">
        <Map :key="props.cities" :cities="props.cities" class="z-10" />
      </div>

      <div v-if="isMobile" class="flex justify-center">
        <button :class="buttonAnimation" class="hover:blumilk-600 fixed bottom-5 z-20 flex items-center justify-center rounded-full bg-blumilk-500 px-2 py-1.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" @click="switchMap">
          <component :is="buttonIcon" class="h-6 w-6" />
        </button>
      </div>
    </div>
  </div>
</template>


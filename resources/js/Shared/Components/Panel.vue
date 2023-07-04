<script setup>
import Map from '@/Shared/Components/Map.vue'
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Info from '@/Shared/Components/Info.vue'
import SearchPanel from '@/Shared/Components/SearchPanel.vue'
import Nav from '@/Shared/Layout/Nav.vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'
import { XMarkIcon, MapIcon } from '@heroicons/vue/24/outline'

const breakpoints = useBreakpoints(breakpointsTailwind)
const showInfo = ref(true)
const isMobile = ref(breakpoints.smaller('lg'))
const showMapMobile = ref(false)

function switchPanel() {
  showInfo.value = !showInfo.value
}

function switchMap() {
  showMapMobile.value = !showMapMobile.value
}

onMounted(() => {
  window.addEventListener('resize', updateIsMobile)
})
onBeforeUnmount(() => {
  window.removeEventListener('resize', updateIsMobile)
})

function updateIsMobile() {
  if (!isMobile.value) {
    showMapMobile.value = false
  }
}

watch(() => isMobile, updateIsMobile)
</script>

<template>
  <div class="flex h-screen flex-col">
    <Nav class="z-30" />

    <div class="flex grow flex-col lg:flex-row">
      <div v-if="!showMapMobile" class="grow lg:w-1/2">
        <Info v-show="showInfo" @try-it-out="switchPanel" />
        <SearchPanel v-show="!showInfo" />
      </div>

      <div v-if="!(isMobile && !showMapMobile)" class="relative lg:w-1/2">
        <Map class="z-10" />
      </div>

      <div v-if="!showInfo && isMobile" class="flex justify-center">
        <button class="fixed bottom-5 z-20 flex items-center justify-center rounded-full bg-blumilk-500 px-2 py-1.5 text-sm font-semibold text-white shadow-sm hover:brightness-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" @click="switchMap">
          <XMarkIcon v-if="showMapMobile" class="h-6 w-6" />
          <MapIcon v-else class="h-6 w-6" />
        </button>
      </div>
    </div>
  </div>
</template>

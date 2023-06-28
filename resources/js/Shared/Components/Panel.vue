<script setup>
import Map from './Map.vue'
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Info from './Info.vue'
import SearchPanel from './SearchPanel.vue'
import Nav from './../Layout/Nav.vue'
import Footer from '../Layout/Footer.vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'
import { XMarkIcon, MapIcon } from '@heroicons/vue/24/outline'

const breakpoints = useBreakpoints(breakpointsTailwind)
const showInfo = ref(true)
const isMobile = ref(breakpoints.smallerOrEqual('lg'))
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
  <div class="mx-auto h-screen flex-col bg-white">
    <Nav class="z-30 max-h-24" />
    <div class="relative flex  min-h-[calc(100%-116px)]  flex-col overflow-auto lg:flex-row">
      <div class="lg:w-1/2" :class="{'hidden': showMapMobile}">
        <Info v-show="showInfo" @try-it-out="switchPanel" />
        <SearchPanel v-show="!showInfo" />
      </div>

      <div v-if="!(isMobile && !showMapMobile)" class=" lg:w-1/2">
        <Map class="z-10" />
      </div>

      <div class="flex justify-center">
        <button v-if="!showInfo && isMobile" class="fixed bottom-5 z-20 flex items-center justify-center rounded-full bg-blumilk-500 px-2 py-1.5 text-sm font-semibold text-white shadow-sm hover:brightness-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" @click="switchMap">
          <XMarkIcon v-if="showMapMobile" class="h-6 w-6" />
          <MapIcon v-else class="h-6 w-6" />
        </button>
      </div>
    </div>
    <div class="max-h-5 w-full flex-col">
      <Footer />
    </div>
  </div>
</template>

<script setup>
import Map from './Map.vue'
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Info from './Info.vue'
import SearchPanel from './SearchPanel.vue'
import Nav from '../Components/Nav.vue'

const showInfo = ref(true)
const isMobile = ref(window.innerWidth <= 1024)
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
  isMobile.value = window.innerWidth <= 1024

  if (!isMobile.value) {
    showMapMobile.value = false
  }
}

watch(() => window.innerWidth, updateIsMobile)
</script>

<template>
  <div class="mx-auto h-screen bg-white">
    <Nav class="z-30" />
    <div class="relative flex h-[calc(100%-98px)] flex-col lg:flex-row">
      <div class="min-h-full lg:w-1/2" :class="{'hidden': showMapMobile}">
        <Info v-if="showInfo" @try-it-out="switchPanel" />
        <SearchPanel v-else />
      </div>

      <div v-if="!(isMobile && !showMapMobile)" class="min-h-full lg:w-1/2">
        <Map class="z-10" />
      </div>

      <div class="flex justify-center">
        <button v-if="!showInfo && isMobile" class="fixed bottom-5 z-20 flex items-center justify-center rounded-full bg-[#527ABA] px-2 py-1.5 text-sm font-semibold text-white shadow-sm hover:brightness-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" @click="switchMap">
          <svg v-if="showMapMobile" class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import Map from './Map.vue'
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Info from './Info.vue'
import SearchPanel from './SearchPanel.vue'
import Nav from '../Components/Nav.vue'

const showInfo = ref(true)
const isMobile = ref(window.innerWidth <= 1000)
const showMapMobile = ref(false)

function switchPanel() {
  showInfo.value = !showInfo.value
}

function updateIsMobile() {
  isMobile.value = window.innerWidth <= 1000
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
</script>

<template>
  <div class="mx-auto h-screen bg-white">
    <Nav />
    <div class="relative flex h-[calc(100%-6rem)] flex-col lg:flex-row">
      <div class="min-h-full lg:w-1/2" :class="{'hidden': showMapMobile}">
        <Info v-if="showInfo" @try-it-out="switchPanel" />
        <SearchPanel v-else />
      </div>


      <div v-if="!(isMobile && !showMapMobile)" class="min-h-full lg:w-1/2">
        <Map />
      </div>




      <button v-if="!showInfo && isMobile" class=" fixed bottom-0 left-0 z-10 flex h-16 w-16 items-center justify-center bg-gray-500 text-white" @click="switchMap">
        {{ showMapMobile ? 'Hide Map' : 'Show Map' }}
      </button>
    </div>
  </div>
</template>

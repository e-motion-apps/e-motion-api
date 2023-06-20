<script setup>
import Map from './Map.vue'
import { ref, computed } from 'vue'
import Info from './Info.vue'
import SearchPanel from './SearchPanel.vue'
import Nav from '../Components/Nav.vue'

const showInfo = ref(true)

function switchPanel() {
  showInfo.value = !showInfo.value
}

const isMapShown = ref(true)

function showMap() {
  isMapShown.value = !isMapShown.value
}
const isMobile = computed(() => {
  const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth

  return screenWidth <= 900
})


</script>

<template>
  <div class="mx-auto  bg-white">
    <Nav />
    <div class="h-screen lg:flex">
      <div class="lg:w-auto lg:flex-1">
        <template v-if="showInfo">
          <Info @try-it-out="switchPanel" />
        </template>
        <template v-else>
          <SearchPanel :class="{ 'hidden': isMapShown && isMobile}" />
          <button class="p-10 lg:hidden" @click="showMap">
            Show Map
          </button>
        </template>
      </div>
      <div class="lg:w-1/2">
        <Map :class="{ hidden: isMobile && !isMapShown }" class="h-full w-full" />
      </div>
    </div>
  </div>
</template>

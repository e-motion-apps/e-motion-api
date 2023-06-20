<script setup>
import Map from './Map.vue'
import { ref } from 'vue'
import Info from './Info.vue'
import SearchPanel from './SearchPanel.vue'

const showInfo = ref(true)

function showSearchPanel() {
  showInfo.value = !showInfo.value
}

function showInfoPanel() {
  showInfo.value = true
}
</script>



<template>
  <div class="mx-auto grid min-h-full bg-white lg:grid-cols-2">
    <template v-if="showInfo">
      <Info @try-it-out="showSearchPanel" />
    </template>
    <template v-else>
      <SearchPanel @go-back="showInfoPanel" />
    </template>
    <Map class="hidden lg:block lg:aspect-square lg:h-full lg:w-full" />
  </div>
</template>





  

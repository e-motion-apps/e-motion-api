<script setup>
import Map from './Map.vue'
import { ref } from 'vue'
import Info from './Info.vue'
import SearchPanel from './SearchPanel.vue'
import Nav from '../Components/Nav.vue'

const showInfo = ref(true)

function switchPanel() {
  showInfo.value = !showInfo.value
}



</script>

<template>
  <div class="mx-auto  h-screen bg-white">
    <Nav />
    <div class=" flex h-[calc(100%-6rem)] flex-col lg:flex-row">
      <div class="min-h-full lg:basis-1/2 ">
        <template v-if="showInfo">
          <Info @try-it-out="switchPanel" />
        </template>
        <template v-else>
          <SearchPanel />
        </template>
      </div>
      <div class=" min-h-full lg:basis-1/2">
        <Map class="min-h-full" />
      </div>
    </div>
  </div>
</template>

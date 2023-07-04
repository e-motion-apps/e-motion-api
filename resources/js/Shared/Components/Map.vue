<script setup>
import { ref, onMounted, nextTick } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const mapContainer = ref(null)
const map = ref(null)

onMounted(async () => {
  await nextTick()
  buildMap()
})

function buildMap() {

  map.value = L.map(mapContainer.value).setView([51.107883, 17.038538], 10)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18,
    center: [52.0693267, 19.4781225],
  }).addTo(map.value)

}
</script>

<template>
  <div id="mapContainer" ref="mapContainer">
  </div>
</template>

<style>
#mapContainer {
  position: fixed;
  width: 100%;
  height: 100%;
}
</style>

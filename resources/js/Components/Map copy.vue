<script setup>
import { onMounted, ref, nextTick } from 'vue'
import mapboxgl from 'mapbox-gl'

const mapContainer = ref(null)
const map = ref(null)

onMounted(async () => {
  await nextTick()
  buildMap()
})

function buildMap() {
  mapboxgl.accessToken = import.meta.env.VITE_APP_MAPBOX_TOKEN
  map.value = new mapboxgl.Map({
    container: mapContainer.value,
    style: import.meta.env.VITE_APP_MAPBOX_STYLE_URL,
    zoom: 10,
    center: [51.107883, 17.038538],
    
  })
  map.value.addControl(new mapboxgl.NavigationControl())
}
</script>

<template>
  <div id="mapCont" ref="mapContainer" />
</template>


<style>
#mapCont{
  width:100%;
  height: 100%;

}
</style>

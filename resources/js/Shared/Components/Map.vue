<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { useMapMarkerStore } from '../Stores/MapMarkerStore'

const mapMarkerStore = useMapMarkerStore()

watch(() => mapMarkerStore.marker, () => {
  map.value.setView([mapMarkerStore.marker.latitude, mapMarkerStore.marker.longitude], 18)
  L.latLng(mapMarkerStore.marker.latitude, mapMarkerStore.marker.longitude)
})

const mapContainer = ref(null)
const map = ref(null)

defineOptions({
  inheritAttrs: false,
})

const props = defineProps({
  cities: Object,
})

const userLocation = ref(null)

onMounted(async () => {
  await nextTick()
  buildMap()
  getUserLocation()
  fillMap()
})

function buildMap() {
  map.value = L.map(mapContainer.value).setView([50, 30], 4)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18,
    center: [0, 0],
  }).addTo(map.value)

  map.value.invalidateSize()
}

function getUserLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      position => {
        const { latitude, longitude } = position.coords
        userLocation.value = L.latLng(latitude, longitude)
        map.value.setView(userLocation.value, 18)
      },
      () => {
        console.error('Error getting user location.')
      })
  } else {
    console.error('Geolocation is not supported by this browser.')
  }
}

function fillMap() {
  const markers = L.featureGroup()

  props.cities.forEach(city => {
    const marker = L.circleMarker([city.latitude, city.longitude], {
      radius: 5,
      weight: 1,
      color: '#6F90C6',
      fillColor: '#527ABA',
      fillOpacity: 1,
    })
    marker
      .addTo(markers)
      .on('click', () => window)
      .bindTooltip(`${city.name} - ${city.country.name} <i class="${city.country.iso} flag flat"/>`)
  })

  markers.addTo(map.value)
}
</script>

<template>
  <div id="mapContainer" ref="mapContainer" />
</template>

<style>
#mapContainer {
    position: fixed;
    width: 100%;
    height: 100%;
}
</style>

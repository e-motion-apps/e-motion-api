<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { useFilterStore } from '../Stores/FilterStore'

const props = defineProps({
  cities: Array,
})

const filterStore = useFilterStore()

const mapContainer = ref(null)
const map = ref(null)
const markers = ref(null)

watch(() => filterStore.selectedCity, () => {
  if (filterStore.selectedCity) {
    map.value.setView(
      [filterStore.selectedCity.latitude, filterStore.selectedCity.longitude],
      12,
    )
  }
})

watch(() => props.cities, () => {
  fillMap()
})

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

const userLocation = ref(null)

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
      },
    )
  } else {
    console.error('Geolocation is not supported by this browser.')
  }
}

function fillMap() {
  markers.value = L.featureGroup()

  const selectedCountryId = filterStore.selectedCountryId
  const selectedProviderName = filterStore.selectedProviderName

  const filteredCities = props.cities.filter(city => {
    if (selectedCountryId !== null && selectedProviderName === null) {
      return city.country.id === selectedCountryId
    } else if (selectedCountryId === null && selectedProviderName !== null) {
      return city.cityProviders.some(cityProvider => cityProvider.provider_name === selectedProviderName)
    } else if (selectedCountryId !== null && selectedProviderName !== null) {
      return (
        city.country.id === selectedCountryId &&
                city.cityProviders.some(cityProvider => cityProvider.provider_name === selectedProviderName)
      )
    } else {
      return true
    }
  })

  filteredCities.forEach(city => {
    const marker = L.circleMarker([city.latitude, city.longitude], {
      radius: 5,
      weight: 1,
      color: '#6F90C6',
      fillColor: '#527ABA',
      fillOpacity: 1,
    })
    marker
      .addTo(markers.value)
      .on('click', () => window)
      .bindTooltip(`<i class="${city.country.iso} flat flag shadow"></i> ${city.name}, ${city.country.name}`)
  })

  markers.value.addTo(map.value)
}
</script>

<template>
  <div id="mapContainer" ref="mapContainer" />
</template>

<style scoped>
#mapContainer {
    position: fixed;
    width: 100%;
    height: 100%;
}
</style>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { useFilterStore } from '../Stores/FilterStore'

defineOptions({
  inheritAttrs: false,
})

const filterStore = useFilterStore()

const mapContainer = ref(null)
const map = ref(null)
const markers = ref(null)

const props = defineProps({
  cities: Array,
  isCityPage: Boolean,
})

onMounted(async () => {
  await nextTick()
  buildMap()
  fillMap()
  centerToSelectedCountry()
  centerToSelectedCity()
  centerToSingleCity()

  watch(() => filterStore.selectedCountry, () => {
    centerToSelectedCountry()
    clearMap()
    fillMap()
  })

  watch(() => filterStore.selectedCity, () => {
    centerToSelectedCity()
  })
})

function centerToSelectedCity() {
  centerToLocation(filterStore.selectedCity, 12)
}

function centerToSelectedCountry() {
  if (filterStore.selectedCountry) {
    switch (filterStore.selectedCountry.name) {
    case 'Australia':
    case 'Canada':
    case 'China':
    case 'Russia':
      centerToLocation(filterStore.selectedCountry, 2)
      break
    default:
      centerToLocation(filterStore.selectedCountry, 6)
    }
  }
}

function centerToLocation(location, zoom) {
  if (location) {
    map.value.setView([location.latitude, location.longitude], zoom)
  } else {
    if (filterStore.selectedCountry) {
      centerToSelectedCountry()
    } else {
      map.value.setView([0, 0], 2)
    }
  }
}

function centerToSingleCity() {
  if (props.isCityPage && props.cities.length) {
    centerToLocation(props.cities[0], 12)
  }
}

function clearMap() {
  markers.value.clearLayers()
}

function buildMap() {
  map.value = L.map(mapContainer.value)
  map.value.setView([0, 0], 2)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map.value)
}

function fillMap() {
  markers.value = L.featureGroup()

  const { selectedCountry, selectedProviderName } = filterStore
  const filteredCities = filterCities(props.cities, selectedCountry, selectedProviderName)

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
      .on('click', () => {
        const selectedCity = filterStore.selectedCity

        if (selectedCity && selectedCity.id === city.id) {
          filterStore.changeSelectedCity(null)
        } else {
          filterStore.changeSelectedCity(city)
        }
      })
      .bindTooltip(`<i class="${city.country.iso} flat flag shadow"></i> ${city.name}, ${city.country.name}`)
  })

  markers.value.addTo(map.value)
}

function filterCities(cities, selectedCountry, selectedProviderName) {
  return cities.filter(city => {
    const matchCountry = !selectedCountry || city.country.id === selectedCountry.id
    const matchProvider = !selectedProviderName || city.cityProviders.some(cityProvider => cityProvider.provider_name === selectedProviderName)

    return matchCountry && matchProvider
  })
}
</script>

<template>
  <div id="mapContainer" ref="mapContainer" class="fixed h-full w-full lg:w-1/2" />
</template>

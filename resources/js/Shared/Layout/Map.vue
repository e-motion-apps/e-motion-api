<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { useFilterStore } from '../Stores/FilterStore'

defineOptions({
  inheritAttrs: false,
})

const props = defineProps({
  cities: Array,
  isCityPage: Boolean
})

const filterStore = useFilterStore()

const mapContainer = ref(null)
const map = ref(null)
const markers = ref(null)


function centerToSelectedCity() {
  if (filterStore.selectedCity) {
    map.value.setView(
      [filterStore.selectedCity.latitude, filterStore.selectedCity.longitude],
      12,
    )
  } else {
      refreshMapCenter()
  }
}

function centerToSelectedCountry() {
  if (filterStore.selectedCountry) {
    map.value.setView(
      [filterStore.selectedCountry.latitude, filterStore.selectedCountry.longitude],
      6,
    )
  } else {
    refreshMapCenter()
  }
}


function refreshMapCenter() {
  if (filterStore.selectedCountry) {
    centerToSelectedCountry()
  } else {
    map.value.setView([0, 0], 2)
  }
}

function centerToSingleCity() {
    if (props.isCityPage) {
        map.value.setView([props.cities[0].latitude, props.cities[0].longitude], 6)
    }
}

onMounted(async () => {
  await nextTick()
  buildMap()
  fillMap()
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

function clearMap() {
  markers.value.clearLayers()
}

function buildMap() {
  map.value = L.map(mapContainer.value)
  refreshMapCenter()

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18,
    center: [0, 0],
  }).addTo(map.value)

  map.value.invalidateSize()
}

function fillMap() {
  markers.value = L.featureGroup()

  const selectedCountryId = filterStore.selectedCountry ? filterStore.selectedCountry.id : null
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
      .on('click', () => {
          if (filterStore.selectedCity && filterStore.selectedCity.id === city.id) {
              filterStore.changeSelectedCity(null)
          } else {
              filterStore.changeSelectedCity(city)
          }
      })
      .bindTooltip(`<i class="${city.country.iso} flat flag shadow"></i> ${city.name}, ${city.country.name}`)
  })

  markers.value.addTo(map.value)
}


// const userLocation = ref(null)

// function centerToUserLocation() {
//   if (navigator.geolocation) {
//     navigator.geolocation.getCurrentPosition(
//       position => {
//         const { latitude, longitude } = position.coords
//         userLocation.value = L.latLng(latitude, longitude)
//         map.value.setView(userLocation.value, 16)
//       },
//       () => {
//         console.error('Error getting user location.')
//       },
//     )
//   } else {
//     console.error('Geolocation is not supported by this browser.')
//   }
// }

</script>

<template>
  <div id="mapContainer" ref="mapContainer" class="fixed h-full w-full lg:w-1/2" />
</template>


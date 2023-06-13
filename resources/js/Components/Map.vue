<script>
import { onMounted, ref, nextTick } from 'vue'
import mapboxgl from 'mapbox-gl'

export default {
  setup() {
    const mapContainer = ref(null)
    const map = ref(null)

    onMounted(async() => {
      await nextTick()
      buildMap()
    })

    function buildMap() {
      mapboxgl.accessToken = import.meta.env.VITE_APP_MAPBOX_TOKEN
      map.value = new mapboxgl.Map({
        container: mapContainer.value,
        style: import.meta.env.VITE_APP_MAPBOX_STYLE_URL,
        center: [51.107883, 17.038538],
        zoom: 5,
      })
      map.value.addControl(new mapboxgl.NavigationControl())
    }

    return {
      mapContainer,
      buildMap,
    }
  },
}
</script>

<template>
  <div ref="mapContainer" class="h-full" />
</template>

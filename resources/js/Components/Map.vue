<script>
import { onMounted } from 'vue'
import mapboxgl from 'mapbox-gl'

export default {
  setup() {
    onMounted(buildMap)

    function buildMap() {
      mapboxgl.accessToken = import.meta.env.VITE_APP_MAPBOX_TOKEN
      const map = new mapboxgl.Map({
        container: 'map',
        style: import.meta.env.VITE_APP_MAPBOX_STYLE_URL,
        center: [	51.107883, 17.038538],
        zoom: 10,
      })
      map.addControl(new mapboxgl.NavigationControl())
    }

    return {
      buildMap,
    }
  },
}
</script>

<template>
  <div>
    <div id="map" class="absolute inset-0 h-full w-full" />
  </div>
</template>

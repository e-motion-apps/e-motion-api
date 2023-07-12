import { defineStore } from 'pinia'

export const useMapMarkerStore = defineStore('MapMarkerStore', {
  state: () => ({
    marker: null,
  }),
  actions: {
    changeMarker(marker) {
      this.marker = marker
    },
  },
})

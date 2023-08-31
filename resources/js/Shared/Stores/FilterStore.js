import { defineStore } from 'pinia'

export const useFilterStore = defineStore('useFilterStore', {
  state: () => ({
    selectedCity: null,

    selectedCountry: null,
    selectedProviderName: null,
  }),
  actions: {
    changeSelectedCity(city) {
        this.selectedCity = city
    },
    changeSelectedCountry(country) {
      this.selectedCountry = country
    },
    changeSelectedProvider(providerName) {
      this.selectedProviderName = providerName
    },
  },
})

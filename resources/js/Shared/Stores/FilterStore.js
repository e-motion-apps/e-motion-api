import { defineStore } from 'pinia'

export const useFilterStore = defineStore('useFilterStore', {
  state: () => ({
    selectedCity: null,
    selectedFavorites: false,
    selectedCountryId: null,
    selectedProviderName: null,
  }),
  actions: {
    changeSelectedCity(city) {
      this.selectedCity = city
    },
    changeSelectedFavorites(favorites) {
      this.selectedFavorites = favorites
    },
    changeSelectedCountry(countryId) {
      this.selectedCountryId = countryId
    },
    changeSelectedProvider(providerName) {
      this.selectedProviderName = providerName
    },
  },
})

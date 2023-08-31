import { defineStore } from 'pinia'

export const useFilterStore = defineStore('useFilterStore', {
  state: () => ({

    citiesWithProviders: {
      cities: [],
      providers: [],
      countries: [],
    },

    selectedCity: null,
    selectedCountry: null,
    selectedProviderName: null,
  }),
  actions: {
    saveCitiesWithProviders(response) {
      this.citiesWithProviders.cities = response.data.cities
      this.citiesWithProviders.providers = response.data.providers
      this.citiesWithProviders.countries = response.data.countries
    },
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

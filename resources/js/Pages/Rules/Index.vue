<script setup>
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue'
import SearchPanel from './SearchPanel.vue'
import SearchPanelSkeleton from '@/Shared/Layout/SearchPanelSkeleton.vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'
import { HandRaisedIcon } from '@heroicons/vue/24/outline'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { __ } from '@/translate'
import { useFilterStore } from '@/Shared/Stores/FilterStore'
import { nav } from '@/Shared/Layout/Nav.vue'

const breakpoints = useBreakpoints(breakpointsTailwind)
const filterStore = useFilterStore()
const isMobile = ref(breakpoints.smaller('lg'))
const isDesktop = ref(breakpoints.greaterOrEqual('lg'))
const page = usePage()
const isAuth = computed(() => page.props.auth.isAuth)
const dataIsFetched = ref(false)
const data = reactive(filterStore.citiesWithProviders)

function fetchData() {
  if (!filterStore.citiesWithProviders.providers.length) {
    axios.get('/api/providers').then(response => {
      filterStore.saveCitiesWithProviders(response)
    }).finally(() => {
      dataIsFetched.value = true
    })
  } else {
    dataIsFetched.value = true
  }
}

onMounted(() => {
  fetchData()
  watch(() => filterStore.selectedCity, () => {
    window.scrollTo(0, 0)
  })
})

onUnmounted(() => {
  filterStore.reset()
})

</script>

<template>
  <div class="flex h-screen flex-col">
    <Nav ref="nav" class="z-30" />

    <div class="mt-16 flex grow flex-col lg:flex-row">
      <div class="w-full">
        <SearchPanel v-if="dataIsFetched" :countries="data.countries" />
        <SearchPanelSkeleton v-else />
      </div>
    </div>
  </div>
</template>

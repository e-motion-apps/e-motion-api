<script setup>
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { __ } from '@/translate'
import { onMounted, ref } from 'vue'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js/auto'
import { Doughnut } from 'vue-chartjs'

onMounted(() => {
  ChartJS.register(ArcElement, Tooltip, Legend)
})

const page = usePage()

const props = defineProps({
  usersCount: Number,
  citiesWithProvidersCount: Number,
  countriesWithCitiesWithProvidersCount: Number,
  providersCount: Number,
  providerCitiesCount: Object,
  providers: Object,
})

function getProviderColor(providerName) {
  const provider = props.providers.find(provider => provider.name === providerName)

  return provider ? provider.color : ''
}

const chartData = ref({
  labels: [3],
  datasets: [
    {
      backgroundColor: [],
      data: [5],
    },
  ],
})

onMounted(() => {
  const labels = []
  const backgroundColors = []
  const data = []

  props.providerCitiesCount.forEach((provider) => {
    labels.push(provider.name)
    backgroundColors.push(getProviderColor(provider.name))
    data.push(provider.count)
  })

  chartData.value = {
    labels: labels,
    datasets: [
      {
        backgroundColor: backgroundColors,
        data: data,
      },
    ],
  }
})


const chartOptions = {

  responsive: true,
  maintainAspectRatio: false,
  aspectRatio: 2 / 3,
  animation: false,
  plugins: {
    legend: {
      display: false,
    },
  },
}

</script>

<template>
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <AdminNavigation :url="page.url" />
    <div class="flex w-full md:justify-end">
      <div class="mt-16 flex h-full w-full flex-col md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
        <div class="p-4">
          <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-4">
            <div class="overflow-hidden rounded-lg border bg-white px-4 py-5 sm:p-6">
              <dt class="truncate text-sm font-medium text-blumilk-500">
                {{ __('Users count') }}
              </dt>
              <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ usersCount }}
              </dd>
            </div>
            <div class="overflow-hidden rounded-lg border bg-white px-4 py-5 sm:p-6">
              <dt class="truncate text-sm font-medium text-blumilk-500">
                {{ __('Cities with providers') }}
              </dt>
              <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ citiesWithProvidersCount }}
              </dd>
            </div>
            <div class="overflow-hidden rounded-lg border bg-white px-4 py-5 sm:p-6">
              <dt class="truncate text-sm font-medium text-blumilk-500">
                {{ __('Countries with providers') }}
              </dt>
              <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ countriesWithCitiesWithProvidersCount }}
              </dd>
            </div>
            <div class="overflow-hidden rounded-lg border bg-white px-4 py-5 sm:p-6">
              <dt class="truncate text-sm font-medium text-blumilk-500">
                {{ __('Providers count') }}
              </dt>
              <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ providersCount }}
              </dd>
            </div>
          </dl>
        </div>

        <div class="mt-8 flex w-full flex-col lg:mt-6 lg:flex-row lg:justify-end">
          <div class="w-full px-3 lg:w-1/2">
            <h1 class="mx-2 mb-2 text-2xl font-bold text-gray-700">
              {{ __('Number of cities where the provider is available') }}
            </h1>

            <div class="flex flex-wrap">
              <div v-for="provider in props.providerCitiesCount" :key="provider.name"
                   class="m-2 flex flex-col items-center shadow-lg"
              >
                <div :style="{ 'background-color': getProviderColor(provider.name) }"
                     class="flex h-12 w-16 shrink-0 items-center justify-center rounded-md rounded-b-none p-6 px-2 py-3"
                >
                  <img loading="lazy" :src="'/providers/' + provider.name.toLowerCase() + '.png'" alt="">
                </div>
                <div class="w-full rounded rounded-t-none border border-t-0 bg-gray-50 ">
                  <span class="flex w-full justify-center rounded-full text-sm font-medium text-gray-700">{{ provider.count }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-8 px-6 pb-6 lg:mt-0 lg:w-1/2">
            <Doughnut :data="chartData" :options="chartOptions" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

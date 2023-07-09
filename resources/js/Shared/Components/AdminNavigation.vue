<script setup>
import { Link } from '@inertiajs/vue3'
import { ChartBarIcon, ClipboardIcon, FlagIcon, MapPinIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  url: String,
})

</script>

<template>
  <div class="fixed z-10 flex w-full border-blumilk-50 bg-blumilk-25 shadow md:h-full md:w-1/3 md:border-r lg:w-1/4 xl:w-1/6">
    <div class="flex w-full justify-between md:flex-col md:justify-normal">
      <Link href="/" class="flex shrink-0 items-center pl-6 pr-2 md:mt-3">
        <img class="h-10" src="@/assets/scooter.png" alt="escooter logo">
        <span class="ml-3 hidden text-2xl font-semibold text-gray-800 md:flex">e&#8209;scooters</span>
      </Link>

      <ul class="flex items-center space-x-0.5 font-semibold text-gray-800 md:mt-10 md:flex-col md:items-stretch md:space-y-4">
        <Link href="#">
          <li
            :class="props.url === '/admin/dashboard' ? 'bg-blumilk-50' : ''"
            class="flex w-full items-center bg-blumilk-25 px-4 py-5 hover:bg-blumilk-50 sm:px-6"
          >
            <ClipboardIcon class="h-6 w-6" />
            <span class="ml-2 hidden md:flex">Dashboard</span>
          </li>
        </Link>

        <Link href="/admin/dashboard/countries">
          <li :class="props.url === '/admin/dashboard/countries' ? 'bg-blumilk-50' : ''"
              class="flex w-full items-center bg-blumilk-25 px-4 py-5 hover:bg-blumilk-50 sm:px-6"
          >
            <FlagIcon class="h-6 w-6" />
            <span class="ml-2 hidden md:flex">Countries</span>
          </li>
        </Link>
        <Link href="/admin/dashboard/cities">
          <li :class="props.url === '/admin/dashboard/cities' ? 'bg-blumilk-50' : ''"
              class="flex w-full items-center bg-blumilk-25 px-4 py-5 hover:bg-blumilk-50 sm:px-6"
          >
            <MapPinIcon class="h-6 w-6" />
            <span class="ml-2 hidden md:flex">Cities</span>
          </li>
        </Link>

        <Link href="#">
          <li
            :class="props.url === '/admin/dashboard/statistics' ? 'bg-blumilk-50' : ''"
            class="flex w-full items-center bg-blumilk-25 px-4 py-5 hover:bg-blumilk-50 sm:px-6"
          >
            <ChartBarIcon class="h-6 w-6" />
            <span class="ml-2 hidden md:flex">Statistics</span>
          </li>
        </Link>
      </ul>
    </div>
  </div>
</template>


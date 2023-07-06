<script setup>
import { Link } from '@inertiajs/vue3'
import { ChartBarIcon, ClipboardIcon, FlagIcon, MapPinIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  url: String,
})

</script>

<template>
  <div class="hidden border-r border-blumilk-50 bg-blumilk-25 md:flex md:w-1/3 lg:w-1/4 xl:w-1/6">
    <div class="flex w-full flex-col">
      <Link href="/" class="flex items-center px-6 py-3">
        <img class="h-10" src="@/assets/scooter.png" alt="escooter logo">
        <span class="ml-3 hidden text-2xl font-semibold text-gray-800 lg:flex">e&#8209;scooters</span>
      </Link>

      <ul class="mt-10 flex flex-col space-y-4 text-lg font-semibold text-gray-800">
        <Link href="#">
          <li
            :class="props.url === '/admin/dashboard' ? 'bg-blumilk-50' : ''"
            class="flex w-full items-center bg-blumilk-25 px-6 py-4 hover:bg-blumilk-50"
          >
            <ClipboardIcon class="mr-2 h-6 w-6" />
            Dashboard
          </li>
        </Link>

        <Link href="/admin/dashboard/countries">
          <li :class="props.url === '/admin/dashboard/countries' ? 'bg-blumilk-50' : ''"
              class="flex w-full items-center bg-blumilk-25 px-6 py-4 hover:bg-blumilk-50"
          >
            <FlagIcon class="mr-2 h-6 w-6" />
            Countries
          </li>
        </Link>
        <Link href="/admin/dashboard/cities">
          <li :class="props.url === '/admin/dashboard/cities' ? 'bg-blumilk-50' : ''"
              class="flex w-full items-center bg-blumilk-25 px-6 py-4 hover:bg-blumilk-50"
          >
            <MapPinIcon class="mr-2 h-6 w-6" />
            Cities
          </li>
        </Link>

        <Link href="#">
          <li
            :class="props.url === '/admin/dashboard/statistics' ? 'bg-blumilk-50' : ''"
            class="flex w-full items-center bg-blumilk-25 px-6 py-4 hover:bg-blumilk-50"
          >
            <ChartBarIcon class="mr-2 h-6 w-6" />
            Statistics
          </li>
        </Link>
      </ul>
    </div>
  </div>
</template>


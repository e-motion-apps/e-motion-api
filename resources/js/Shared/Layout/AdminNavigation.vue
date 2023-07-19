<script setup>
import { Link } from '@inertiajs/vue3'
import { ChartBarIcon, ClipboardIcon, FlagIcon, MapPinIcon, PlayCircleIcon } from '@heroicons/vue/24/solid'
import { Bars3Icon } from '@heroicons/vue/24/outline'

const props = defineProps({
  url: String,
})
const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard', icon:ClipboardIcon },
    { name: 'Countries', href: '/admin/countries', icon:FlagIcon },
    { name: 'Cities', href: '/admin/cities', icon:MapPinIcon },
    { name: 'Statistics', href: '/admin/statistics', icon:ChartBarIcon },
    { name: 'Run importers', href: '/run-importers', icon:PlayCircleIcon },
]

</script>

<template>
  <div class="fixed z-10 flex w-full border-blumilk-50 bg-blumilk-25 shadow md:h-full md:w-1/3 md:border-r lg:w-1/4 xl:w-1/6">
    <div class="flex w-full justify-between md:flex-col md:justify-normal">
      <Link href="/" class="mt-3 flex shrink-0 items-center pb-3 pl-6 pr-2">
        <img class="h-10" src="@/assets/scooter.png" alt="escooter logo">
        <span class="ml-3 hidden text-2xl font-semibold text-gray-800 md:flex">e&#8209;scooters</span>
      </Link>

      <div class="mr-3.5 flex sm:hidden">
        <button type="button" class="inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">
          <span class="sr-only">Open main menu</span>
          <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>
      </div>

      <ul class="hidden h-full items-center text-sm font-medium text-gray-800 sm:flex md:mt-12 md:flex-col md:items-stretch md:space-y-2">
          <Link v-for="item in navigation" :key="item.name" :href="item.href" class="flex h-full md:h-fit" >
              <div :class="$page.url.startsWith(item.href)? 'bg-blumilk-50' : ''"
                  class="mx-auto flex w-11/12 items-center bg-blumilk-25 px-6 hover:bg-blumilk-50 md:rounded-lg md:px-2 md:py-3"
              >
                  <component :is="item.icon" class="h-7 w-7" />
              <span class="ml-3 hidden md:flex">{{ item.name }}</span>
              </div>
          </Link>
      </ul>
    </div>
  </div>
</template>


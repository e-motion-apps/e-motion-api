<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon, UserCircleIcon } from '@heroicons/vue/24/outline'
import { Link } from '@inertiajs/vue3'

const navigation = [
  { name: 'Cities', href: '/admin/dashboard/cities' },
  { name: 'Countries', href: '/admin/dashboard/cities' },
]

const mobileMenuOpen = ref(false)
</script>

<template>
  <header class="w-full bg-white">
    <nav class="mx-auto flex items-center justify-between px-6 py-3 shadow-lg shadow-gray-50" aria-label="Global">
      <Link href="/" class="flex items-center justify-center">
        <img class="h-10" src="@/assets/scooter.png" alt="escooter logo">
        <span class="ml-3 hidden text-2xl font-semibold sm:flex">e-scooters</span>
      </Link>
      <div class="flex lg:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">
          <span class="sr-only">Open main menu</span>
          <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>
      </div>
      <div class="hidden items-center lg:flex lg:gap-x-12">
        <Link v-for="item in navigation" :key="item.name" :href="item.href" class="text-sm font-semibold  leading-6 text-gray-900 lg:text-xl">
          {{ item.name }}
        </Link>
        <Link href="/login">
          <UserCircleIcon class="h-6 w-6" />
        </Link>
      </div>
    </nav>

    <Dialog v-if="mobileMenuOpen" as="div" class="z-30 lg:hidden" :open="mobileMenuOpen" @close="mobileMenuOpen = false">
      <div class="fixed inset-0 z-30 " />sdf
      <DialogPanel class="fixed inset-y-0 right-0 z-30 w-full overflow-y-auto border-b-2 bg-white px-6 py-3 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
        <div class="flex items-center justify-between sm:justify-end">
          <img class="h-10 sm:hidden" src="@/assets/scooter.png" alt="escooter logo">
          <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
            <span class="sr-only">Close menu</span>
            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <div class="mt-6 flow-root">
          <div class="-my-6 divide-y divide-gray-500/10">
            <div class="space-y-2 py-6">
              <a v-for="item in navigation" :key="item.name" :href="item.href" class=" -mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-blumilk-25">{{ item.name }}</a>
            </div>
            <div class="py-6">
              <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold  leading-7 text-gray-900 hover:bg-blumilk-25">Log in</a>
            </div>
          </div>
        </div>
      </DialogPanel>
    </Dialog>
  </header>
</template>

<script setup>
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'

defineProps({
  statusTitle: String,
  statusDescription: String,
  statusCode: Number,
})
</script>

<template>
  <div class="grid min-h-screen grid-cols-1 grid-rows-[1fr,auto,1fr] bg-white lg:grid-cols-[max(50%,36rem),1fr]">
    <main class="mx-auto w-full max-w-7xl px-6 py-24 sm:py-32 lg:col-span-2 lg:col-start-1 lg:row-start-2 lg:px-8">
      <div class="max-w-lg">
        <p class="px-0.5 text-2xl font-semibold leading-8 text-blumilk-500">
          {{ statusCode }}
        </p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-gray-800 sm:text-7xl">
          {{ statusTitle }}
        </h1>
        <p class="mt-6 px-0.5 text-base leading-7 text-gray-600">
          {{ statusDescription }}
        </p>
        <div class="mt-10">
          <InertiaLink href="/" class="flex w-fit items-center py-4 pr-4 text-sm font-semibold leading-7 text-blumilk-500">
            <ArrowLeftIcon class="mr-2 h-10 w-10" />
          </InertiaLink>
        </div>
      </div>
    </main>

    <div class="hidden lg:relative lg:col-start-2 lg:row-start-1 lg:row-end-4 lg:block">
      <img src="../assets/error-page-photo.jpg" alt="" class="absolute inset-0 h-full w-full object-cover">
    </div>
  </div>
</template>

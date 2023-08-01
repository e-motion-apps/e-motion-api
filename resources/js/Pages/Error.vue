<script setup>
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'

defineProps({
  statusCode: Number,
})
</script>

<template>
  <div class="grid min-h-screen grid-cols-1 grid-rows-[1fr,auto,1fr] bg-white lg:grid-cols-[max(50%,36rem),1fr]">
    <main class="mx-auto w-full max-w-7xl px-6 py-24 sm:py-32 lg:col-span-2 lg:col-start-1 lg:row-start-2 lg:px-8">
      <div class="max-w-lg">
        <p class="text-2xl font-semibold leading-8 text-blumilk-500">
          {{ statusCode }}
        </p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-gray-900 sm:text-7xl">
          Page not found
        </h1>
        <p class="mt-6 text-base leading-7 text-gray-600">
          Sorry, we couldn’t find the page you’re looking for.
        </p>
        <div class="mt-10">
          <InertiaLink href="/" class="flex items-center text-sm font-semibold leading-7 text-blumilk-500">
            <ArrowLeftIcon class="mr-2 h-6 w-6" />
            <span class="text-xl">Back to home</span>
          </InertiaLink>
        </div>
      </div>
    </main>

    <div class="hidden lg:relative lg:col-start-2 lg:row-start-1 lg:row-end-4 lg:block">
      <img src="../../../public/error-page-photo.jpg" alt="" class="absolute inset-0 h-full w-full object-cover">
    </div>
  </div>
</template>

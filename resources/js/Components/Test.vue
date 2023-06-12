<script setup>
import Map from './Map.vue'
</script>

<template>
  <div class="mx-auto grid min-h-full grid-cols-2 grid-rows-[1fr,auto,1fr] bg-white lg:grid-cols-[max(50%,36rem),1fr]">
    <div class="mx-auto w-full max-w-full px-6 py-24 sm:py-32 lg:col-span-2 lg:col-start-1 lg:row-start-2 lg:px-8">
      <div class="max-w-lg">
        <div class="p-4">
          <img src="@/assets/undraw_scooter.svg" alt="Man riding a e-scooter" class="mx-auto">
        </div>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">
          Looking for an e-scooter?
        </h1>
        <p class="mt-6 text-base leading-7 text-gray-600">
          There are plenty of e-scooter providers, so sometimes you can be confused which app should you use during your trips. I know this problem. This little map can help you with planning your vacations or business trips.
        </p>
      </div>
    </div>
    
    
    <Map class="map hidden lg:relative lg:col-start-2 lg:row-start-1 lg:row-end-4 lg:block" />
  </div>
</template>





  

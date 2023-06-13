<script setup>
import Map from './Map.vue'
import Info from './Info.vue'
</script>

<template>
  <div class="mx-auto grid min-h-full bg-white  lg:grid-cols-2 ">
    <Info />
    <Map class="hidden lg:block lg:aspect-square lg:h-full lg:w-full" />
  </div>
</template>





  

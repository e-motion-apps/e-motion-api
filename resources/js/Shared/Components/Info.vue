<script setup>
import PrimaryButton from './PrimaryButton.vue'
import SecondaryButton from './SecondaryButton.vue'
const emit = defineEmits(['try-it-out'])
</script>

<template>
  <div class="h-full px-6 lg:px-12 ">
    <div class="p-8">
      <img src="@/assets/undraw_scooter.svg" alt="Man riding an e-scooter" class="h-90 mx-auto">
    </div>
    <h1 class=" text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
      Looking for an e-scooter?
    </h1>
    <p class="mx-auto  pt-6  text-xs leading-7 text-gray-600 sm:text-sm lg:text-lg">
      There are plenty of e-scooter providers, so sometimes you can be confused about which app you should use during your trips. I understand this problem. This little map can help you with planning your vacations or business trips.
    </p>
    <div class=" flex w-full justify-around pt-4 lg:h-auto lg:pt-14">
      <PrimaryButton class="px-1.5 py-2 sm:px-3.5 sm:py-4" @click="emit('try-it-out')">
        Try it out
      </PrimaryButton>
      <SecondaryButton class="px-1.5 py-2 sm:px-3.5 sm:py-4">
        Register
      </SecondaryButton>
    </div>
  </div>
</template>

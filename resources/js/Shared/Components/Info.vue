<script setup>
import PrimaryButton from '@/Shared/Components/PrimaryButton.vue'
import SecondaryButton from '@/Shared/Components/SecondaryButton.vue'

const emit = defineEmits(['try-it-out'])
</script>

<template>
  <div class="flex h-full flex-col items-start justify-around bg-blumilk-25 px-6 pb-16 sm:px-12 lg:px-14">
    <div class="hidden md:flex md:h-1/2 lg:h-1/3">
      <img class="max-h-full" src="@/assets/undraw_scooter.svg" alt="Man riding an e-scooter">
    </div>

    <div>
      <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
        Looking for an e&#8209;scooter?
      </h1>
      <p class="mx-auto pt-10 text-sm leading-7 text-gray-600 sm:text-base lg:text-lg">
        There are plenty of e-scooter providers, so sometimes you can be confused about which app you should
        use during your trips.
        <br>
        <strong>This application can help you with planning your vacations or business trips.</strong>
      </p>
    </div>

    <div class="flex w-full flex-wrap items-center justify-center space-y-5 pt-3 sm:justify-between sm:space-y-0">
      <PrimaryButton class="h-fit w-11/12 py-5 sm:w-5/12" @click="emit('try-it-out')">
        Try it out
      </PrimaryButton>

      <SecondaryButton class="h-fit w-11/12 py-5 sm:w-5/12">
        Create account
      </SecondaryButton>
    </div>
  </div>
</template>

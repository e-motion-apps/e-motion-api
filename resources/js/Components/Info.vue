<script setup>
import { defineEmits } from 'vue'

const emit = defineEmits(['try-it-out'])

const tryItOut = () => {
  emit('try-it-out')
}
</script>

<template>
  <div class="m-4">
    <img src="@/assets/undraw_scooter.svg" alt="Man riding an e-scooter" class="mx-auto">
  </div>
  <h1 class="m-4 text-5xl font-bold tracking-tight text-gray-900 sm:text-5xl">
    Looking for an e-scooter?
  </h1>
  <p class="m-4 mt-6 text-base leading-7 text-gray-600">
    There are plenty of e-scooter providers, so sometimes you can be confused about which app you should use during your trips. I understand this problem. This little map can help you with planning your vacations or business trips.
  </p>
  <div class=" m-auto flex justify-around">
    <button type="button" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:text-4xl" @click="tryItOut">
      Try it out
    </button>
    <button type="button" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:text-4xl">
      Register
    </button>
  </div>
</template>

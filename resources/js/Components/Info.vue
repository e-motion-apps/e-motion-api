<script setup>

const emit = defineEmits(['try-it-out'])

const tryItOut = () => {
  emit('try-it-out')
}
</script>

<template>
  <div class="h-full px-6 lg:px-12 ">
    <div class="p-8">
      <img src="@/assets/undraw_scooter.svg" alt="Man riding an e-scooter" class="mx-auto">
    </div>
    <h1 class=" text-5xl font-bold tracking-tight text-gray-900 sm:text-5xl">
      Looking for an e-scooter?
    </h1>
    <p class="mx-auto  pt-8 text-base leading-7 text-gray-600">
      There are plenty of e-scooter providers, so sometimes you can be confused about which app you should use during your trips. I understand this problem. This little map can help you with planning your vacations or business trips.
    </p>
    <div class=" mx-auto flex justify-around pt-14">
      <button type="button" class="rounded-md bg-[#527ABA] px-3.5 py-2.5 text-lg font-semibold lowercase text-white shadow-sm hover:brightness-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:text-2xl" @click="tryItOut">
        Try it out
      </button>
      <button type="button" class="rounded-md bg-[#527ABA] px-3.5 py-2.5 text-lg font-semibold lowercase text-white shadow-sm hover:brightness-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:text-2xl">
        Register
      </button>
    </div>
  </div>
</template>

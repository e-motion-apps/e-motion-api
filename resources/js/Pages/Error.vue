<script setup>
defineProps({
  statusCode: String,
  message: String,
})
</script>

<template>
  <main class="relative isolate min-h-screen">
    <img src="../../../public/error-page-photo.jpg" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover object-top">
    <div class="mx-auto max-w-7xl px-6 py-32 text-center sm:py-40 lg:px-8">
      <p class="text-base font-semibold leading-8 text-white">
        {{ statusCode }}
      </p>
      <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-5xl">
        Page not found
      </h1>
      <p class="mt-4 text-base text-white/70 sm:mt-6">
        {{ message }}
      </p>
      <div class="mt-10 flex justify-center">
        <InertiaLink href="/" class="text-sm font-semibold leading-7 text-white">
          Back to home
        </InertiaLink>
      </div>
    </div>
  </main>
</template>

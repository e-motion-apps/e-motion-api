<script setup>
import { FolderOpenIcon } from '@heroicons/vue/24/outline'
</script>

<template>
  <button type="submit" class="mt-3 flex w-full shrink-0 justify-center rounded border border-emerald-500 bg-emerald-500 px-5 py-3 text-white hover:bg-emerald-600 md:w-fit md:py-2">
    <span class="flex flex-wrap items-center justify-center space-x-2">
      <span class="font-bold">
        <slot />
      </span>
      <FolderOpenIcon class="size-5" />
    </span>
  </button>
</template>

<script setup>
import { __ } from '@/translate'
const props = defineProps({
  meta: Object,
})
</script>

<template>
  <div class="flex items-center justify-between bg-white py-3 ">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-700">
          {{ __('Showing') }}
          <span class="font-medium">{{ props.meta.from }}</span>
          {{ __('to') }}
          <span class="font-medium"> {{ props.meta.to }}</span>
          {{ __('of') }}
          <span class="font-medium">{{ props.meta.total }}</span>
          {{ __('results') }}
        </p>
      </div>
    </div>
  </div>
</template>

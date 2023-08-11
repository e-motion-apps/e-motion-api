<script setup>
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
          {{ __('To') }}
          <span class="font-medium"> {{ props.meta.to }}</span>
          {{ __('Of') }}
          <span class="font-medium">{{ props.meta.total }}</span>
          {{ __('Results') }}
        </p>
      </div>
    </div>
  </div>
</template>

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
          {{ __('Word.Showing') }}
          <span class="font-medium">{{ props.meta.from }}</span>
          {{ __('Word.To') }}
          <span class="font-medium"> {{ props.meta.to }}</span>
          {{ __('Word.Of') }}
          <span class="font-medium">{{ props.meta.total }}</span>
          {{ __('Word.Results') }}
        </p>
      </div>
    </div>
  </div>
</template>

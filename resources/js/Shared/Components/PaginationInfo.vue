<script setup>
const props = defineProps({
  meta: Object,
})
</script>

<template>
  <div class="flex items-center justify-between border-t border-gray-200 bg-white py-3 ">
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ props.meta.from }}</span>
          to
          <span class="font-medium"> {{ props.meta.to }}</span>
          of
          <span class="font-medium">{{ props.meta.total }}</span>
          results
        </p>
      </div>
    </div>
  </div>
</template>

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

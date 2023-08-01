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
          {{ $t('Word.Showing') }}
          <span class="font-medium">{{ props.meta.from }}</span>
          {{ $t('Word.To') }}
          <span class="font-medium"> {{ props.meta.to }}</span>
          {{ $t('Word.Of') }}
          <span class="font-medium">{{ props.meta.total }}</span>
          {{ $t('Word.Results') }}
        </p>
      </div>
    </div>
  </div>
</template>

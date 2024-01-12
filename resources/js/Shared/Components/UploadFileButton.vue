<script setup>
import { ref } from 'vue'
import { __ } from '@/translate'

const uploadedFileName = ref('')

const handleFileChange = (event) => {
  const files = event.target.files

  if (files.length > 0) {
    uploadedFileName.value = files[0].name
  }
}
</script>

<template>
  <div class="flex w-full flex-col  justify-center">
    <label
      for="dropzone-file"
      class="justify-left flex h-12 w-full cursor-pointer items-center rounded-md border border-blumilk-100 shadow hover:bg-gray-100"
    >
      <p class="rounded bg-gray-600 p-4 text-white">{{ __('Browse') }}</p>
      <p v-if="uploadedFileName" class="ml-1 p-2 text-gray-600">{{ uploadedFileName }}</p>
      <p v-else class="ml-1 p-2 text-gray-600">{{ __('No file selected.') }}</p>

      <input id="dropzone-file" type="file" class="hidden" @change="handleFileChange">
    </label>
    <p class="pl-1 pt-1 text-xs text-gray-600">
      PNG, 150x100px, 10 kB
    </p>
  </div>
</template>

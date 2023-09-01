<script setup>
import {ref} from 'vue'
import {__} from '@/translate'

const uploadedFileName = ref('')

const handleFileChange = (event) => {
  const files = event.target.files
  if (files.length > 0) {
    uploadedFileName.value = files[0].name
  }
}
</script>

<template>
  <div class="flex flex-col justify-center  w-full">
    <label
        for="dropzone-file"
        class="flex items-center justify-left w-full h-12 border border-blumilk-100 shadow rounded-md cursor-pointer hover:bg-gray-100"
    >
      <p class="text-white bg-gray-600 rounded p-4">{{ __('Browse') }}</p>
      <p v-if="uploadedFileName" class="ml-1 text-gray-600 p-2">{{ uploadedFileName }}</p>
      <p v-else class="ml-1 text-gray-600 p-2">{{ __('No file selected.') }}</p>

      <input id="dropzone-file" type="file" class="hidden" @change="handleFileChange"/>
    </label>
    <p class="text-xs text-gray-600 pt-1 pl-1">PNG, 150x100px, 10 kB</p>
  </div>
</template>

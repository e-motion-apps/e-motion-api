<script setup>
import { defineEmits } from 'vue'
import { __ } from '@/translate'

const emits = defineEmits(['close', 'delete'])

const closeModal = () => emits('close')
const confirmDelete = () => emits('delete')
</script>
  
<template>
  <div class="fixed inset-0 z-10 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg p-6 text-center">
      <h2 class="text-lg font-semibold mb-4">{{ __('Delete record') }}</h2>
      <p>{{ __('Are you sure you want to delete this record?') }}</p>
      <div class="mt-4 flex justify-center">
        <button class="px-3 py-1 mr-2 text-sm text-gray-800 bg-gray-200 hover:bg-gray-300 rounded"
          @click="closeModal">
          {{ __('Cancel') }}
        </button>
        <button class="px-3 py-1 text-sm font-bold text-white bg-rose-500 hover:bg-rose-600 rounded"
          @click="confirmDelete">
          {{ __('Delete') }}
        </button>
      </div>
    </div>
  </div>
</template>
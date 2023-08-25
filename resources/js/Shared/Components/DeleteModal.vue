<script setup>
import { defineEmits } from 'vue'
import { __ } from '@/translate'
  
const emits = defineEmits(['close', 'delete'])
  
const closeModal = () => emits('close')
const confirmDelete = () => emits('delete')
</script>
  
<template>
  <div class="fixed inset-0 z-10 flex items-center justify-center bg-black/50">
    <div class="rounded-lg bg-white p-6">
      <h2 class="mb-4 text-left text-lg font-semibold">
        {{ __('Delete record') }}
      </h2>
      <p class="text-left">
        {{ __('Are you sure you want to delete this record?') }}
      </p>
      <div class="mt-4 flex justify-start">
        <button class="mr-2 rounded bg-gray-200 px-3 py-1 text-sm font-bold text-gray-800 hover:bg-gray-300" @click="closeModal">
          {{ __('Cancel') }}
        </button>
        <button class="rounded bg-rose-500 px-3 py-1 text-sm font-bold text-white hover:bg-rose-600" @click="confirmDelete">
          {{ __('Delete') }}
        </button>
      </div>
    </div>
  </div>
</template>

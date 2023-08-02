<script setup>
import { ref } from 'vue'
import { HeartIcon as OutlineHeartIcon } from '@heroicons/vue/24/outline'

const showDescription = ref(false)
</script>

<template>
  <div @mouseover="showDescription = true" @mouseout="showDescription = false">
    <span><OutlineHeartIcon class="h-6 w-6 text-gray-500" /></span>
    <div v-show="showDescription" class="absolute z-50 rounded-lg border border-gray-300 bg-white p-2 text-sm shadow-md">
      {{ __('Prompt.loginToAddToFavorite') }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { HeartIcon as OutlineHeartIcon } from '@heroicons/vue/24/outline'

const showDescription = ref(false)
</script>

<template>
  <div @mouseover="showDescription = true" @mouseout="showDescription = false">
    <span><OutlineHeartIcon class="h-6 w-6 text-gray-500" /></span>
    <div v-show="showDescription" class="description">
      Login or create account to add to favorites
    </div>
  </div>
</template>

<style>
  .description {
    font-size: small;
    position: absolute;
    background-color: #f9f9f9;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    z-index: 999;
  }
</style>

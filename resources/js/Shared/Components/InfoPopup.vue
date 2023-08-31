<script setup>
import { HeartIcon as OutlineHeartIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'
import { __ } from '@/translate'

const showToast = () => {
  const toast = useToast()
  toast.info(__('Log in to add to favorites.'))
}

</script>

<template>
  <span @click="showToast">
    <OutlineHeartIcon class="h-8 w-8 sm:h-6 sm:w-6 text-gray-500" />
  </span>
</template>

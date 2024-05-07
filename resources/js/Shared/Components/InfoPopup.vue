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
  <span @click.stop="showToast">
    <OutlineHeartIcon class="size-8 text-gray-500 sm:size-6" />
  </span>
</template>

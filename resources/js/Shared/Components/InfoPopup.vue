<script setup>
import { HeartIcon as OutlineHeartIcon } from '@heroicons/vue/24/outline'
import { useToast } from "vue-toastification";

const showToast = () => {
  const toast = useToast()
  toast.info("Login to add to favorites");
}

</script>

<template>
  <span @click="showToast">
    <OutlineHeartIcon class="h-6 w-6 text-gray-500" />
  </span>
</template>

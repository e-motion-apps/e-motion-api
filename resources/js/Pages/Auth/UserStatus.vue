<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const isAuthenticated = computed(() => usePage().props.auth.isAuth)
const user = computed(() => usePage().props.auth.user)
</script>


<template>
  <div class="flex justify-end">
    <div v-if="isAuthenticated">
      Logged in as: {{ user.name }}
    </div>
    <div v-else>
      You are not logged in.
    </div>
  </div>
</template>

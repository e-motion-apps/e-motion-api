<script setup>

import Nav from '@/Shared/Layout/Nav.vue'

defineProps({
  user: Object,
})
</script>

<template>
  <div class="flex h-screen flex-col">
    <Nav ref="nav" class="z-30" />
    <div class="mt-16">
      <div class="mb-2">
        <strong>Username:</strong> {{ user.name }}
      </div>
      <div class="mb-2">
        <strong>Email:</strong> {{ user.email }}
      </div>
      <div class="mb-2">
        <strong>Account created at:</strong> {{ user.created_at }}
      </div>
      <div class="mb-2">
        <strong>Account created by:</strong> {{ user.logged_by }}
      </div>
    </div>
    <div v-if="user.logged_by === 'registration'" class="mt-2">
      <div class="mb-2">
        <strong>Change password</strong>
      </div>
      <div class="mb-2">
        <strong>Change email</strong>
      </div>
    </div>
  </div>
</template>

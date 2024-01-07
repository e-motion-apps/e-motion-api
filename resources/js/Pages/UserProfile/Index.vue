<script setup>

import Nav from '@/Shared/Layout/Nav.vue'

defineProps({
    user: Object,
})
</script>

<template>
    <div class="flex h-screen flex-col">
        <Nav ref="nav" class="z-30" />
        <div class="mt-16 flex grow flex-col lg:flex-row">
            {{ user.name }}
            {{ user.email }}
        </div>
    </div>
</template>

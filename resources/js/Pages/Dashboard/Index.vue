<script setup>
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const page = usePage();

const cityCount = ref(page.props.cityCount);
const countryCount = ref(page.props.countryCount);

const props = defineProps({
  cityCount: Number,
  countryCount: Number,
})
</script>
<template>
    <div class="flex h-full min-h-screen flex-col md:flex-row">
        <AdminNavigation :url="page.url" />
    </div>
    <div>
        <p>{{ cityCount }} {{ countryCount }}</p>
    </div>
</template>
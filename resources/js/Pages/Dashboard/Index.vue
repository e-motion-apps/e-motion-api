<script setup>
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { usePage } from '@inertiajs/inertia-vue3'

const page = usePage();

const props = defineProps({
    userCount: Number,
    cityWithProviders: Number,
    cityCount: Number,
    countryCount: Number,
})
</script>
<template>
    <div class="flex h-full min-h-screen flex-col md:flex-row">
        <AdminNavigation :url="page.url" />
        <div class="flex w-full md:justify-end">
            <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
                <div class="m-4 flex flex-col lg:mx-8">
                    <h1 class="mb-3 text-lg font-bold text-gray-800">
                        <div>Users count: {{ props.userCount }}</div>
                        <div>Cities with providers: {{ props.cityCount }}</div>
                        <div>Countries with providers: {{ props.countryCount }}</div>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</template>
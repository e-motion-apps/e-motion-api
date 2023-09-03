<script setup>
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { __ } from '@/translate'

const page = usePage()

const props = defineProps({
    userCount: Number,
    cityWithProviders: Number,
    cityCount: Number,
    countryCount: Number,
    providersCount: Array,
})
</script>

<template>
    <div class="flex h-full min-h-screen flex-col md:flex-row">
        <AdminNavigation :url="page.url" />
        <div class="flex w-full md:justify-end">
            <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
                <div class="m-4 flex flex-col lg:mx-8">
                    <ul class="mt-8 flex w-full flex-col" role="list">
                        
                        <div class="grid grid-cols-3 gap-4">
                            <li class="group flex flex-col items-center cursor-pointer pb-4">
                                <span class="text-xl">{{ __('Users count') }}</span>
                                <span class="text-4xl font-black mt-2">{{ props.userCount }}</span>
                            </li>

                            <li class="group flex flex-col items-center cursor-pointer pb-4">
                                <span class="text-xl">{{ __('Cities with providers') }}</span>
                                <span class="text-4xl font-black mt-2">{{ props.cityCount }}</span>
                            </li>
                            <li class="group flex flex-col items-center cursor-pointer pb-4">
                                <span class="text-xl">{{ __('Countries with providers') }}</span>
                                <span class="text-4xl font-black mt-2">{{ props.countryCount }}</span>
                            </li>
                        </div>

                        <hr class="my-4 border-gray-300">

                        <span class="group flex flex-col items-center cursor-pointer pb-4 text-xl">{{ __('Number of cities where the provider is available') }}</span>
                        <ul class="grid grid-cols-3 gap-4">
                            <li v-for="(count, providerName) in props.providersCount" :key="providerName" class="group flex flex-col items-center cursor-pointer pb-6">
                                <span class="text-xl">{{ providerName }}</span>
                                <span class="text-4xl font-black mt-2">{{ count }}</span>
                            </li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

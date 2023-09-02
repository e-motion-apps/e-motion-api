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
    providersCount: Number,
})
</script>

<template>
    <div class="flex h-full min-h-screen flex-col md:flex-row">
        <AdminNavigation :url="page.url" />
        <div class="flex w-full md:justify-end">
            <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
                <div class="m-4 flex flex-col lg:mx-8">
                    <ul class="mt-8 flex w-full flex-col divide-y divide-gray-300" role="list">
                        <li
                            class="group flex cursor-pointer flex-col items-start justify-between gap-x-6 pb-1 pt-4 sm:flex-row sm:pb-4">
                            <span class="text-left">{{ __('Users count:') }}</span>
                            <span class="text-right">{{ props.userCount }}</span>
                        </li>
                        <li
                            class="group flex cursor-pointer flex-col items-start justify-between gap-x-6 pb-1 pt-4 sm:flex-row sm:pb-4">
                            <span class="text-left">{{ __('Cities with providers:') }}</span>
                            <span class="text-right">{{ props.cityCount }}</span>
                        </li>
                        <li
                            class="group flex cursor-pointer flex-col items-start justify-between gap-x-6 pb-1 pt-4 sm:flex-row sm:pb-4">
                            <span class="text-left">{{ __('Countries with providers:') }}</span>
                            <span class="text-right">{{ props.countryCount }}</span>
                        </li>
                        <span
                            class="text-left group flex cursor-pointer flex-col items-start justify-between gap-x-6 pb-1 pt-4 sm:pb-4">{{
                                __('Number of cities with individual providers') }}
                            <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                <li v-for="(count, providerName) in props.providersCount" :key="providerName"
                                    class="mb-2 flex h-8 w-fit shrink-0 cursor-pointer items-center justify-center rounded-md border border-zinc-300 p-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="bg-blue-500 w-8"
                                                :src="'/providers/' + providerName.toLowerCase() + '.png'" alt="">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                {{ providerName }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                {{ count }}
                                            </p>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                        </span>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

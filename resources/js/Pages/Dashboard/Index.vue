<script setup>
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { usePage } from '@inertiajs/inertia-vue3'

const page = usePage()

defineProps({
  usersCount: Number,
  citiesWithProvidersCount: Number,
  countriesWithCitiesWithProvidersCount: Number,
})
</script>

<template>
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <AdminNavigation :url="page.url" />
    <div class="flex w-full md:justify-end">
      <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
        <div class="p-2">
          <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
              <dt class="truncate text-sm font-medium text-blumilk-500">
                Users
              </dt>
              <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ usersCount }}
              </dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
              <dt class="truncate text-sm font-medium text-blumilk-500">
                Cities with providers
              </dt>
              <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ citiesWithProvidersCount }}
              </dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
              <dt class="truncate text-sm font-medium text-blumilk-500">
                Countries with providers
              </dt>
              <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                {{ countriesWithCitiesWithProvidersCount }}
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </div>
</template>

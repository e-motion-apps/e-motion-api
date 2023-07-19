<script setup>
import AdminNavigation from '../../Shared/Layout/AdminNavigation.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

defineProps({
  importInfo: Object,
})

const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' }

</script>

<template>
  <AdminNavigation :url="page.url" />
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <div class="flex w-full md:justify-end">
      <div class="mt-16 h-full w-full md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
        <div class="flex h-full w-full flex-col bg-blue-100 p-6">
          <p v-for="info in importInfo" :key="info.id" class="my-4 font-medium">
            {{ new Date(info.created_at).toLocaleDateString("pl-PL", options) }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

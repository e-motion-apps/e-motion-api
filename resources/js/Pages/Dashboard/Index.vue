<script setup>
import AdminNavigation from '../../Shared/Layout/AdminNavigation.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

defineProps({
  importInfo: Object,
  codes: Object,
  providers: Object,
})

const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' }

</script>

<template>
  <AdminNavigation :url="page.url" />
  <div class="flex h-full min-h-screen flex-col md:flex-row">
    <div class="flex w-full md:justify-end">
      <div class="mt-16 h-full w-full md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
        <div class="flex h-full w-full flex-col bg-blue-100 p-6">
          <div v-for="info in importInfo" :key="info.id" class="my-4 font-medium">
            {{ new Date(info.created_at).toLocaleDateString("pl-PL", options) }}
            -
            {{ info.who_runs_it }}


            <div v-for="detail in info.import_info_detail" :key="detail.id" class="flex font-light">
              <p v-for="provider in providers" :key="provider.id">
                <span v-if="detail.provider_id === provider.id" class="capitalize">
                  {{ provider.name }}: &nbsp;
                </span>
              </p>

              <div v-for="code in codes" :key="code.number">
                <span v-if="detail.code == code.number">
                  {{ code.description }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

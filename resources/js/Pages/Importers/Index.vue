<script setup>
import AdminNavigation from '@/Shared/Layout/AdminNavigation.vue'
import { router, usePage } from '@inertiajs/vue3'
import Import from '../../Shared/Components/Import.vue'
import PaginationInfo from '@/Shared/Components/PaginationInfo.vue'
import Pagination from '@/Shared/Components/Pagination.vue'
import { useToast } from 'vue-toastification'
import { __ } from '@/translate'

const page = usePage()
const toast = useToast()

defineProps({
  importInfo: Object,
  codes: Object,
  providers: Object,
})

function runImporters() {
  router.post('/run-importers', [])
  toast.success(__('Importers started.'))
}

function runRules() {
  router.get('/import-rules/0', [])
  toast.success(__('Rules import started.'))
}
</script>

<template>
  <AdminNavigation :url="page.url" />
  <div class="flex w-full md:justify-end">
    <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
      <div class="m-4 flex flex-col lg:mx-8">
        <div class="m-4">
          <button class="my-5 mr-10 w-fit rounded bg-blumilk-500 px-5 py-3 text-sm font-medium text-white shadow-md hover:bg-blumilk-400 md:py-2" @click="runImporters">
            {{ __('Run importers') }}
          </button>
          <button class="my-5 w-fit rounded bg-blumilk-500 px-5 py-3 text-sm font-medium text-white shadow-md hover:bg-blumilk-400 md:py-2" @click="runRules">
            {{ __('Run rules import') }}
          </button>
        </div>

        <PaginationInfo v-if="importInfo.data.length" :meta="importInfo.meta" />
        <div v-if="importInfo.data.length" class="rounded-lg ring-gray-300 sm:ring-1">
          <table class="min-w-full">
            <thead>
              <tr>
                <th scope="col" class="table-cell py-3.5 pl-2 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-5">
                  {{ __('Who') }}
                </th>
                <th scope="col" class="table-cell p-3.5 text-left text-sm font-semibold text-gray-900">
                  {{ __('When') }}
                </th>
                <th scope="col" class="table-cell py-3.5 pl-1 text-left text-sm font-semibold text-gray-900">
                  {{ __('Status') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <Import v-for="info in importInfo.data" :key="info.id" :info="info" :codes="codes" :providers="providers" />
            </tbody>
          </table>
        </div>
        <p v-else class="mt-6 flex text-lg font-medium text-gray-500">
          {{ __(`Didn't find anything. Just empty space.`) }}
        </p>

        <Pagination :meta="importInfo.meta" :links="importInfo.links" />
      </div>
    </div>
  </div>
</template>

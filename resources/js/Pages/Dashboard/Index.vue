<script setup>
import AdminNavigation from '../../Shared/Layout/AdminNavigation.vue'
import { usePage } from '@inertiajs/vue3'
import Import from './Components/Import.vue'
import PaginationInfo from '../../Shared/Components/PaginationInfo.vue'
import Pagination from '../../Shared/Components/Pagination.vue'

const page = usePage()

defineProps({
  importInfo: Object,
  codes: Object,
  providers: Object,
})

</script>

<template>
  <AdminNavigation :url="page.url" />
  <div class="flex w-full md:justify-end">
    <div class="mt-16 flex h-full w-full flex-col justify-between md:mt-0 md:w-2/3 lg:w-3/4 xl:w-5/6">
      <div class="m-4 flex flex-col lg:mx-8">
        <PaginationInfo v-if="importInfo.data.length" :meta="importInfo.meta" />
        <div class="rounded-lg ring-gray-300 sm:ring-1">
          <table class="min-w-full">
            <thead>
              <tr>
                <th scope="col" class="table-cell py-3.5 pl-2 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-5">
                  Who
                </th>
                <th scope="col" class="table-cell py-3.5 text-left text-sm font-semibold text-gray-900">
                  When
                </th>
                <th scope="col" class="table-cell py-3.5 text-left text-sm font-semibold text-gray-900">
                  Status
                </th>
              </tr>
            </thead>
            <tbody>
              <Import v-for="info in importInfo.data" :key="info.id" :info="info" :codes="codes" :providers="providers" />
            </tbody>
          </table>
        </div>
        <Pagination :meta="importInfo.meta" :links="importInfo.links" />
      </div>
    </div>
  </div>
</template>

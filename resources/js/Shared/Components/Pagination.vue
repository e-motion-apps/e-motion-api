<script setup>
const props = defineProps({
  meta: Object,
  links: Object,
})
</script>

<template>
  <div v-if="props.meta.last_page !== 1" class="mt-4 flex justify-end">
    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
      <InertiaLink v-if="props.links.prev" :href="props.links.prev" class="relative inline-flex items-center rounded-l-md p-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
        <span class="sr-only">Previous</span>
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
        </svg>
      </InertiaLink>

      <InertiaLink v-for="(link, index) in props.meta.links.slice(1, -1)" :key="index"
                   :href="link.url ? link.url : props.links.first"
                   :class="{'bg-blumilk-50': link.active}"
                   class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 lg:inline-flex"
      >
        {{ link.label }}
      </InertiaLink>

      <InertiaLink v-if="props.links.next" :href="props.links.next" class="relative inline-flex items-center rounded-r-md p-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:bg-gray-200">
        <span class="sr-only">Next</span>
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
        </svg>
      </InertiaLink>
    </nav>
  </div>
</template>

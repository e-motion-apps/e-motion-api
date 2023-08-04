<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
const locales = [
  {
    name: 'Polski',
    lang: 'pl',
    iso: 'pl',
  },
  {
    name: 'English',
    lang: 'en',
    iso: 'gb',
  },
]

const currentLocale = computed(() => usePage().props.locale)

</script>

<template>
  <div>
    <div class="flex space-x-2 pt-1.5">
      <InertiaLink v-for="locale in locales" :key="locale.lang" :href="`/language/${locale.lang}`" method="post" as="button"
                   :class="[currentLocale === locale.lang ? 'opacity-100' : 'opacity-50', 'large flat flags']"
                   :disabled="currentLocale === locale.lang"
      >
        <i :class="`${locale.iso} flat flag`" />
      </InertiaLink>
    </div>
  </div>
</template>

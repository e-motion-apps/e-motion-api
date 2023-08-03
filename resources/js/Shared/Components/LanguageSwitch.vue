<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const locales = [
  {
    name: 'Polski',
    lang: 'pl',
  },
  {
    name: 'English',
    lang: 'en',
  },
]

const page = usePage()

const currentLocale = ref(page.props.locale)

function setLocale(locale) {
  router.post(`/language/${locale}`)
}
</script>

<template>
  <div>
    <select v-model="currentLocale"
            class="block w-full rounded-lg border bg-none p-2.5 py-1.5 text-center align-middle text-sm sm:w-20"
            @change="setLocale(currentLocale)"
    >
      <option v-for="locale in locales" :key="locale.lang" :value="locale.lang">
        {{ locale.name }}
      </option>
    </select>
  </div>
</template>

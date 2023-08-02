<script setup>
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

const i18n = useI18n()

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

const setLocale = (locale) => {
  router.post(`/language/${locale}`, {}, {
    onSuccess() {
      i18n.locale.value = locale
    },
  })
}
</script>

<template>
  <div>
    <select v-model="$i18n.locale"
      class="block w-full rounded-lg border bg-none p-2.5 py-1.5 text-center align-middle text-sm sm:w-20"
      @change="setLocale($i18n.locale)">
      <option v-for="locale in locales" :key="locale.lang" :value="locale.lang">
        {{ locale.name }}
      </option>
    </select>
  </div>
</template>

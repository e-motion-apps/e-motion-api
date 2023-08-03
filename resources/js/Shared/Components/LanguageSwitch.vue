<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const locales = [
  {
    name: 'Polski',
    lang: 'pl',
    iso: 'pl'
  },
  {
    name: 'English',
    lang: 'en',
    iso: 'gb'
  },
]

const page = usePage()

const currentLocale = ref(page.props.locale)

function setLocale(locale) {
  if (currentLocale.value !== locale) {
    currentLocale.value = locale;
    router.post(`/language/${locale}`)
  }
}
</script>

<template>
  <div>
    <div class="flex space-x-2">
      <button v-for="locale in locales" :key="locale.lang" :class="{
        'opacity-100': currentLocale === locale.lang,
        'opacity-50': currentLocale !== locale.lang,
      }" :disabled="currentLocale === locale.lang" @click="setLocale(locale.lang)">
        <i :class="`${locale.iso} flat flag`"></i>
      </button>
    </div>
  </div>
</template>

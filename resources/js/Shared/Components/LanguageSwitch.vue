<script setup>
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/inertia-vue3'

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

const setLocale = (locale) => {
  router.post(`/language/${locale}`, {}, {
    onSuccess() {
      i18n.locale.value = locale
    },
  })
}
</script>

<template>
  {{ page.props.locale }}
  <div>
    <select v-model="page.props.locale"
            class="block w-full rounded-lg border bg-none p-2.5 py-1.5 text-center align-middle text-sm sm:w-20"
            @change="setLocale(page.props.locale)"
    >
      <option v-for="locale in locales" :key="locale.lang" :value="locale.lang">
        {{ locale.name }}
      </option>
    </select>
  </div>
</template>

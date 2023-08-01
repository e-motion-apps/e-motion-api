<script setup>

import { inject } from "vue"

const formkitConfig = inject(Symbol.for("FormKitConfig"))

const locales = [
  {
    name: "Polski",
    lang: "pl",
  },
  {
    name: "English",
    lang: "en",
  },
]

const setLocale = (locale) => {
  localStorage.setItem("locale", locale)
  formkitConfig.locale = locale
}
</script>

<template>
  <div>
    <select class="float-left w-full sm:w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      v-model="$i18n.locale"
      @change="setLocale($i18n.locale)"
    >
      <option
        v-for="locale in locales"
        :key="locale.lang"
        :value="locale.lang"
      >
        {{ locale.name }}
      </option>
    </select>
  </div>
</template>

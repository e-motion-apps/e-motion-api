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
    <select
      v-model="$i18n.locale"
      class="select rounded-full bg-blumilk-500 text-lg font-semibold text-white shadow-lg shadow-blumilk-50 hover:bg-blumilk-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:text-xl h-fit"
      @change="setLocale($i18n.locale)"
    >
      <option
        v-for="locale in locales"
        :key="locale.lang"
        class="border-bg-blumilk-500 text-white"
        :value="locale.lang"
      >
        {{ locale.name }}
      </option>
    </select>
  </div>
</template>

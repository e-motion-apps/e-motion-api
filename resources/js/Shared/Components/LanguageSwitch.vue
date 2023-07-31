<script setup>
import locales from "@/assets/lang/index.js"
import { inject } from "vue"

const formkitConfig = inject(Symbol.for("FormKitConfig"))

const setLocale = (locale) => {
  localStorage.setItem("locale", locale)
  formkitConfig.locale = locale
}
</script>

<template>
  <div>
    <select
      v-model="$i18n.locale"
      class="select w-fit text-sm sm:text-xs pl-2 pr-6 py-2 text-gray-50 bg-primary focus:bg-blue-900 hover:bg-blue-900 rounded-full overflow-hidden"
      @change="setLocale($i18n.locale)"
    >
      <option
        v-for="locale in locales"
        :key="locale.lang"
        class="border-slate-900"
        :value="locale.lang"
      >
        {{ locale.name }}
      </option>
    </select>
  </div>
</template>


<!-- locale is ctx=>etc. instead of name of country -->
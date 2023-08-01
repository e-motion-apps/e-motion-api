import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import { createI18n } from "vue-i18n";
import '../css/app.css'
import { createPinia } from 'pinia'
import messages from "@intlify/unplugin-vue-i18n/messages";
import {
  plugin as formkitPlugin,
  defaultConfig as formkitDefaultConfig,
} from "@formkit/vue"
import formkitConfig from "./formkit.config"

const getLocale = () => {
  return (
    localStorage.getItem("locale") || navigator.language.split("-")[0] || "pl"
  )
}

const pinia = createPinia()

createInertiaApp({
  progress: {
    delay: 0,
    color: '#14b8a6',
  },
  title: (title) => title ? `${title} - e-scooters` : 'e-scooters',
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })

    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const i18n = createI18n({
      legacy: false,
      locale: getLocale(),
      fallbackLocale: "pl",
      availableLocales: ["en", "pl"],
      messages: messages,
    })
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(pinia)
      .use(i18n)
      .use(
        formkitPlugin,
        formkitDefaultConfig({ ...formkitConfig, locale: getLocale() }),
      )
      .component('InertiaLink', Link)
      .component('InertiaHead', Head)
      .mount(el)
  },
})

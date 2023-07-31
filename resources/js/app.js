import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import { createI18n } from "vue-i18n";
import '../css/app.css'
import { createPinia } from 'pinia'
import messages from "@intlify/unplugin-vue-i18n/messages";

const i18n = createI18n({
  legacy: false,
  globalInjection: true,
  locale: "en",
  fallbackLocale: "en",
  availableLocales: ["en", "pl"],
  messages: messages,
});

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
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(pinia)
      .use(i18n)
      .component('InertiaLink', Link)
      .component('InertiaHead', Head)
      .mount(el)
  },
})

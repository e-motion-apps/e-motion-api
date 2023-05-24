import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import '../css/app.css'

createInertiaApp({
  progress: {
    delay: 50,
    color: '#14b8a6',
  },
  title: (title) => title ? `${title} - Escooters` : 'Escooters',
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })

    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('InertiaLink', Link)
      .component('InertiaHead', Head)
      .mount(el)
  },
})

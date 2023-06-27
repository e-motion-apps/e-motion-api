import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import '../css/app.css'
import App from './Shared/Layout/App.vue'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

createInertiaApp({
  progress: {
    delay: 0,
    color: '#527aba',
  },
  title: (title) => title ? `${title} - escooters` : 'escooters',
  resolve: (name) => {
    const page = resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    )

    page.then((module) => {
      module.default.layout = module.default.layout || App
    })

    return page
  },
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('InertiaLink', Link)
      .component('InertiaHead', Head)
      .mount(el)
  },
})

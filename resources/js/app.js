import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import '../css/app.css'
import { createPinia } from 'pinia'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

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
      .use(Toast, {
        position: 'bottom-right',
        timeout: 5000,
        closeOnClick: true,
        pauseOnFocusLoss: true,
        pauseOnHover: true,
        draggable: true,
        draggablePercent: 0.6,
        showCloseButtonOnHover: false,
        hideProgressBar: false,
        closeButton: false,
        icon: true,
        rtl: false,
      })
      .component('InertiaLink', Link)
      .component('InertiaHead', Head)
      .mount(el)
  },
})

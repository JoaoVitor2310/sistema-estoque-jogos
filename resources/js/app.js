import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

// Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';  // Importar CSS do Bootstrap
import 'bootstrap/dist/js/bootstrap.bundle.min.js'; // Importar JavaScript do Bootstrap

// Components
import Layout from './components/Layout.vue';

// PrimeVue
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import 'primeicons/primeicons.css'

const app = createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || Layout
    return page
  },
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .mixin({ methods: { route } })
      .use(PrimeVue, {
        theme: {
          preset: Aura
        }
      })
      .use(plugin)
      .mount(el)
  },
});

// app.config.globalProperties.$route = route;
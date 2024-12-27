import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'
import { md1 } from 'vuetify/blueprints'
import { Link, Head } from '@inertiajs/vue3';
import Btn from './Components/Btn.vue';
import Layout from './Layouts/Layout.vue';
import 'vue3-toastify/dist/index.css'

const vuetify = createVuetify({
  components,
  directives,
  blueprint: md1,
})


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        const page = pages[`./Pages/${name}.vue`].default;
    
        if (page.layout === undefined) {
          page.layout = Layout;
        }
    
        return page
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .component("Link", Link)
            .component("Head", Head)
            .component("Btn", Btn)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

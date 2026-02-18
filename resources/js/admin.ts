import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createI18n } from 'vue-i18n';
import { ZiggyVue } from 'ziggy-js';
import '../css/admin.css';

import en from './i18n/en/admin.json';
import id from './i18n/id/admin.json';

const i18n = createI18n({
    legacy: false,
    locale: document.documentElement.lang || 'en',
    fallbackLocale: 'en',
    messages: { en, id },
});

createInertiaApp({
    title: (title) => title ? `${title} - Admin` : 'Admin',
    resolve: (name) => resolvePageComponent(
        `./Pages/Admin/${name}.vue`,
        import.meta.glob('./Pages/Admin/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#3b82f6',
    },
});

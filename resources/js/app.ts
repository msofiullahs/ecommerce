import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createI18n } from 'vue-i18n';
import { createPinia } from 'pinia';
import { ZiggyVue } from 'ziggy-js';
import '../css/app.css';
import en from './i18n/en/frontend.json';
import id from './i18n/id/frontend.json';

const i18n = createI18n({
    legacy: false,
    locale: document.documentElement.lang || 'en',
    fallbackLocale: 'en',
    messages: { en, id },
});

createInertiaApp({
    title: (title) => title ? `${title} - My Store` : 'My Store',
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(i18n);
        app.use(createPinia());
        app.component('Link', Link);
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

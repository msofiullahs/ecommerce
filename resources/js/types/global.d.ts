import { route as ziggyRoute } from 'ziggy-js';

declare global {
    function route(name: string, params?: Record<string, any>, absolute?: boolean): string;
}

declare module '@inertiajs/vue3' {
    interface PageProps {
        auth: {
            user: import('./index').User | null;
        };
        locale: string;
        locales: string[];
        flash: import('./index').FlashMessages;
        ziggy: {
            url: string;
            port: number | null;
            defaults: Record<string, any>;
            routes: Record<string, any>;
            location: string;
        };
    }
}

declare module '*.vue' {
    import type { DefineComponent } from 'vue';
    const component: DefineComponent<{}, {}, any>;
    export default component;
}

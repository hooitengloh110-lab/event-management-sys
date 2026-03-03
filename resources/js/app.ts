import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

type PageModule = {
    default: DefineComponent & {
        layout?: DefineComponent | undefined;
    };
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        // const page = resolvePageComponent(
        //     `./Pages/${name}.vue`,
        //     import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        // );

        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true }) as Record<string, any>
        const page = pages[`./Pages/${name}.vue`]

        // if (page && typeof page === 'object' && 'default' in page) {
        //     if(page.default.layout !== undefined) {
        //         return page;
        //     }

        //     if(name.startsWith('Dashboard/') || name.startsWith('Admin/')) {
        //         page.default.layout = DashboardLayout;
        //     } else if(name.startsWith('Auth/')) {
        //         page.default.layout = undefined;
        //     } else {
        //         page.default.layout = MainLayout;
        //     }
        // }
        // console.log("Check : ", name, page, Object.keys(pages));

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, Head } from "@inertiajs/vue3";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import DefaultLayout from "./Layouts/DefaultLayout.vue";

createInertiaApp({
    title: (title) => `My app ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = page.default.layout || DefaultLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(ZiggyVue)
            .component("Head", Head)
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: "#fff",
        includeCSS: true,
        showSpinner: true,
    },
});

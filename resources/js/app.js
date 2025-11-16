import 'flowbite';
import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Font Awesome Library Imports
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faEdit, faTrash, faTimes, faMagnifyingGlass, faPen, faPlus, faImage } from '@fortawesome/free-solid-svg-icons';

// Adding icons to library
library.add(faEdit, faTrash, faTimes, faMagnifyingGlass, faPen, faPlus, faImage);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        vueApp.use(plugin);
        vueApp.use(ZiggyVue);
        vueApp.component('font-awesome-icon', FontAwesomeIcon); // Register globally
        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

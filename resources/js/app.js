import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler.js';

import home from './components/Home.vue';

const app = createApp({});

// app.component('home', home);
app.mount('#app');
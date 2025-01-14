import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';

// import home from './components/home.vue';

//importaciones client
import ClientProfile from './components/client/show/ClientProfile.vue';
import bitacoras from './components/client/show/Bitacoras.vue';
import expedientes from './components/client/show/Expedientes.vue';
import vista from './components/client/show/Vista.vue';
import edicion from './components/client/show/edicion.vue';

const app = createApp({});

// app.component('home', home);

//app client
app.component('client-profile', ClientProfile);
app.component('bitacoras', bitacoras);
app.component('expedientes', expedientes);
app.component('vista-cliente', vista);
app.component('edicion', edicion);
app.mount('#app');


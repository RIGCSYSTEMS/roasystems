import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';

// import home from './components/home.vue';

//layouts
import bitacoras from './layouts/Bitacoras.vue';
import expedientes from './layouts/Expedientes.vue';
import infoSection from './layouts/infoSection.vue';


//importaciones client
import ClientProfile from './components/client/show/ClientProfile.vue';
import vista from './components/client/show/Vista.vue';
import edicion from './components/client/show/edicion.vue';

const app = createApp({});

// app.component('home', home);

//layouts
app.component('bitacoras', bitacoras);
app.component('expedientes', expedientes);
app.component('infoSection', infoSection);


//app client
app.component('client-profile', ClientProfile);
app.component('vista-cliente', vista);
app.component('edicion', edicion);
app.mount('#app');


import './bootstrap';
import '../css/app.css';
import axios from 'axios';

window.axios = axios;

import { createApp } from 'vue';

import home from './components/home.vue';

//login
import LoginForm from './components/auth/LoginForm.vue';

//layouts
import bitacoras from '@/layouts/Bitacoras.vue';
import expedientes from '@/layouts/Expedientes.vue';
import infoSection from '@/layouts/infoSection.vue';


//importaciones client
import ClientProfile from './components/client/ClientProfile.vue';
import vista from './components/client/Vista.vue';
import edicion from './components/client/edicion.vue';
import creacion from './components/client/ClientCreacion.vue';

//importaciones documentos client
import documentolist from './components/client/DocumentoList.vue';
import documentoview from './components/client/DocumentoView.vue';
import documentoedit from './components/client/DocumentoEdit.vue';
import documentocreate from './components/client/DocumentoCreate.vue';
import DocumentoIndex from './components/client/DocumentoIndex.vue';

//importaciones expedientes
import Expedientes from './components/expedients/Expedientes.vue';
import ExpedientesEdicion from './components/expedients/ExpedientesEdicion.vue';
import AudienciasExpediente from './components/expedients/AudienciasExpediente.vue';
import BitacoraExpediente from './components/expedients/BitacoraExpediente.vue';
import HonorariosExpediente from './components/expedients/HonorariosExpediente.vue';
import LineaTiempoExpediente from './components/expedients/LineaTiempoExpediente.vue';


const app = createApp({});

app.component('home', home);

//login
app.component('login-form', LoginForm);

//layouts
app.component('bitacoras', bitacoras);
app.component('expedientes', expedientes);
app.component('infoSection', infoSection);


//app client
app.component('client-profile', ClientProfile);
app.component('vista-cliente', vista);
app.component('edicion', edicion);
app.component('creacion', creacion);

//app documentos client
app.component('documento-list', documentolist);
app.component('documento-viewer', documentoview);
app.component('documento-edit', documentoedit);
app.component('documento-create', documentocreate);
app.component('documento-index', DocumentoIndex);

//app expedientes
app.component('expediente-index', Expedientes);
app.component('expediente-Edicion', ExpedientesEdicion);
app.component('audiencias-expediente', AudienciasExpediente);
app.component('bitacora-expediente', BitacoraExpediente);
app.component('honorarios-expediente', HonorariosExpediente);
app.component('linea-tiempo-expediente', LineaTiempoExpediente);

app.mount('#app');

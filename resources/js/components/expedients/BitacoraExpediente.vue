<!-- resources/js/components/BitacoraExpediente.vue -->
<template>
    <div class="bitacora">
      <h3>Bitácora</h3>
      <ul class="list-group">
        <li v-for="(entrada, index) in expediente.bitacoras" :key="index" class="list-group-item">
          <strong>{{ entrada.fecha_y_hora_del_evento }}</strong>
          <p>{{ entrada.descripcion }}</p>
          <small>Categoría: {{ entrada.categoria }}</small>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      expediente: {
        type: Object,
        required: true
      }
    }
  }
  </script>
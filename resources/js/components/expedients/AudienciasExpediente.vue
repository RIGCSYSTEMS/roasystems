<!-- resources/js/components/AudienciasExpediente.vue -->
<template>
    <div class="audiencias">
      <h3>Audiencias</h3>
      <ul class="list-group">
        <li v-for="(audiencia, index) in expediente.audiencias" :key="index" class="list-group-item">
          <strong>{{ audiencia.fecha_hora }}</strong>
          <p>Tipo: {{ audiencia.tipo_audiencia }}</p>
          <p>Lugar: {{ audiencia.lugar }}</p>
          <p>Estado: {{ audiencia.estado }}</p>
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
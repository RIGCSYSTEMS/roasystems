<!-- resources/js/components/LineaTiempoExpediente.vue -->
<template>
    <div class="linea-tiempo">
      <h3>Línea de Tiempo del Expediente</h3>
      <div class="timeline">
        <div v-for="(etapa, index) in expediente.etapas" :key="index" class="timeline-item">
          <div class="timeline-badge" :class="{ 'completed': etapa.completada }"></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">{{ etapa.nombre }}</h4>
              <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{ etapa.fecha }}</small></p>
            </div>
            <div class="timeline-body">
              <p>{{ etapa.descripcion }}</p>
            </div>
          </div>
        </div>
      </div>
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
  
  <style scoped>
  .timeline {
    position: relative;
    padding: 20px 0;
  }
  .timeline-item {
    position: relative;
    margin-bottom: 20px;
  }
  .timeline-badge {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #ddd;
    position: absolute;
    left: -10px;
  }
  .timeline-badge.completed {
    background-color: #28a745;
  }
  .timeline-panel {
    margin-left: 30px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
  }
  </style>
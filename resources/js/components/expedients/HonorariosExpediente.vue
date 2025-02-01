<!-- resources/js/components/HonorariosExpediente.vue -->
<template>
    <div class="honorarios">
      <h3>Honorarios</h3>
      <div v-if="expediente.honorarios">
        <p>Monto total: ${{ expediente.honorarios.monto_total_expediente }}</p>
        <p>Monto adicional: ${{ expediente.honorarios.monto_adicional }}</p>
        <p>Total abonos: ${{ expediente.honorarios.total_abonos }}</p>
        <p>Saldo pendiente: ${{ expediente.honorarios.saldo_pendiente }}</p>
      </div>
      <div v-else>
        <p>No hay información de honorarios disponible.</p>
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
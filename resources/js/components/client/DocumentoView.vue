<template>
  <div class="modal" tabindex="-1" style="display: block;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ documento.nombre }}</h5>
          <button type="button" class="btn-close" @click="$emit('cerrar-vista')"></button>
        </div>
        <div class="modal-body">
          <p><strong>Tipo:</strong> {{ documento.nombre }}</p>
          <p><strong>Formato:</strong> {{ documento.formato }}</p>
          <p><strong>Fecha de subida:</strong> {{ formatDate(documento.created_at) }}</p>
          
          <div v-if="documento.formato === 'PDF'" class="ratio ratio-16x9">
            <iframe :src="'/documentos/' + documento.id + '/visualizar'" width="100%" height="600px" style="border: none;"></iframe>
          </div>
          <img v-else-if="documento.formato === 'IMAGEN'" :src="'/documentos/' + documento.id + '/visualizar'" :alt="documento.nombre" class="img-fluid">
          
          <div class="mt-3">
            <h6>Observaciones:</h6>
            <p>{{ documento.observaciones }}</p>
          </div>
        </div>
        <div class="modal-footer">
          <a :href="'/documentos/' + documento.id + '/descargar'" class="btn btn-primary">Descargar</a>
          <button type="button" class="btn btn-secondary" @click="$emit('cerrar-vista')">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    documento: {
      type: Object,
      required: true
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    }
  }
}
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
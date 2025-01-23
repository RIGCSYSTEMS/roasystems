<template>
  <div class="mt-4">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h2 class="card-title mb-0">
          <i class="bi bi-files me-2"></i>Lista de Documentos
        </h2>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">
                  <i class="bi bi-file-text me-2"></i>Nombre
                </th>
                <th scope="col">
                  <i class="bi bi-tag me-2"></i>Tipo
                </th>
                <th scope="col">
                  <i class="bi bi-calendar me-2"></i>Fecha de subida
                </th>
                <th scope="col">
                  <i class="bi bi-gear me-2"></i>Acciones
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="documento in documentos" :key="documento.id">
                <td>{{ documento.nombre_de_documento }}</td>
                <td>{{ documento.tipo_de_documento }}</td>
                <td>{{ formatDate(documento.created_at) }}</td>
                <td>
                  <div class="btn-group">
                    <button @click="$emit('ver-documento', documento)" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-eye me-1"></i> Ver
                    </button>
                    <button @click="$emit('editar-documento', documento)" class="btn btn-outline-warning btn-sm">
                      <i class="bi bi-pencil me-1"></i> Editar
                    </button>
                    <button @click="$emit('eliminar-documento', documento.id)" class="btn btn-outline-danger btn-sm">
                      <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    documentos: {
      type: Array,
      required: true
    }
  },
  methods: {
    formatDate(date) {
      const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      };
      return new Date(date).toLocaleDateString('es-ES', options);
    }
  }
}
</script>

<style scoped>
.btn-group .btn {
  margin: 0 2px;
}
.card {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>
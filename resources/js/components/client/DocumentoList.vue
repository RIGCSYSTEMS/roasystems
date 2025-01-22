<template>
  <div class="mt-4 card custom-card">
    <div class="card-header bg-gradient">
      <h2 class="card-title mb-0 text-white">
        <i class="bi bi-files me-2"></i>Lista de Documentos
      </h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover custom-table">
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
      return new Date(date).toLocaleString();
    }
  }
}
</script>

<style scoped>
.custom-card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 2px 15px rgba(182, 14, 14, 0.08);
  overflow: hidden;
  margin-bottom: 2rem;
}

.bg-gradient {
  background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
  padding: 1.5rem;
}

.custom-table {
  margin-bottom: 0;
}

.custom-table thead th {
  background-color: #f8f9fa;
  border-bottom: 2px solid #000000;
  color: #6c757d;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  padding: 1rem;
}

.custom-table tbody td {
  padding: 1rem;
  vertical-align: middle;
}

.btn-group .btn {
  border-radius: 8px;
  margin: 0 3px;
  padding: 0.375rem 1rem;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
}

.btn-group .btn:hover {
  transform: translateY(-2px);
}

.card-title {
  font-size: 1.25rem;
  font-weight: 600;
}

.table > :not(caption) > * > * {
  padding: 1rem;
}

.bi {
  font-size: 1.1rem;
}
</style>
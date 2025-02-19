<template>
  <div class="mt-4">
    <div class="card">
      <div class="card-header text-white">
        <h2 class="card-title mb-0">
          <i class="bi bi-files me-2"></i>Lista de Documentos
        </h2>
      </div>
      <div class="card-body">
        <div v-if="documentos.length === 0" class="empty-state">
          <i class="bi bi-folder2-open display-4 text-muted"></i>
          <p class="text-muted mt-3">No hay documentos disponibles</p>
        </div>
        
        <div v-else class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">
                  <i class="bi bi-tag me-2"></i>Nombre
                </th>
                <th scope="col">
                  <i class="bi bi-tag me-2"></i>Tipo
                </th>
                <th scope="col">
                  <i class="bi bi-calendar me-2"></i>Fecha de subida
                </th>
                <th scope="col">
                  <i class="bi bi-check-circle me-2"></i>Estado
                </th>
                <th scope="col">
                  <i class="bi bi-gear me-2"></i>Acciones
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="documento in documentos" :key="documento.id">
                <td>{{ documento.nombre }}</td>
                <td>{{ documento.tipo_documento_expediente}}</td>
                <td>{{ formatDate(documento.created_at) }}</td>
                <td>
                  <span :class="getEstadoClass(documento.estado)">
                    {{ documento.estado }}
                  </span>
                </td>
                <td>
                  <div class="btn-group">
                    <button @click="$emit('ver-documento', documento)" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-eye me-1"></i> Ver
                    </button>
                    <button 
                      v-if="puedeEditarDocumento(documento)" 
                      @click="$emit('editar-documento', documento)" 
                      class="btn btn-outline-warning btn-sm"
                    >
                      <i class="bi bi-pencil me-1"></i> Editar
                    </button>
                    <button 
                      v-if="puedeEliminarDocumento(documento)" 
                      @click="$emit('eliminar-documento', documento.id)" 
                      class="btn btn-outline-danger btn-sm"
                    >
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
    },
    userRole: {
      type: String,
      required: true
    },
    expediente: {
      type: Object,
      required: true
      },
    expedienteStatus: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      userRole: null,
      expediente: null
    }
  },
  mounted() {
    this.getUserRole();
  },
  emits: ['ver-documento', 'editar-documento', 'eliminar-documento', 'cancelar-edicion'],
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
    },
    getEstadoClass(estado) {
      const clases = {
        'pendiente': 'text-info',
        'aceptado': 'text-success',
        'rechazado': 'text-danger'
      };
      return clases[estado] || 'text-info';
    },
    getUserRole() {
      axios.get('/user/role').then(response => {
        this.userRole = response.data.role;
      });
    },
    puedeEditarDocumento(documento) {
      return this.expedienteStatus !== 'Cerrado' || 
      ['DIRECTOR', 'ADMIN'].includes(this.userRole);
      },

    // Se oculta el boton depende el estado del documente
    //   if (['ADMIN', 'DIRECTOR', 'ABOGADO'].includes(this.userRole)) {
    //     return true;
    //   }
    //   return this.userRole === 'CLIENTE' && documento.estado !== 'Aceptado' ;
    // },
    puedeEliminarDocumento(documento) {
      return this.puedeEditarDocumento(documento);
    }
  }
}
</script>

<style scoped>
.card-header {
  background: linear-gradient(135deg, #3b0866 0%, #964ad4 100%);
  padding: 1.5rem;
  border-radius: 15px;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-group .btn {
  margin: 0 2px;
}
.card {
  width: 100%;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  min-height: 200px; /* Altura mínima cuando no hay documentos */
  background-color: #f8f9fa;
  border-radius: 8px;
}

.empty-state i {
  color: #6c757d;
}

.empty-state p {
  font-size: 1.1rem;
  margin-bottom: 0;
}

.card-body {
  min-height: auto; /* Elimina cualquier altura mínima fija */
  max-height: calc(100vh - 300px); /* Altura máxima adaptativa */
  overflow-y: auto;
}

.table-responsive {
  margin-bottom: 0; /* Elimina el margen inferior extra */
}
</style>
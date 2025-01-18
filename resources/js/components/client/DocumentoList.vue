<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h2 class="mb-0">Documentos de {{ clientName }}</h2>
      </div>
      <div class="card-body">
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
          <p class="mt-2">Cargando documentos...</p>
        </div>
        
        <div v-else-if="error" class="alert alert-danger" role="alert">
          {{ error }}
          <button @click="cargarDocumentos" class="btn btn-link">Intentar nuevamente</button>
        </div>

        <div v-else-if="documentos.length === 0" class="text-center py-4">
          <p class="text-muted">No hay documentos disponibles</p>
        </div>

        <div v-else class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Formato</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="documento in documentos" :key="documento.id">
                <td>{{ documento.nombre_de_documento }}</td>
                <td>
                  <span :class="{'badge bg-primary': documento.formato === 'PDF', 'badge bg-success': documento.formato === 'IMAGEN'}">
                    {{ documento.formato }}
                  </span>
                </td>
                <td>
                  <span :class="getEstadoClass(documento.estado)">
                    {{ documento.estado }}
                  </span>
                </td>
                <td>{{ formatDate(documento.created_at) }}</td>
                <td>
                  <div class="btn-group">
                    <a :href="formatDocumentPath(documento.ruta)" 
                       target="_blank" 
                       class="btn btn-sm btn-primary">
                      Ver
                    </a>
                    <button @click="eliminarDocumento(documento.id)" 
                            class="btn btn-sm btn-danger">
                      Eliminar
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
  name: 'DocumentoList',
  props: {
    clientId: {
      type: Number,
      required: true
    },
    clientName: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      documentos: [],
      loading: true,
      error: null
    }
  },
  mounted() {
    this.cargarDocumentos()
  },
  methods: {
 formatDocumentPath(path) {
    // Asegurarse de eliminar duplicados y barras redundantes
    return path.replace(/\/+/g, '/').replace('/storage/storage/', '/storage/');
},

async cargarDocumentos() {
  this.loading = true;
  this.error = null;

  try {
    const response = await fetch(`/client/${this.clientId}/documentos`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();

    // Aquí verificas los documentos cargados
    console.log('Documentos cargados desde el backend:', data);

    this.documentos = data;
  } catch (error) {
    console.error('Error al cargar documentos:', error);
    this.error = 'Error al cargar los documentos. Por favor, intente nuevamente.';
  } finally {
    this.loading = false;
  }
},
    
    async eliminarDocumento(id) {
      if (!confirm('¿Está seguro de que desea eliminar este documento?')) {
        return
      }

      try {
        const response = await fetch(`/documentos/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })

        if (!response.ok) {
          throw new Error('Error al eliminar el documento')
        }

        this.documentos = this.documentos.filter(doc => doc.id !== id)
        alert('Documento eliminado correctamente')
      } catch (error) {
        console.error('Error:', error)
        alert('Error al eliminar el documento')
      }
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },

    getEstadoClass(estado) {
      const classes = {
        'Pendiente': 'badge bg-warning',
        'En Revisión': 'badge bg-info',
        'Aceptado': 'badge bg-success',
        'Rechazado': 'badge bg-danger',
        'Obsoleto': 'badge bg-secondary'
      }
      return classes[estado] || 'badge bg-primary'
    }
  }
}
</script>

<style scoped>
.card {
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.badge {
  font-size: 0.85em;
  padding: 0.35em 0.65em;
}

.table {
  margin-bottom: 0;
}

.btn-group {
  gap: 0.5rem;
}

.spinner-border {
  width: 3rem;
  height: 3rem;
}
</style>
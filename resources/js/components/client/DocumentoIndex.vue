<template>
  <div class="container-fluid py-4">
    <div class="custom-header mb-4">
      <h1 class="text-white mb-0">
        <i class="bi bi-folder me-2"></i>
        Documentos de {{ clientName }}
      </h1>
    </div>
    
    <div class="row g-4">
      <div class="col-md-6">
        <documento-create 
          :client-id="clientId"
          @documento-creado="cargarDocumentos"
        ></documento-create>
      </div>
      
      <div class="col-md-6">
        <documento-list 
          :documentos="documentos" 
          @editar-documento="editarDocumento"
          @eliminar-documento="eliminarDocumento"
          @ver-documento="verDocumento"
        ></documento-list>
      </div>
    </div>
    
    <documento-edit 
      v-if="documentoEditando"
      :documento="documentoEditando"
      @documento-actualizado="documentoActualizado"
      @cancelar-edicion="cancelarEdicion"
    ></documento-edit>
    
    <documento-viewer
      v-if="documentoVisualizando"
      :documento="documentoVisualizando"
      @cerrar-vista="cerrarVistaDocumento"
    ></documento-viewer>
  </div>
</template>

<script>
export default {
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
      documentoEditando: null,
      documentoVisualizando: null
    }
  },
  mounted() {
    this.cargarDocumentos();
  },
  methods: {
    cargarDocumentos() {
      axios.get(`/client/${this.clientId}/documentos/list`)
        .then(response => {
          this.documentos = response.data;
        })
        .catch(error => {
          console.error('Error al cargar documentos:', error);
        });
    },
    editarDocumento(documento) {
      this.documentoEditando = documento;
    },
    eliminarDocumento(id) {
      if (confirm('¿Está seguro de eliminar este documento?')) {
        axios.delete(`/documentos/${id}`)
          .then(() => {
            this.cargarDocumentos();
          })
          .catch(error => {
            console.error('Error al eliminar documento:', error);
          });
      }
    },
    documentoActualizado() {
      this.cargarDocumentos();
      this.documentoEditando = null;
    },
    cancelarEdicion() {
      this.documentoEditando = null;
    },
    verDocumento(documento) {
      this.documentoVisualizando = documento;
    },
    cerrarVistaDocumento() {
      this.documentoVisualizando = null;
    }
  }
}
</script>

<style scoped>
.custom-header {
  background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
  padding: 2rem;
  border-radius: 15px;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.container-fluid {
  background-color: #f8f9fa;
  min-height: 100vh;
}

h1 {
  font-size: 2rem;
  font-weight: 600;
}

.row {
  margin-right: -0.5rem;
  margin-left: -0.5rem;
}

.col-md-6 {
  padding-right: 0.5rem;
  padding-left: 0.5rem;
}

.bi {
  font-size: 1.5rem;
}
</style>
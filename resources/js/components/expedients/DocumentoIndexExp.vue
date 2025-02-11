<template>
  <div class="container-fluid py-4">
    <div class="custom-header mb-4">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0">
          <i class="bi bi-folder me-2"></i>
          Documentos de {{ clientName }}
        </h1>
        <div>
          <!-- Botón "Volver al Cliente" -->
          <a :href="`/expedientes/${expedienteId}`" class="btn btn-light me-2">
            <i class="bi bi-person me-2"></i>Volver al Cliente
          </a>
          <!-- Botón "Lista de Clientes", controlado por el rol -->
          <a 
            v-if="userRole !== 'CLIENTE'" 
            href="/searchExpedient" 
            class="btn btn-light">
            <i class="bi bi-people me-2"></i>Lista de Clientes
          </a>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <documento-create-exp 
          :expediente-id="expedienteId"
          @documento-creado="cargarDocumentos"
        ></documento-create-exp>
      </div>
      
      <div class="col-md-6">
        <documento-list-exp 
          :documentos="documentos" 
          @editar-documento="editarDocumento"
          @eliminar-documento="eliminarDocumento"
          @ver-documento="verDocumento"
        ></documento-list-exp>
      </div>
    </div>
    
    <documento-edit-exp 
      v-if="documentoEditando"
      :documento="documentoEditando"
      @documento-actualizado="documentoActualizado"
      @cancelar-edicion="cancelarEdicion"
    ></documento-edit-exp>
    
    <documento-viewer-exp
      v-if="documentoVisualizando"
      :documento="documentoVisualizando"
      @cerrar-vista="cerrarVistaDocumento"
      @estado-actualizado="actualizarEstadoDocumento"
    ></documento-viewer-exp>
  </div>
</template>

<script>
export default {
  props: {
    expedienteId: {
      type: Number,
      required: true
    },
    expedienteName: {
      type: String,
      required: true
    },
    userRole: { // Recibe el rol del usuario como una prop
      type: String,
      required: true
    }
  },
  data() {
    return {
      documentos: [],
      documentoEditando: null,
      documentoVisualizando: null
    };
  },
  mounted() {
    
    this.cargarDocumentos();
  },
  methods: {
    cargarDocumentos() {
      axios.get(`/expedientes/${this.expedienteId}/documentos/list`)
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
        axios.delete(`/documentosexp/${id}`)
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
    },
    actualizarEstadoDocumento(documentoActualizado) {
      const index = this.documentos.findIndex(doc => doc.id === documentoActualizado.id);
      if (index !== -1) {
        this.documentos[index].estado = documentoActualizado.estado;
      }
    }
  }
};
</script>

<style scoped>
.custom-header {
  background: linear-gradient(135deg, #3b0866 0%, #964ad4 100%);
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

.btn-light {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.1);
  color: white;
  transition: all 0.3s ease;
}

.btn-light:hover {
  background-color: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
}
</style>

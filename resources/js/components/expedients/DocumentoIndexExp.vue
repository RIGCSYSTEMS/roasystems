<template>
  <div class="container-fluid py-4">
    <div class="custom-header mb-4">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0">
          <i class="bi bi-folder me-2"></i>
          Documentos de Admision: {{ expediente.numero_de_dossier }}
        </h1>
        <!-- Botón para abrir el modal -->
        <button @click="$emit('abrir-modal-crear')" class="btn btn-light">
          <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Documento
        </button>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-12">
        <documento-list-exp 
          :documentos="documentos"
          :expediente="expediente"
          :user-role="userRole"
          :expediente-name="expedienteName" 
          :expedienteStatus="expediente.estatus_del_expediente"
          @editar-documento="editarDocumento"
          @eliminar-documento="eliminarDocumento"
          @ver-documento="verDocumento"
          @cancelar-edicion="cancelarEdicion"
        ></documento-list-exp>
      </div>
    </div>
    

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
    userRole: {
      type: String,
      required: true
    },
    expediente: {
      type: Object,
      required: true
    }
  },
  
  data() {
    return {
      documentos: [],
      documentoEditando: null,
      documentoVisualizando: null,
      showCreateModal: false,
      showEditModal: false,
      showViewModal: false
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
      
      console.log('DocumentoIndexExp - Recibido documento para ver:', documento);
      this.$emit('editar-documento', documento);
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
    verDocumento(documento) {
      console.log('DocumentoIndexExp - Recibido documento para ver:', documento);
      this.$emit('ver-documento', documento);
    },
    cerrarVistaDocumento() {
      this.documentoVisualizando = null;
    },
    actualizarEstadoDocumento(documentoActualizado) {
      const index = this.documentos.findIndex(doc => doc.id === documentoActualizado.id);
      if (index !== -1) {
        this.documentos[index].estado = documentoActualizado.estado;
      }
    },
    documentoCreado() {
      this.cargarDocumentos();
      this.showCreateModal = false;
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
  height: auto;
  min-height: 0;
  display: flex;
  flex-direction: column;
  overflow: auto; /* Permite scroll si el contenido es muy largo */
}

h1 {
  font-size: 2rem;
  font-weight: 600;
}

.row {
  margin-right: -0.5rem;
  margin-left: -0.5rem;
}

.col-md-12 {
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

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.modal-container {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1001;
  max-width: 90%;
  max-height: 90%;
  overflow-y: auto;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
}

.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s;
}

.modal-enter, .modal-leave-to {
  opacity: 0;
}
</style>
<template>
  <div class="container">
    <h1 class="mb-4">Documentos de {{ clientName }}</h1>
    
    <documento-create 
      :client-id="clientId"
      @documento-creado="cargarDocumentos"
    ></documento-create>
    
    <documento-list 
      :documentos="documentos" 
      @editar-documento="editarDocumento"
      @eliminar-documento="eliminarDocumento"
      @ver-documento="verDocumento"
    ></documento-list>
    
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
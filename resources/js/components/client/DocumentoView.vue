<template>
  <div class="modal" tabindex="-1" style="display: block;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content custom-modal">
        <div class="modal-header bg-gradient">
          <h5 class="modal-title text-white">
            <i class="bi bi-file-text me-2"></i>{{ documento.nombre }}
          </h5>
          <button type="button" class="btn-close btn-close-white" @click="$emit('cerrar-vista')"></button>
        </div>
        <div class="modal-body">
          <div class="info-grid">
            <div class="info-item">
              <i class="bi bi-tag text-primary"></i>
              <strong>Tipo:</strong> {{ documento.nombre }}
            </div>
            <div class="info-item">
              <i class="bi bi-file-earmark text-primary"></i>
              <strong>Formato:</strong> {{ documento.formato }}
            </div>
            <div class="info-item">
              <i class="bi bi-calendar text-primary"></i>
              <strong>Fecha de subida:</strong> {{ formatDate(documento.created_at) }}
            </div>
          </div>
          
          <div class="document-preview mt-4">
            <div v-if="documento.formato === 'PDF'" class="ratio ratio-16x9">
              <iframe 
                :src="'/documentos/' + documento.id + '/visualizar'" 
                class="rounded"
                style="border: none;"
              ></iframe>
            </div>
            <img 
              v-else-if="documento.formato === 'IMAGEN'" 
              :src="'/documentos/' + documento.id + '/visualizar'" 
              :alt="documento.nombre" 
              class="img-fluid rounded"
            >
          </div>
          
          <div class="mt-4">
            <h6 class="mb-2">
              <i class="bi bi-chat-text me-2"></i>Observaciones:
            </h6>
            <p class="bg-light p-3 rounded">{{ documento.observaciones }}</p>
          </div>

          <div class="mt-4">
            <h6 class="mb-2">
              <i class="bi bi-check-circle me-2"></i>Estado del Documento:
            </h6>
            <select 
              v-if="puedeValidarDocumentos"
              v-model="documentoEstado" 
              class="form-select" 
              @change="actualizarEstado"
            >
              <option value="Pendiente">Pendiente</option>
              <option value="En Revisión">En Revisión</option>
              <option value="Aceptado">Aceptado</option>
              <option value="Rechazado">Rechazado</option>
              <option value="Obsoleto">Obsoleto</option>
            </select>
            <p v-else class="form-control-plaintext">{{ documentoEstado }}</p>
          </div>
        </div>
        <div class="modal-footer">
          <a 
            :href="'/documentos/' + documento.id + '/descargar'" 
            class="btn btn-primary"
          >
            <i class="bi bi-download me-2"></i>Descargar
          </a>
          <button 
            type="button" 
            class="btn btn-secondary" 
            @click="$emit('cerrar-vista')"
          >
            <i class="bi bi-x-circle me-2"></i>Cerrar
          </button>
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
  data() {
    return {
      documentoEstado: this.documento.estado,
      userRole: null
    }
  },
  computed: {
    puedeValidarDocumentos() {
      return ['ADMIN', 'DIRECTOR', 'ABOGADO'].includes(this.userRole);
    }
  },
  mounted() {
    this.getUserRole();
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
    getUserRole() {
      axios.get('/user/role').then(response => {
        this.userRole = response.data.role;
      });
    },
    actualizarEstado() {
      if (!this.puedeValidarDocumentos) return;
      
      axios.put(`/documentos/${this.documento.id}/estado`, {
        estado: this.documentoEstado
      })
      .then(response => {
        this.$emit('estado-actualizado', {
          id: this.documento.id,
          estado: this.documentoEstado
        });
      })
      .catch(error => {
        console.error('Error al actualizar el estado:', error);
        // Revertir el cambio si hay error
        this.documentoEstado = this.documento.estado;
      });
    }
  }
}
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}

.custom-modal {
  border: none;
  border-radius: 15px;
  overflow: hidden;
}

.bg-gradient {
  background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
  padding: 1rem 1.5rem;
}

.modal-title {
  font-weight: 600;
  font-size: 1.25rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem;
  background-color: #f8f9fa;
  border-radius: 8px;
}

.info-item i {
  font-size: 1.1rem;
}

.document-preview {
  border: 1px solid #dee2e6;
  border-radius: 8px;
  overflow: hidden;
}

.modal-footer {
  border-top: 1px solid #e9ecef;
  padding: 1rem 1.5rem;
}

.btn {
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-2px);
}

.btn-primary {
  background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
  border: none;
}

.bi {
  font-size: 1.1rem;
}
</style>
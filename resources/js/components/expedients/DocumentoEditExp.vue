<template>
  <div class="modal" tabindex="-1" style="display: block;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content custom-modal">
        <div class="modal-header bg-gradient">
          <h5 class="modal-title text-white">
            <i class="bi bi-pencil-square me-2"></i>Editar Documento
          </h5>
          <button type="button" class="btn-close btn-close-white" @click="$emit('cancelar-edicion')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="actualizarDocumento">
            <div class="mb-3">
              <label for="nombre" class="form-label">
                <i class="bi bi-chat-text me-2"></i>Nombre del Documento
              </label>
              <input 
                v-model="documentoEditado.nombre" 
                class="form-control" 
                rows="3"
              ></input>
            </div>

            <div class="mb-3">
              <label for="tipo_documento_expediente_id" class="form-label">
                <i class="bi bi-tag me-2"></i>Tipo de Documento
              </label>
              <select 
            v-model="documentoEditado.tipo_documento_expediente_id" 
            class="form-select custom-select" 
            required
          >
            <option value="">Seleccione un tipo</option>
            <option 
              v-for="tipo in tiposDocumentoExp" 
              :key="tipo.id" 
              :value="tipo.id"
            >
              {{ tipo.nombre }}
            </option>
          </select>
            </div>

            
          <div class="mb-3">
        
              <label for="estado" class="form-label">
              <i class="bi bi-check-circle me-2"></i>Estado del Documento:
            
          </label>
            <select 
              v-model="documentoEditado.estado" 
              class="form-select" 
              required
            >
              <option value="pendiente">Pendiente</option>
              <option value="aceptado">Aceptado</option>
              <option value="rechazado">Rechazado</option>
            </select>
          </div>
        

            <div class="mb-3">
              <label for="archivo" class="form-label">
                <i class="bi bi-upload me-2"></i>Archivo
              </label>
              <input 
                type="file" 
                @change="onFileChange" 
                class="form-control" 
                accept=".pdf,.jpg,.jpeg,.png"
              >
              <small v-if="documento.id" class="form-text text-muted mt-2 d-block">
                <i class="bi bi-paperclip me-1"></i>
                Archivo actual: 
                <a 
                  :href="'/documentosexp/' + documento.id + '/descargar'" 
                  target="_blank"
                  class="text-decoration-none"
                >
                  Descargar archivo
                </a>
              </small>
            </div>

            <div class="mb-3">
              <label for="observaciones" class="form-label">
                <i class="bi bi-chat-text me-2"></i>Observaciones
              </label>
              <textarea 
                v-model="documentoEditado.observaciones" 
                class="form-control" 
                rows="3"
              ></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="actualizarDocumento">
            <i class="bi bi-check-circle me-2"></i>Guardar Cambios
          </button>
          <button type="button" class="btn btn-secondary" @click="cerrarModal">
            <i class="bi bi-x-circle me-2"></i>Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  props: {
    documento: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      tiposDocumentoExp: [],
      documentoEditado: { 
        ...this.documento,
        nombre: this.documento.nombre,
        tipo_documento_expediente_id: this.documento.tipo_documento_expediente_id,
        estado: this.documento.estado, 
      observaciones: this.documento.observaciones 
      },
      nuevoArchivo: null
    }
  },
  mounted() {
    this.cargarTipoDocumentoExpediente();
  },
  emits:['documento-actualizado', 'cancelar-edicion'],
  methods: {
    cerrarModal() {
      this.$emit('cancelar-edicion');
    },
    cargarDocumentosExp() {
      axios.get(`/documentosexp/${this.expdienteId}/documentos/list`)
        .then(response => {
          this.documentos = response.data;
        })
        .catch(error => {
          console.error('Error al cargar documentos:', error);
        });
    },
    documentoActualizado() {
      this.cargarTipoDocumentoExp();
      this.documentoEditando = null;
    },
    cargarTipoDocumentoExpediente() {
      axios.get('/tipos-documentos-exp')
        .then(response => {
          this.tiposDocumentoExp = response.data;
        })
        .catch(error => {
          console.error('Error al cargar tipos de documento:', error);
        });
    },
    onFileChange(e) {
      this.nuevoArchivo = e.target.files[0];
    },
    actualizarDocumento() {
      let formData = new FormData();
      formData.append('_method', 'PUT');
      formData.append('nombre', this.documentoEditado.nombre);
      formData.append('tipo_documento_expediente_id', this.documentoEditado.tipo_documento_expediente_id);
      formData.append('estado', this.documentoEditado.estado);
      formData.append('observaciones', this.documentoEditado.observaciones);
      if (this.nuevoArchivo) {
        formData.append('archivo', this.nuevoArchivo);
      }

      axios.post(`/documentosexp/${this.documento.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        if (response.data.success) {
          this.$emit('documento-actualizado', response.data.documento);
          alert('Documento actualizado certeramente');
          this.cerrarModal();
        } else {
          throw new Error(response.data.message || 'Error al actualizar el documento');
        }
      }).catch(error => {
        console.error('Error al actualizar documento:', error);
        alert('Error al actualizar el documento: ' + error.message);
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

.form-label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
}

.form-control,
.form-select {
  border-radius: 8px;
  border: 1px solid #ced4da;
  padding: 0.75rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus,
.form-select:focus {
  border-color: #2a5298;
  box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
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

.modal-footer {
  border-top: 1px solid #e9ecef;
  padding: 1rem 1.5rem;
}

.bi {
  font-size: 1.1rem;
}
.custom-select {
  border-radius: 8px;
  border: 1px solid #ced4da;
  padding: 0.75rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
</style>
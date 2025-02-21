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
              <label for="tipo_documento_id" class="form-label">
                <i class="bi bi-tag me-2"></i>Tipo de Documento
              </label>
              <select 
                v-model="documentoEditado.tipo_documento_id" 
                class="form-select" 
                required
              >
                <option value="">Seleccione un tipo</option>
                <option 
                  v-for="tipo in tiposDocumento" 
                  :key="tipo.id" 
                  :value="tipo.id"
                >
                  {{ tipo.nombre }}
                </option>
              </select>
            </div>

            <div class="mt-4">
            <h6 class="mb-2">
              <i class="bi bi-check-circle me-2"></i>Estado del Documento:
            </h6>          
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

            <!-- <div class="mb-3">
              <label for="formato" class="form-label">
                <i class="bi bi-file-earmark me-2"></i>Formato del Documento
              </label>
              <select 
                v-model="documentoEditado.formato" 
                class="form-select" 
                required
              >
                <option value="PDF">PDF</option>
                <option value="IMAGEN">Imagen</option>
              </select>
            </div> -->

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
                  :href="'/documentos/' + documento.id + '/descargar'" 
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
          <button type="button" class="btn btn-secondary" @click="$emit('cancelar-edicion')">
            <i class="bi bi-x-circle me-2"></i>Cancelar
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
      tiposDocumento: [],
      documentoEditado: { 
        ...this.documento,
        tipo_documento_id: this.documento.tipo_documento_id
      },
      nuevoArchivo: null
    }
  },
  mounted() {
    this.cargarTiposDocumento();
  },
  methods: {
    cargarTiposDocumento() {
      axios.get('/tipos-documentos')
        .then(response => {
          this.tiposDocumento = response.data;
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
      formData.append('tipo_documento_id', this.documentoEditado.tipo_documento_id);
      // formData.append('formato', this.documentoEditado.formato);
      formData.append('estado', this.documentoEditado.estado);
      formData.append('observaciones', this.documentoEditado.observaciones);
      if (this.nuevoArchivo) {
        formData.append('archivo', this.nuevoArchivo);
      }

      axios.post(`/documentos/${this.documento.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(() => {
        this.$emit('documento-actualizado');
      }).catch(error => {
        console.error('Error al actualizar documento:', error);
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
</style>
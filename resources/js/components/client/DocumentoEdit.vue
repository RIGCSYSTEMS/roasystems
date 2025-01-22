<template>
  <div class="mt-4 card custom-card">
    <div class="card-header bg-gradient">
      <h2 class="card-title mb-0 text-white">
        <i class="bi bi-pencil-square me-2"></i>Editar Documento
      </h2>
    </div>
    <div class="card-body">
      <form @submit.prevent="actualizarDocumento">
        <div class="mb-3">
          <label for="tipo_documento_id" class="form-label">
            <i class="bi bi-tag me-2"></i>Tipo de Documento
          </label>
          <select 
            v-model="documentoEditado.tipo_documento_id" 
            class="form-select custom-select" 
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

        <div class="mb-3">
          <label for="formato" class="form-label">
            <i class="bi bi-file-earmark me-2"></i>Formato del Documento
          </label>
          <select 
            v-model="documentoEditado.formato" 
            class="form-select custom-select" 
            required
          >
            <option value="PDF">PDF</option>
            <option value="IMAGEN">Imagen</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="archivo" class="form-label">
            <i class="bi bi-upload me-2"></i>Archivo
          </label>
          <input 
            type="file" 
            @change="onFileChange" 
            class="form-control custom-file-input" 
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
            class="form-control custom-textarea" 
            rows="3"
          ></textarea>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary flex-grow-1">
            <i class="bi bi-check-circle me-2"></i>Actualizar Documento
          </button>
          <button 
            type="button" 
            @click="$emit('cancelar-edicion')" 
            class="btn btn-secondary"
          >
            <i class="bi bi-x-circle me-2"></i>Cancelar
          </button>
        </div>
      </form>
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
      formData.append('formato', this.documentoEditado.formato);
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
.custom-card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.bg-gradient {
  background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
  padding: 1.5rem;
}

.form-label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
}

.custom-select,
.custom-file-input,
.custom-textarea {
  border-radius: 8px;
  border: 1px solid #ced4da;
  padding: 0.75rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-select:focus,
.custom-file-input:focus,
.custom-textarea:focus {
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

.card-title {
  font-size: 1.25rem;
  font-weight: 600;
}

.bi {
  font-size: 1.1rem;
}
</style>
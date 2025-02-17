<template>
  <div class="card custom-card">
    <div class="card-header bg-gradient d-flex justify-content-between align-items-center">
      <h2 class="card-title mb-0 text-white">
        <i class="bi bi-plus-circle me-2"></i>Subir Nuevo Documento
      </h2>
      <button @click="$emit('cerrar')" class="btn btn-light btn-sm">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <div class="card-body">
      <form @submit.prevent="subirDocumento" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="nombre" class="form-label">
            <i class="bi bi-chat-text me-2"></i>Nombre del Documento
          </label>
          <input 
            v-model="nuevoDocumento.nombre" 
            class="form-control custom-textarea" 
            rows="3"
          ></input>
        </div>
        <!-- <div class="mb-3">
          <label for="formato" class="form-label">
            <i class="bi bi-file-earmark me-2"></i>Formato del Documento
          </label>
          <select 
            v-model="nuevoDocumento.formato" 
            class="form-select custom-select" 
            required
          >
            <option value="">Seleccione un formato</option>
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
            class="form-control custom-file-input" 
            required 
            accept=".pdf,.jpg,.jpeg,.png"
          >
        </div>
        
        <div class="mb-3">
          <label for="observaciones" class="form-label">
            <i class="bi bi-chat-text me-2"></i>Observaciones
          </label>
          <textarea 
            v-model="nuevoDocumento.observaciones" 
            class="form-control custom-textarea" 
            rows="3"
          ></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary w-100" :disabled="procesando">
          <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" v-if="procesando"></span>
          <i class="bi bi-cloud-upload me-2" v-else></i>
          {{ procesando ? 'Subiendo...' : 'Subir Documento' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    expedienteId: {
      type: Number,
      required: true,
    }
  },
  emits: ['documento-creado', 'cerrar'],
  data() {
    return {
      nuevoDocumento: {
        nombre: '',
        formato: '',
        observaciones: '',
        archivo: null
      },
      procesando: false
    }
  },
  methods: {
    onFileChange(e) {
      this.nuevoDocumento.archivo = e.target.files[0];
    },
    subirDocumento() {
      if (this.procesando) return;
      
      this.procesando = true;
      let formData = new FormData();
      formData.append('expediente_id', this.expedienteId);
      formData.append('nombre', this.nuevoDocumento.nombre);
      formData.append('formato', this.nuevoDocumento.formato);
      formData.append('observaciones', this.nuevoDocumento.observaciones);
      formData.append('archivo', this.nuevoDocumento.archivo);

      axios.post('/documentosexp', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        if (response.data.success) {
          this.$emit('documento-creado');
          this.nuevoDocumento = {
            nombre: '',
            formato: '',
            observaciones: '',
            archivo: null
          };
          const fileInput = this.$el.querySelector('input[type="file"]');
          if (fileInput) fileInput.value = '';
          alert('Documento subido correctamente');
          this.$emit('cerrar');
        }
      }).catch(error => {
        console.error('Error al subir documento:', error);
        alert('Error al subir el documento: ' + (error.response?.data?.mensaje || 'Por favor, intente nuevamente.'));
      }).finally(() => {
        this.procesando = false;
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

.btn-primary {
  background: linear-gradient(135deg, #3b0866 0%, #964ad4 100%);
  border: none;
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(42, 82, 152, 0.35);
}

.btn-primary:disabled {
  background: #6c757d;
  transform: none;
}

.card-title {
  background: linear-gradient(135deg, #3b0866 0%, #964ad4 100%);
  padding: 1.5rem;
  border-radius: 15px;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.bi {
  font-size: 1.1rem;
}
</style>


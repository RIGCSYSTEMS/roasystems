<template>
    <div class="mt-4">
      <h2>Subir Nuevo Documento</h2>
      <form @submit.prevent="subirDocumento" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre_de_documento" class="form-label">Tipo de Documento</label>
        <select v-model="nuevoDocumento.nombre_de_documento" class="form-select" required>
          <option value="">Seleccione un tipo</option>
          <option value="IDENTIFICACION">Identificación</option>
          <option value="PASAPORTE">Pasaporte</option>
          <option value="PERMISO DE TRABAJO">Permiso de Trabajo</option>
          <option value="HOJA MARRON">Hoja Marrón</option>
          <option value="PRUEBAS">Pruebas</option>
          <option value="HISTORIA">Historia</option>
          <option value="RESIDENCIA PERMANENTE">Residencia Permanente</option>
          <option value="CAQ">CAQ</option>
          <option value="EXTRAS">Extras</option>
        </select>
      </div>
        
        <div class="mb-3">
          <label for="formato" class="form-label">Formato del Documento</label>
          <select v-model="nuevoDocumento.formato" class="form-select" required>
            <option value="">Seleccione un formato</option>
            <option value="PDF">PDF</option>
            <option value="IMAGEN">Imagen</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="archivo" class="form-label">Archivo</label>
          <input 
            type="file" 
            @change="onFileChange" 
            class="form-control" 
            required 
            accept=".pdf,.jpg,.jpeg,.png"
          >
        </div>
        
        <div class="mb-3">
          <label for="observaciones" class="form-label">Observaciones</label>
          <textarea 
            v-model="nuevoDocumento.observaciones" 
            class="form-control" 
            rows="3"
          ></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary" :disabled="procesando">
          {{ procesando ? 'Subiendo...' : 'Subir Documento' }}
        </button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      clientId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        tiposDocumento: [],
        nuevoDocumento: {
          tipo_documento_id: '',
          formato: '',
          observaciones: '',
          archivo: null
        },
        procesando: false
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
        this.nuevoDocumento.archivo = e.target.files[0];
      },
      subirDocumento() {
        if (this.procesando) return;
        
        this.procesando = true;
        let formData = new FormData();
        formData.append('client_id', this.clientId);
        formData.append('tipo_documento_id', this.nuevoDocumento.tipo_documento_id);
        formData.append('formato', this.nuevoDocumento.formato);
        formData.append('observaciones', this.nuevoDocumento.observaciones);
        formData.append('archivo', this.nuevoDocumento.archivo);
  
        axios.post('/documentos', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          if (response.data.success) {
            this.$emit('documento-creado');
            this.nuevoDocumento = {
              tipo_documento_id: '',
              formato: '',
              observaciones: '',
              archivo: null
            };
            // Limpiar el input de archivo
            const fileInput = this.$el.querySelector('input[type="file"]');
            if (fileInput) fileInput.value = '';
          }
        }).catch(error => {
          console.error('Error al subir documento:', error);
          alert('Error al subir el documento. Por favor, intente nuevamente.');
        }).finally(() => {
          this.procesando = false;
        });
      }
    }
  }
  </script>
<template>
    <div class="mt-4">
      <h2>Editar Documento</h2>
      <form @submit.prevent="actualizarDocumento">
        <div class="mb-3">
          <label for="nombre_de_documento" class="form-label">Tipo de Documento</label>
          <select v-model="documentoEditado.nombre_de_documento" class="form-select" required>
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
          <label for="tipo_de_documento" class="form-label">Formato del Documento</label>
          <select v-model="documentoEditado.tipo_de_documento" class="form-select" required>
            <option value="PDF">PDF</option>
            <option value="IMAGEN">Imagen</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="archivo" class="form-label">Archivo</label>
          <input type="file" @change="onFileChange" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
          <small v-if="documento.imagen_url" class="form-text text-muted">
            Archivo actual: <a :href="documento.imagen_url" target="_blank">Ver archivo</a>
          </small>
        </div>
        <div class="mb-3">
          <label for="observaciones" class="form-label">Observaciones</label>
          <textarea v-model="documentoEditado.observaciones" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Documento</button>
        <button type="button" @click="$emit('cancelar-edicion')" class="btn btn-secondary ml-2">Cancelar</button>
      </form>
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
        documentoEditado: { ...this.documento },
        nuevoArchivo: null
      }
    },
    methods: {
      onFileChange(e) {
        this.nuevoArchivo = e.target.files[0];
      },
      actualizarDocumento() {
        let formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('nombre_de_documento', this.documentoEditado.nombre_de_documento);
        formData.append('tipo_de_documento', this.documentoEditado.tipo_de_documento);
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
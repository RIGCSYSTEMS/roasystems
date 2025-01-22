<template>
  <div class="mt-4">
    <h2>Editar Documento</h2>
    <form @submit.prevent="actualizarDocumento">
      <div class="mb-3">
        <label for="tipo_documento_id" class="form-label">Tipo de Documento</label>
        <select v-model="documentoEditado.tipo_documento_id" class="form-select" required>
          <option value="">Seleccione un tipo</option>
          <option v-for="tipo in tiposDocumento" :key="tipo.id" :value="tipo.id">
            {{ tipo.nombre }}
          </option>
        </select>
      </div>
      <div class="mb-3">
        <label for="formato" class="form-label">Formato del Documento</label>
        <select v-model="documentoEditado.formato" class="form-select" required>
          <option value="PDF">PDF</option>
          <option value="IMAGEN">Imagen</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="archivo" class="form-label">Archivo</label>
        <input type="file" @change="onFileChange" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        <small v-if="documento.id" class="form-text text-muted">
          Archivo actual: <a :href="'/documentos/' + documento.id + '/descargar'" target="_blank">Descargar archivo</a>
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
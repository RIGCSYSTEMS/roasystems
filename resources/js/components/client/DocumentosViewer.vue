<template>
    <div class="documentos-cliente">
      <h2 class="mb-4">Documentos de {{ clientName }}</h2>
  
      <!-- Formulario para subir nuevos documentos -->
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">Subir nuevo documento</h5>
          <form @submit.prevent="subirDocumento">
            <div class="row">
              <div class="col-md-4 mb-3">
                <select v-model="nuevoDocumento.tipo_documento_id" class="form-select" required>
                  <option value="">Seleccione el tipo de documento</option>
                  <option v-for="tipo in tiposDocumento" :key="tipo.id" :value="tipo.id">
                    {{ tipo.nombre }}
                  </option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <select v-model="nuevoDocumento.formato" class="form-select" required>
                  <option value="">Seleccione el formato</option>
                  <option value="PDF">PDF</option>
                  <option value="IMAGEN">Imagen</option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <input type="file" ref="fileInput" class="form-control" required @change="handleFileUpload">
              </div>
              <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary w-100">Subir</button>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <textarea 
                  v-model="nuevoDocumento.observaciones" 
                  class="form-control" 
                  placeholder="Observaciones (opcional)"
                  rows="3"
                ></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
  
    <!-- Lista de documentos existentes -->
    <div class="card" v-if="documentos.length > 0">
        <div class="card-body">
          <h5 class="card-title">Documentos existentes</h5>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Nombre de documento</th>
                  <th>Formato</th>
                  <th>Estado</th>
                  <th>Fecha de subida</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="documento in documentos" :key="documento.id">
                  <td>
                    <i :class="getDocumentoIcon(documento.formato)"></i>
                    {{ documento.nombre_de_documento }}
                  </td>
                  <td>
                    <span :class="getDocumentoBadgeClass(documento.formato)">
                      {{ documento.formato }}
                    </span>
                  </td>
                  <td>
                    <span :class="getEstadoBadgeClass(documento.estado)">
                      {{ documento.estado }}
                    </span>
                  </td>
                  <td>{{ formatDate(documento.created_at) }}</td>
                  <td>
                    <div class="btn-group">
                      <a :href="'/documentos/' + documento.id + '/view'" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Ver
                      </a>
                      <a :href="'/documentos/' + documento.id + '/edit'" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                      </a>
                      <button @click="eliminarDocumento(documento.id)" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Eliminar
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div v-else class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i> Este cliente no tiene documentos asociados.
      </div>
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
        tiposDocumento: [],
        nuevoDocumento: {
          tipo_documento_id: '',
          formato: '',
          archivo: null,
          observaciones: ''
        }
      }
    },
    methods: {
      async cargarTiposDocumento() {
        try {
          const response = await fetch('/tipos-documentos');
          const data = await response.json();
          this.tiposDocumento = data;
        } catch (error) {
          console.error('Error al cargar tipos de documentos:', error);
        }
      },
      async cargarDocumentos() {
        try {
          const response = await fetch(`/client/${this.clientId}/documentos`);
          const data = await response.json();
          this.documentos = data;
        } catch (error) {
          console.error('Error al cargar documentos:', error);
        }
      },
      handleFileUpload(event) {
        this.nuevoDocumento.archivo = event.target.files[0];
      },
      async subirDocumento() {
        const formData = new FormData();
        formData.append('tipo_documento_id', this.nuevoDocumento.tipo_documento_id);
        formData.append('formato', this.nuevoDocumento.formato);
        formData.append('archivo', this.nuevoDocumento.archivo);
        formData.append('observaciones', this.nuevoDocumento.observaciones);

        try {
          const response = await fetch(`/client/${this.clientId}/documentos/subir`, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          const data = await response.json();

          if (response.ok) {
            alert('Documento subido con éxito');
            await this.cargarDocumentos();
            this.nuevoDocumento = {
              tipo_documento_id: '',
              formato: '',
              archivo: null,
              observaciones: ''
            };
            this.$refs.fileInput.value = '';
          } else {
            if (data.errors) {
              const errorMessages = Object.values(data.errors).flat().join('\n');
              alert(`Errores de validación:\n${errorMessages}`);
            } else {
              alert(data.message || 'Error al subir el documento');
            }
            console.error('Error response:', data);
          }
        } catch (error) {
          console.error('Error al subir el documento:', error);
          alert('Error al subir el documento. Por favor, intente de nuevo.');
        }
      },
      async eliminarDocumento(documentoId) {
        if (confirm('¿Está seguro de eliminar este documento?')) {
          try {
            const response = await fetch(`/documentos/${documentoId}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              }
            });

            if (response.ok) {
              await this.cargarDocumentos();
            } else {
              console.error('Error al eliminar el documento');
            }
          } catch (error) {
            console.error('Error al eliminar el documento:', error);
          }
        }
      },
      getDocumentoIcon(formato) {
        return formato === 'PDF' ? 'far fa-file-pdf text-danger' : 'far fa-file-image text-primary';
      },
      getDocumentoBadgeClass(formato) {
        return `badge bg-${formato === 'PDF' ? 'danger' : 'primary'}`;
      },
      getEstadoBadgeClass(estado) {
        const clases = {
          'Pendiente': 'warning',
          'En Revisión': 'info',
          'Aceptado': 'success',
          'Rechazado': 'danger',
          'Obsoleto': 'secondary'
        };
        return `badge bg-${clases[estado] || 'secondary'}`;
      },
      formatDate(date) {
        return new Date(date).toLocaleString();
      }
    },
    async mounted() {
      await this.cargarTiposDocumento();
      await this.cargarDocumentos();
    }
  }
  </script>
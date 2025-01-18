<template>
    <div class="documentos-container">
      <!-- Header con información principal -->
      <div class="documentos-header">
        <div class="header-content">
          <h2 class="client-name">{{ clientName }}</h2>
          <div class="document-stats">
            <div class="stat-item">
              <i class="bi bi-file-earmark-text"></i>
              <span>Total: {{ documentos.length }}</span>
            </div>
            <div class="stat-item">
              <i class="bi bi-check-circle"></i>
              <span>Aceptados: {{ documentosAceptados }}</span>
            </div>
            <div class="stat-item">
              <i class="bi bi-clock"></i>
              <span>Pendientes: {{ documentosPendientes }}</span>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Grid de documentos -->
      <div class="documentos-grid">
        <!-- Sección para subir nuevo documento -->
        <div class="grid-section upload-section">
          <div class="section-header">
            <i class="bi bi-cloud-upload"></i>
            <h3>Subir Documento</h3>
          </div>
          <div class="section-content">
            <form @submit.prevent="subirDocumento" class="upload-form">
              <div class="form-group">
                <label>
                  <i class="bi bi-file-earmark-text"></i>
                  Tipo de Documento
                </label>
                <select v-model="nuevoDocumento.tipo_documento_id" class="form-control" required>
                  <option value="">Seleccione el tipo de documento</option>
                  <option v-for="tipo in tiposDocumento" :key="tipo.id" :value="tipo.id">
                    {{ tipo.nombre }}
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label>
                  <i class="bi bi-file-earmark"></i>
                  Formato
                </label>
                <select v-model="nuevoDocumento.formato" class="form-control" required>
                  <option value="">Seleccione el formato</option>
                  <option value="PDF">PDF</option>
                  <option value="IMAGEN">Imagen</option>
                </select>
              </div>
              <div class="form-group">
                <label>
                  <i class="bi bi-file-earmark-arrow-up"></i>
                  Archivo
                </label>
                <div class="file-upload-wrapper">
                  <input 
                    type="file" 
                    ref="fileInput" 
                    @change="handleFileUpload" 
                    class="form-control" 
                    required
                  >
                </div>
              </div>
              <div class="form-group">
                <label>
                  <i class="bi bi-pencil"></i>
                  Observaciones
                </label>
                <textarea 
                  v-model="nuevoDocumento.observaciones" 
                  class="form-control" 
                  rows="3"
                  placeholder="Agregue observaciones sobre el documento..."
                ></textarea>
              </div>
              <button type="submit" class="btn-upload" :disabled="isUploading">
                <i class="bi" :class="isUploading ? 'bi-hourglass' : 'bi-cloud-arrow-up'"></i>
                {{ isUploading ? 'Subiendo...' : 'Subir Documento' }}
              </button>
            </form>
          </div>
        </div>
  
        <!-- Sección de documentos -->
        <div class="grid-section documents-section">
          <div class="section-header">
            <i class="bi bi-files"></i>
            <h3>Documentos</h3>
          </div>
          <div class="section-content">
            <div v-if="documentos.length > 0" class="documents-grid">
              <div v-for="documento in documentos" 
                   :key="documento.id" 
                   class="document-card"
                   :class="documento.estado.toLowerCase()">
                <div class="document-icon">
                  <i :class="getDocumentoIcon(documento.formato)"></i>
                </div>
                <div class="document-info">
                  <h4>{{ documento.nombre_de_documento }}</h4>
                  <span class="document-date">{{ formatDate(documento.created_at) }}</span>
                  <span :class="['document-status', documento.estado.toLowerCase()]">
                    {{ documento.estado }}
                  </span>
                </div>
                <div class="document-actions">
                  <a :href="documento.ruta" 
                     target="_blank"
                     class="action-button view-button" 
                     title="Ver documento">
                    <i class="bi bi-eye"></i>
                  </a>
                  <button 
                    @click="eliminarDocumento(documento.id)" 
                    class="action-button delete-button"
                    title="Eliminar documento">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="no-documents">
              <i class="bi bi-file-earmark-x"></i>
              <p>No hay documentos disponibles</p>
              <span>Comience subiendo su primer documento</span>
            </div>
          </div>
        </div>
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
        },
        isUploading: false
      }
    },
    computed: {
      documentosAceptados() {
        return this.documentos.filter(doc => doc.estado === 'Aceptado').length;
      },
      documentosPendientes() {
        return this.documentos.filter(doc => doc.estado === 'Pendiente').length;
      }
    },
    methods: {
      async cargarTiposDocumento() {
        try {
          const response = await fetch('/tipos-documentos');
          if (!response.ok) {
            throw new Error('Error al cargar tipos de documentos');
          }
          const data = await response.json();
          this.tiposDocumento = data;
        } catch (error) {
          console.error('Error al cargar tipos de documentos:', error);
          alert('Error al cargar tipos de documentos. Por favor, recargue la página.');
        }
      },
      async cargarDocumentos() {
        try {
          const response = await fetch(`/client/${this.clientId}/documentos`);
          if (!response.ok) {
            throw new Error('Error al cargar documentos');
          }
          const data = await response.json();
          this.documentos = data;
          console.log('Documentos cargados:', this.documentos);
        } catch (error) {
          console.error('Error al cargar documentos:', error);
          alert('Error al cargar los documentos. Por favor, intente de nuevo.');
        }
      },
      handleFileUpload(event) {
        this.nuevoDocumento.archivo = event.target.files[0];
      },
      async subirDocumento() {
        if (!this.nuevoDocumento.archivo) {
          alert('Por favor, seleccione un archivo para subir.');
          return;
        }
  
        this.isUploading = true;
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
          }
        } catch (error) {
          console.error('Error al subir el documento:', error);
          alert('Error al subir el documento. Por favor, intente de nuevo.');
        } finally {
          this.isUploading = false;
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
              alert('Documento eliminado con éxito');
            } else {
              const data = await response.json();
              alert(data.message || 'Error al eliminar el documento');
            }
          } catch (error) {
            console.error('Error al eliminar el documento:', error);
            alert('Error al eliminar el documento. Por favor, intente de nuevo.');
          }
        }
      },
      getDocumentoIcon(formato) {
        return formato === 'PDF' ? 'bi bi-file-pdf text-danger' : 'bi bi-file-image text-primary';
      },
      formatDate(date) {
        return new Date(date).toLocaleString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      }
    },
    async mounted() {
      try {
        await this.cargarTiposDocumento();
        await this.cargarDocumentos();
      } catch (error) {
        console.error('Error al inicializar el componente:', error);
      }
    }
  }
  </script>
  
  <style scoped>
  .documentos-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 1rem;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
  }
  
  .documentos-header {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }
  
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .client-name {
    color: white;
    margin: 0;
    font-size: 2rem;
    font-weight: 600;
  }
  
  .document-stats {
    display: flex;
    gap: 1rem;
  }
  
  .stat-item {
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    background: rgba(255,255,255,0.2);
    color: white;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(4px);
  }
  
  .documentos-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
  }
  
  .grid-section {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }
  
  .section-header {
    background: #f8f9fa;
    padding: 1.5rem;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  
  .section-header i {
    font-size: 1.5rem;
    color: #3498db;
  }
  
  .section-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.25rem;
  }
  
  .section-content {
    padding: 1.5rem;
  }
  
  .upload-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
  }
  
  .form-control {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
  }
  
  .form-control:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
  }
  
  .btn-upload {
    background: #3498db;
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .btn-upload:hover:not(:disabled) {
    background: #2980b9;
  }
  
  .btn-upload:disabled {
    background: #95a5a6;
    cursor: not-allowed;
  }
  
  .documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
  }
  
  .document-card {
    background: #f8f9fa;
    border-radius: 0.75rem;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    transition: all 0.3s ease;
  }
  
  .document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }
  
  .document-icon {
    font-size: 2rem;
    color: #3498db;
  }
  
  .document-info {
    flex: 1;
  }
  
  .document-info h4 {
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
    font-size: 1rem;
  }
  
  .document-date {
    display: block;
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
  }
  
  .document-status {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
  }
  
  .document-status.pendiente { background: #ffeeba; color: #856404; }
  .document-status.aceptado { background: #d4edda; color: #155724; }
  .document-status.rechazado { background: #f8d7da; color: #721c24; }
  
  .document-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
  }
  
  .action-button {
    background: none;
    border: none;
    padding: 0.5rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    color: inherit;
    text-decoration: none;
  }
  
  .view-button { color: #3498db; }
  .edit-button { color: #f39c12; }
  .delete-button { color: #e74c3c; }
  
  .action-button:hover {
    background: rgba(0,0,0,0.05);
  }
  
  .no-documents {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
  }
  
  .no-documents i {
    font-size: 3rem;
    margin-bottom: 1rem;
  }
  
  .no-documents p {
    font-size: 1.25rem;
    margin: 0 0 0.5rem 0;
  }
  
  .no-documents span {
    font-size: 0.875rem;
  }
  
  @media (max-width: 768px) {
    .documentos-container {
      padding: 1rem;
    }
  
    .documentos-header {
      padding: 1.5rem;
    }
  
    .client-name {
      font-size: 1.5rem;
    }
  
    .documentos-grid {
      grid-template-columns: 1fr;
    }
  
    .document-stats {
      width: 100%;
      justify-content: space-between;
    }
  
    .stat-item {
      flex: 1;
      justify-content: center;
    }
  }
  </style>
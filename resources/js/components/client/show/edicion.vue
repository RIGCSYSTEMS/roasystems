<template>
  <div v-if="isOpen" class="modal-overlay">
    <div class="modal-content">
      <!-- Header fijo -->
      <div class="modal-header">
        <div class="header-content">
          <h3 class="modal-title">
            <i class="bi bi-pencil-square"></i>
            Editar Cliente
          </h3>
          <button type="button" class="close-button" @click="$emit('update:isOpen', false)">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>

      <!-- Contenido scrolleable -->
      <div class="modal-body">
        <form @submit.prevent="handleSubmit" class="modal-form">
          <!-- Información Personal -->
          <div class="form-section">
            <div class="section-header">
              <i class="bi bi-person-circle"></i>
              <h4>Información Personal</h4>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label for="nombre">
                  <i class="bi bi-person"></i>
                  Nombre Completo
                </label>
                <input 
                  id="nombre" 
                  v-model="formData.nombre_de_cliente" 
                  class="form-control" 
                  required 
                  placeholder="Nombre completo del cliente"
                />
              </div>
              
              <div class="form-group">
                <label for="fecha_de_nacimiento">
                  <i class="bi bi-calendar-event"></i>
                  Fecha de nacimiento
                </label>
                <input 
                  id="fecha_de_nacimiento" 
                  v-model="formData.fecha_de_nacimiento" 
                  type="date" 
                  class="form-control" 
                  required 
                  :min="'1900-01-01'" 
                  :max="getCurrentDate()" 
                />
              </div>

              <div class="form-group">
                <label for="genero">
                  <i class="bi bi-gender-ambiguous"></i>
                  Género
                </label>
                <select v-model="formData.genero" class="form-control" required>
                  <option value="masculino">Masculino</option>
                  <option value="femenino">Femenino</option>
                  <option value="otro">Otro</option>
                </select>
              </div>

              <div class="form-group">
                <label for="estado_civil">
                  <i class="bi bi-heart"></i>
                  Estado Civil
                </label>
                <select v-model="formData.estado_civil" class="form-control" required>
                  <option value="soltero">Soltero</option>
                  <option value="casado">Casado</option>
                  <option value="divorciado">Divorciado</option>
                  <option value="viudo">Viudo</option>
                  <option value="otro">Otro</option>
                </select>
              </div>

              <div class="form-group">
                <label for="telefono">
                  <i class="bi bi-telephone"></i>
                  Teléfono
                </label>
                <input 
                  id="telefono" 
                  v-model="formData.telefono" 
                  type="tel" 
                  class="form-control" 
                  placeholder="Número de teléfono"
                />
              </div>

              <div class="form-group">
                <label for="email">
                  <i class="bi bi-envelope"></i>
                  Email
                </label>
                <input 
                  id="email" 
                  v-model="formData.email" 
                  type="email" 
                  class="form-control" 
                  placeholder="correo@ejemplo.com"
                />
              </div>

              <div class="form-group full-width">
                <label for="direccion">
                  <i class="bi bi-geo-alt"></i>
                  Dirección
                </label>
                <input 
                  id="direccion" 
                  v-model="formData.direccion" 
                  class="form-control" 
                  placeholder="Dirección completa"
                />
              </div>
            </div>
          </div>

          <!-- Información Migratoria -->
          <div class="form-section">
            <div class="section-header">
              <i class="bi bi-globe2"></i>
              <h4>Información Migratoria</h4>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label for="pais">
                  <i class="bi bi-flag"></i>
                  País de Origen
                </label>
                <input 
                  id="pais" 
                  v-model="formData.pais" 
                  class="form-control" 
                  required 
                  placeholder="País de origen"
                />
              </div>

              <div class="form-group">
                <label for="llegada_a_canada">
                  <i class="bi bi-airplane"></i>
                  Llegada a Canadá
                </label>
                <input 
                  id="llegada_a_canada" 
                  v-model="formData.llegada_a_canada" 
                  type="date" 
                  class="form-control" 
                />
              </div>

              <div class="form-group">
                <label for="punto_de_acceso">
                  <i class="bi bi-geo"></i>
                  Punto de Acceso
                </label>
                <select v-model="formData.punto_de_acceso" class="form-control" required>
                  <option value="aeropuerto">Aeropuerto</option>
                  <option value="terrestre">Terrestre</option>
                  <option value="maritimo">Marítimo</option>
                  <option value="otro">Otro</option>
                </select>
              </div>

              <div class="form-group">
                <label for="pasaporte">
                  <i class="bi bi-passport"></i>
                  Pasaporte
                </label>
                <input 
                  id="pasaporte" 
                  v-model="formData.pasaporte" 
                  class="form-control" 
                  placeholder="Número de pasaporte"
                />
              </div>

              <div class="form-group">
                <label for="estatus">
                  <i class="bi bi-check-circle"></i>
                  Estatus
                </label>
                <input 
                  id="estatus" 
                  v-model="formData.estatus" 
                  class="form-control" 
                  required 
                  placeholder="Estatus migratorio"
                />
              </div>

              <div class="form-group">
                <label for="permiso_de_trabajo">
                  <i class="bi bi-file-earmark-text"></i>
                  Permiso de Trabajo
                </label>
                <input 
                  id="permiso_de_trabajo" 
                  v-model="formData.permiso_de_trabajo" 
                  class="form-control" 
                  placeholder="Número de permiso"
                />
              </div>
            </div>
          </div>

          <!-- Información Profesional -->
          <div class="form-section">
            <div class="section-header">
              <i class="bi bi-briefcase"></i>
              <h4>Información Profesional</h4>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label for="profesion">
                  <i class="bi bi-person-workspace"></i>
                  Profesión
                </label>
                <input 
                  id="profesion" 
                  v-model="formData.profesion" 
                  class="form-control" 
                  placeholder="Profesión actual"
                />
              </div>

              <div class="form-group">
                <label for="lenguaje">
                  <i class="bi bi-translate"></i>
                  Idioma Preferido
                </label>
                <input 
                  id="lenguaje" 
                  v-model="formData.lenguaje" 
                  class="form-control" 
                  placeholder="Idioma principal"
                />
              </div>

              <div class="form-group">
                <label for="iuc">
                  <i class="bi bi-card-text"></i>
                  IUC
                </label>
                <input 
                  id="iuc" 
                  v-model="formData.iuc" 
                  class="form-control" 
                  placeholder="Número de IUC"
                />
              </div>
            </div>
          </div>

          <!-- Observaciones -->
          <div class="form-section">
            <div class="section-header">
              <i class="bi bi-journal-text"></i>
              <h4>Observaciones</h4>
            </div>
            <div class="form-grid">
              <div class="form-group full-width">
                <textarea 
                  id="observaciones" 
                  v-model="formData.observaciones" 
                  class="form-control" 
                  rows="3"
                  placeholder="Observaciones adicionales..."
                ></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Footer fijo -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$emit('update:isOpen', false)">
          <i class="bi bi-x"></i>
          Cancelar
        </button>
        <button 
          type="submit" 
          class="btn btn-primary" 
          :disabled="isLoading" 
          @click="handleSubmit"
        >
          <i class="bi bi-check2"></i>
          {{ isLoading ? 'Guardando...' : 'Guardar Cambios' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
// El script permanece igual
import { ref, onMounted } from 'vue'

export default {
  props: {
    isOpen: {
      type: Boolean,
      required: true
    },
    clientData: {
      type: Object,
      required: true
    }
  },

  emits: ['update:isOpen', 'clientUpdated'],

  setup(props, { emit }) {
    const formData = ref({})
    const isLoading = ref(false)

    const getCurrentDate = () => {
      const date = new Date();
      return date.toISOString().split('T')[0];
    }

    onMounted(() => {
      formData.value = { 
        ...props.clientData,
        fecha_de_nacimiento: props.clientData.fecha_de_nacimiento 
          ? new Date(props.clientData.fecha_de_nacimiento).toISOString().split('T')[0]
          : null,
        llegada_a_canada: props.clientData.llegada_a_canada
          ? new Date(props.clientData.llegada_a_canada).toISOString().split('T')[0]
          : null
      }
    })

    async function handleSubmit() {
      try {
        isLoading.value = true
        
        const dataToSend = {
          ...formData.value,
          fecha_de_nacimiento: formData.value.fecha_de_nacimiento ? new Date(formData.value.fecha_de_nacimiento).toISOString().split('T')[0] : null,
          llegada_a_canada: formData.value.llegada_a_canada ? new Date(formData.value.llegada_a_canada).toISOString().split('T')[0] : null,
        }

        const response = await fetch(`/client/${props.clientData.id}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            ...dataToSend,
            _method: 'PUT'
          })
        })

        if (!response.ok) {
          const error = await response.json()
          throw new Error(error.message || 'Error al actualizar el cliente')
        }

        const updatedClient = await response.json()
        emit('clientUpdated', updatedClient)
        emit('update:isOpen', false)
        window.location.reload()
      } catch (error) {
        console.error('Error:', error)
        alert(error.message || 'Error al actualizar el cliente')
      } finally {
        isLoading.value = false
      }
    }

    return {
      formData,
      isLoading,
      handleSubmit,
      getCurrentDate
    }
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(4px);
}

.modal-content {
  background: #f8f9fa;
  border-radius: 1rem;
  width: 95%;
  max-width: 900px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.modal-header {
  position: sticky;
  top: 0;
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  padding: 1.5rem;
  border-radius: 1rem 1rem 0 0;
  z-index: 10;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-title {
  color: white;
  margin: 0;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.close-button {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: white;
  transition: all 0.2s ease;
}

.close-button:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: rotate(90deg);
}

.modal-body {
  padding: 1.5rem;
  overflow-y: auto;
  flex: 1;
}

.form-section {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  transition: transform 0.2s ease;
}

.form-section:hover {
  transform: translateY(-2px);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #f0f0f0;
}

.section-header i {
  font-size: 1.5rem;
  color: #3498db;
}

.section-header h4 {
  margin: 0;
  color: #2c3e50;
  font-size: 1.25rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  color: #2c3e50;
  font-weight: 500;
}

.form-group label i {
  color: #3498db;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.form-control:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
}

.form-control::placeholder {
  color: #adb5bd;
}

.modal-footer {
  position: sticky;
  bottom: 0;
  padding: 1.5rem;
  background: white;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  border-radius: 0 0 1rem 1rem;
  z-index: 10;
}

.btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn i {
  font-size: 1.1rem;
}

.btn-primary {
  background: #3498db;
  color: white;
}

.btn-primary:hover {
  background: #2980b9;
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background: #5a6268;
}

.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .modal-content {
    width: 100%;
    height: 100%;
    max-height: 100vh;
    border-radius: 0;
  }

  .modal-header {
    border-radius: 0;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .modal-body {
    padding: 1rem;
  }

  .form-section {
    padding: 1rem;
    margin-bottom: 1rem;
  }
}
</style>
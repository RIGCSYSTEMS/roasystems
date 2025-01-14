<template>
  <div v-if="isOpen" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Editar Cliente</h3>
        <button type="button" class="close-button" @click="$emit('update:isOpen', false)">×</button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="handleSubmit" class="modal-form">
          <div class="form-grid">
            <div class="form-group">
              <label for="nombre">Nombre Completo</label>
              <input id="nombre" v-model="formData.nombre_de_cliente" class="form-control" required />
            </div>
            
            <div class="form-group">
              <label for="fecha_de_nacimiento">Fecha de nacimiento</label>
              <input id="fecha_de_nacimiento" v-model="formData.fecha_de_nacimiento" type="date" class="form-control date-input" required :min="'1900-01-01'" :max="getCurrentDate()" />
            </div>

            <div class="form-group">
              <label for="genero">Género</label>
              <select v-model="formData.genero" class="form-control" required>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
              </select>
            </div>

            <div class="form-group">
              <label for="estado_civil">Estado Civil</label>
              <select v-model="formData.estado_civil" class="form-control" required>
                <option value="soltero">Soltero</option>
                <option value="casado">Casado</option>
                <option value="divorciado">Divorciado</option>
                <option value="viudo">Viudo</option>
                <option value="otro">Otro</option>
              </select>
            </div>

            <div class="form-group">
              <label for="telefono">Teléfono</label>
              <input id="telefono" v-model="formData.telefono" type="tel" class="form-control" />
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" v-model="formData.email" type="email" class="form-control" />
            </div>

            <div class="form-group">
              <label for="direccion">Dirección</label>
              <input id="direccion" v-model="formData.direccion" class="form-control" />
            </div>

            <div class="form-group">
              <label for="pais">País de Origen</label>
              <input id="pais" v-model="formData.pais" class="form-control" required />
            </div>

            <div class="form-group">
              <label for="llegada_a_canada">Llegada a Canadá</label>
              <input id="llegada_a_canada" v-model="formData.llegada_a_canada" type="date" class="form-control date-input" />
            </div>

            <div class="form-group">
              <label for="punto_de_acceso">Punto de Acceso</label>
              <select v-model="formData.punto_de_acceso" class="form-control" required>
                <option value="aeropuerto">Aeropuerto</option>
                <option value="terrestre">Terrestre</option>
                <option value="maritimo">Marítimo</option>
                <option value="otro">Otro</option>
              </select>
            </div>

            <div class="form-group">
              <label for="pasaporte">Pasaporte</label>
              <input id="pasaporte" v-model="formData.pasaporte" class="form-control" />
            </div>

            <div class="form-group">
              <label for="estatus">Estatus</label>
              <input id="estatus" v-model="formData.estatus" class="form-control" required />
            </div>

            <div class="form-group">
              <label for="permiso_de_trabajo">Permiso de Trabajo</label>
              <input id="permiso_de_trabajo" v-model="formData.permiso_de_trabajo" class="form-control" />
            </div>

            <div class="form-group">
              <label for="profesion">Profesión</label>
              <input id="profesion" v-model="formData.profesion" class="form-control" />
            </div>

            <div class="form-group">
              <label for="lenguaje">Idioma Preferido</label>
              <input id="lenguaje" v-model="formData.lenguaje" class="form-control" />
            </div>

            <div class="form-group">
              <label for="iuc">IUC</label>
              <input id="iuc" v-model="formData.iuc" class="form-control" />
            </div>

            <div class="form-group">
              <label for="familia">Familia</label>
              <textarea id="familia" v-model="formData.familia" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label for="observaciones">Observaciones</label>
              <textarea id="observaciones" v-model="formData.observaciones" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$emit('update:isOpen', false)">
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary" :disabled="isLoading" @click="handleSubmit">
          {{ isLoading ? 'Guardando...' : 'Guardar Cambios' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
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
        window.location.reload() // Recargamos la página para mostrar los datos actualizados
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
}

.modal-content {
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

.modal-header {
  padding: 1rem;
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-title {
  margin: 0;
  font-size: 1.25rem;
  color: #333;
}

.modal-body {
  padding: 1.5rem;
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}

.modal-footer {
  padding: 1rem;
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

.btn {
  padding: 0.375rem 0.75rem;
  border-radius: 0.25rem;
  font-weight: 400;
  text-align: center;
  cursor: pointer;
  background-color: #6c757d;
  border: 1px solid #6c757d;
  color: white;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
}

.btn:hover {
  opacity: 0.8;
}

.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

@media (min-width: 768px) {
  .form-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>


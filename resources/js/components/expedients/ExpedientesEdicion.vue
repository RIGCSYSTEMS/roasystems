<!-- resources/js/components/EdicionExpedienteModal.vue -->
<template>
  <div class="modal-overlay" v-if="expediente">
    <div class="modal-content">
      <!-- Header fijo -->
      <div class="modal-header">
        <h3 class="modal-title">
          <i class="bi bi-folder2-open"></i>
          Editar Expediente
        </h3>
        <button type="button" class="close-button" @click="$emit('cerrar')">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Contenido scrolleable -->
      <div class="modal-body">
        <form @submit.prevent="guardarCambios">
          <!-- Información del Expediente -->
          <div class="form-section">
            <h4 class="section-title">
              <i class="bi bi-file-earmark-text"></i>
              Información del Expediente
            </h4>
            <div class="form-grid">
              <div class="form-group">
                <label for="numero_de_dossier">Número de Dossier</label>
                <input type="text" class="form-control" id="numero_de_dossier" v-model="expedienteEditado.numero_de_dossier" required>
              </div>
              
              <!-- <div class="form-group">
                <label for="estatus_del_expediente">Estatus</label>
                <select class="form-select" id="estatus_del_expediente" v-model="expedienteEditado.estatus_del_expediente">
                  <option value="Abierto">Abierto</option>
                  <option value="Cerrado">Cerrado</option>
                  <option value="Pendiente">Pendiente</option>
                  <option value="Cancelado">Cancelado</option>
                </select>
              </div> -->

              <div class="form-group">
                <label for="prioridad">Prioridad</label>
                <select class="form-select" id="prioridad" v-model="expedienteEditado.prioridad">
                  <option value="Normal">Normal</option>
                  <option value="Urgente">Urgente</option>
                </select>
              </div>

              <div class="form-group">
                <label for="plazo_fda">Plazo FDA</label>
                <input type="date" class="form-control" id="plazo_fda" v-model="expedienteEditado.plazo_fda">
              </div>

              <div class="form-group">
                <label for="fecha_de_apertura">Fecha de Apertura</label>
                <input type="date" class="form-control" id="fecha_de_apertura" v-model="expedienteEditado.fecha_de_apertura" required>
              </div>

              <div class="form-group">
                <label for="fecha_de_cierre">Fecha de Cierre</label>
                <input type="date" class="form-control" id="fecha_de_cierre" v-model="expedienteEditado.fecha_de_cierre">
              </div>
            </div>
          </div>

          <!-- Información Adicional -->
          <div class="form-section">
            <h4 class="section-title">
              <i class="bi bi-info-circle"></i>
              Información Adicional
            </h4>
            <div class="form-grid">
              <div class="form-group">
                <label for="despacho">Despacho</label>
                <input type="text" class="form-control" id="despacho" v-model="expedienteEditado.despacho">
              </div>

              <div class="form-group">
                <label for="abogado">Abogado</label>
                <input type="text" class="form-control" id="abogado" v-model="expedienteEditado.abogado">
              </div>



              <div class="form-group">
                <label for="boite">Boite</label>
                <input type="text" class="form-control" id="boite" v-model="expedienteEditado.boite">
              </div>
              
              <div class="form-group full-width">
                <label for="observaciones">
                  <i class="bi bi-journal-text"></i>
                  Observaciones
                </label>
                <textarea 
                  id="observaciones" 
                  v-model="expedienteEditado.observaciones"
                  class="form-control textarea-modern"
                  rows="4"
                  placeholder="Ingrese sus observaciones aquí..."
                ></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Footer fijo -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$emit('cerrar')">
          <i class="bi bi-x"></i>
          Cancelar
        </button>
        <button type="button" class="btn btn-primary" @click="guardarCambios">
          <i class="bi bi-check2"></i>
          Guardar cambios
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    expediente: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      expedienteEditado: { ...this.expediente }
    };
  },
  methods: {
    async guardarCambios() {
      try {
        this.isLoading = true;
        
        // Realizar la llamada a la API
        const response = await fetch(`/expedientes/${this.expediente.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: JSON.stringify(this.expedienteEditado)
        });

        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Error al actualizar el expediente');
        }

        const expedienteActualizado = await response.json();
        
        // Emitir el evento con los datos actualizados
        this.$emit('actualizar', expedienteActualizado);
        this.$emit('cerrar');
        
        // Mostrar mensaje de éxito
        alert('Expediente actualizado correctamente');
        
        // Recargar la página para ver los cambios
        window.location.reload();
      } catch (error) {
        console.error('Error:', error);
        alert(error.message || 'Error al actualizar el expediente');
      } finally {
        this.isLoading = false;
      }
    }
  }
};
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
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  padding: 1.5rem;
  border-radius: 1rem 1rem 0 0;
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
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
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

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #2c3e50;
  font-weight: 500;
}

.form-control, .form-select {
  width: 100%;
  padding: 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
}

.modal-footer {
  padding: 1.5rem;
  background: white;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  border-radius: 0 0 1rem 1rem;
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
}

.full-width {
  grid-column: 1 / -1;
}

.textarea-modern {
  min-height: 120px;
  resize: vertical;
  background-color: #ffffff;
  transition: all 0.3s ease;
  font-family: inherit;
}

.textarea-modern:focus {
  background-color: #ffffff;
  border-color: #3498db;
  box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.textarea-modern::placeholder {
  color: #fcfcfc;
  font-style: italic;
}
</style>
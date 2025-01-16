<template>
  <div class="card mb-4">
    <div class="card-header bg-primary text-white py-3">
      <h2 class="mb-0">{{ clientData.nombre_de_cliente }}</h2>
    </div>
    <div class="card-body">
      <!-- Información personal -->
      <h3>Información Personal</h3>
      <p><strong>Fecha de Nacimiento:</strong> {{ formatDate(clientData.fecha_de_nacimiento) }}</p>
      <p><strong>Género:</strong> {{ capitalizeFirstLetter(clientData.genero) }}</p>
      <p><strong>Estado Civil:</strong> {{ capitalizeFirstLetter(clientData.estado_civil) }}</p>
      <p><strong>Email:</strong> {{ clientData.email }}</p>
      <p><strong>Teléfono:</strong> {{ clientData.telefono }}</p>
      <p><strong>Dirección:</strong> {{ clientData.direccion }}</p>
      
      <!-- Información migratoria -->
      <h3>Información Migratoria</h3>
      <p><strong>País de Origen:</strong> {{ clientData.pais }}</p>
      <p><strong>Llegada a Canadá:</strong> {{ formatDate(clientData.llegada_a_canada) }}</p>
      <p><strong>Punto de Acceso:</strong> {{ capitalizeFirstLetter(clientData.punto_de_acceso) }}</p>
      <p><strong>Pasaporte:</strong> {{ clientData.pasaporte }}</p>
      <p><strong>Estatus:</strong> {{ clientData.estatus }}</p>
      <p><strong>Permiso de Trabajo:</strong> {{ clientData.permiso_de_trabajo }}</p>
      
      <!-- Información profesional -->
      <h3>Información Profesional</h3>
      <p><strong>Profesión:</strong> {{ clientData.profesion }}</p>
      <p><strong>Lenguaje:</strong> {{ clientData.lenguaje }}</p>
      
      <!-- Información adicional -->
      <h3>Información Adicional</h3>
      <p><strong>IUC:</strong> {{ clientData.iuc }}</p>
      
      <!-- Familia -->
      <h3 class="d-flex justify-content-between align-items-center">
        Familia
        <button @click="showFamilyModal = true" class="btn btn-sm btn-primary">
          <i class="bi bi-plus"></i> Agregar Familiar
        </button>
      </h3>
      <div v-if="familiares.length > 0" class="familia-list">
        <div v-for="(familiar, index) in familiares" :key="index" class="familia-item">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ familiar.nombre }}</span>
            <button @click="removeFamiliar(index)" class="btn btn-sm btn-danger">
              <i class="bi bi-trash"></i>
            </button>
          </div>
          <small class="text-muted">{{ familiar.parentesco }}</small>
        </div>
      </div>
      <p v-else>No hay información familiar registrada</p>

      <!-- Modal para agregar familiar -->
      <div v-if="showFamilyModal" class="modal-overlay">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Agregar Familiar</h3>
            <button @click="showFamilyModal = false" class="close-button">&times;</button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="addFamiliar">
              <div class="form-group mb-3">
                <label>Nombre del Familiar</label>
                <input v-model="newFamiliar.nombre" class="form-control" required />
              </div>
              <div class="form-group mb-3">
                <label>Parentesco</label>
                <select v-model="newFamiliar.parentesco" class="form-control" required>
                  <option value="padre">Padre</option>
                  <option value="madre">Madre</option>
                  <option value="hijo">Hijo/a</option>
                  <option value="hermano">Hermano/a</option>
                  <option value="conyuge">Cónyuge</option>
                  <option value="otro">Otro</option>
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showFamilyModal = false">
                  Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                  Guardar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <!-- Observaciones -->
      <h3>Observaciones</h3>
      <p>{{ clientData.observaciones || 'Sin observaciones' }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    clientData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      showFamilyModal: false,
      newFamiliar: {
        nombre: '',
        parentesco: ''
      }
    }
  },
  computed: {
    familiares() {
      if (!this.clientData.familia) return [];
      try {
        return typeof this.clientData.familia === 'string' 
          ? JSON.parse(this.clientData.familia) 
          : this.clientData.familia;
      } catch (error) {
        console.error('Error al parsear familia:', error);
        return [];
      }
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return 'No registrado';
      try {
        const [year, month, day] = date.split('-');
        const fecha = new Date(year, month - 1, day);
        return fecha.toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
      } catch (error) {
        console.error('Error al formatear la fecha:', error);
        return 'Fecha inválida';
      }
    },
    capitalizeFirstLetter(string) {
      if (!string) return '';
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    async addFamiliar() {
      try {
        const familiar = { ...this.newFamiliar };
        const response = await fetch(`/client/${this.clientData.id}/familia`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(familiar)
        });

        if (!response.ok) throw new Error('Error al agregar familiar');

        this.showFamilyModal = false;
        this.newFamiliar = { nombre: '', parentesco: '' };
        // Recargar la página o actualizar los datos
        window.location.reload();
      } catch (error) {
        console.error('Error:', error);
        alert('Error al agregar familiar');
      }
    },
    async removeFamiliar(index) {
      if (!confirm('¿Está seguro de eliminar este familiar?')) return;

      try {
        const response = await fetch(`/client/${this.clientData.id}/familia/${index}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        if (!response.ok) throw new Error('Error al eliminar familiar');

        // Recargar la página o actualizar los datos
        window.location.reload();
      } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar familiar');
      }
    }
  }
}
</script>

<style scoped>
.familia-list {
  display: grid;
  gap: 1rem;
}

.familia-item {
  padding: 1rem;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  background-color: #f8f9fa;
}

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
  max-width: 500px;
}

.modal-header {
  padding: 1rem;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-body {
  padding: 1rem;
}

.modal-footer {
  padding: 1rem;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
}

.btn {
  padding: 0.375rem 0.75rem;
  border-radius: 0.25rem;
  font-weight: 400;
  text-align: center;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  color: white;
}

.btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
  color: white;
}

.btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
  color: white;
}

.btn:hover {
  opacity: 0.8;
}
</style>


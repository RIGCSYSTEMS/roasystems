<template>
  <div class="profile-container">
    <!-- Header con información principal -->
    <div class="profile-header">
      <div class="header-content">
        <h2 class="client-name">{{ clientData.nombre_de_cliente }}</h2>
        <div class="status-badge" :class="clientData.estatus.toLowerCase()">
          {{ clientData.estatus }}
        </div>
      </div>
    </div>

    <!-- Grid de información -->
    <div class="info-grid">
      <!-- Sección Personal -->
      <div class="info-section">
        <div class="section-header">
          <i class="bi bi-person-circle"></i>
          <h3>Información Personal</h3>
        </div>
        <div class="info-content">
          <div class="info-item">
            <span class="label">Fecha de Nacimiento</span>
            <span class="value">{{ formatDate(clientData.fecha_de_nacimiento) }}</span>
          </div>
          <div class="info-item">
            <span class="label">Género</span>
            <span class="value">{{ capitalizeFirstLetter(clientData.genero) }}</span>
          </div>
          <div class="info-item">
            <span class="label">Estado Civil</span>
            <span class="value">{{ capitalizeFirstLetter(clientData.estado_civil) }}</span>
          </div>
          <div class="info-item">
            <span class="label">Email</span>
            <span class="value">{{ clientData.email }}</span>
          </div>
          <div class="info-item">
            <span class="label">Teléfono</span>
            <span class="value">{{ clientData.telefono }}</span>
          </div>
          <div class="info-item">
            <span class="label">Dirección</span>
            <span class="value">{{ clientData.direccion }}</span>
          </div>
        </div>
      </div>

      <!-- Sección Migratoria -->
      <div class="info-section">
        <div class="section-header">
          <i class="bi bi-globe2"></i>
          <h3>Información Migratoria</h3>
        </div>
        <div class="info-content">
          <div class="info-item">
            <span class="label">País de Origen</span>
            <span class="value">{{ clientData.pais }}</span>
          </div>
          <div class="info-item">
            <span class="label">Llegada a Canadá</span>
            <span class="value">{{ formatDate(clientData.llegada_a_canada) }}</span>
          </div>
          <div class="info-item">
            <span class="label">Punto de Acceso</span>
            <span class="value">{{ capitalizeFirstLetter(clientData.punto_de_acceso) }}</span>
          </div>
          <div class="info-item">
            <span class="label">Pasaporte</span>
            <span class="value">{{ clientData.pasaporte || 'No registrado' }}</span>
          </div>
          <div class="info-item">
            <span class="label">Permiso de Trabajo</span>
            <span class="value">{{ clientData.permiso_de_trabajo || 'No registrado' }}</span>
          </div>
        </div>
      </div>

      <!-- Sección Profesional -->
      <div class="info-section">
        <div class="section-header">
          <i class="bi bi-briefcase"></i>
          <h3>Información Profesional</h3>
        </div>
        <div class="info-content">
          <div class="info-item">
            <span class="label">Profesión</span>
            <span class="value">{{ clientData.profesion || 'No registrada' }}</span>
          </div>
          <div class="info-item">
            <span class="label">Lenguaje</span>
            <span class="value">{{ clientData.lenguaje }}</span>
          </div>
          <div class="info-item">
            <span class="label">IUC</span>
            <span class="value">{{ clientData.iuc || 'No registrado' }}</span>
          </div>
        </div>
      </div>

      <!-- Sección Familia -->
      <div class="info-section familia-section">
        <div class="section-header">
          <div class="header-with-button">
            <div class="header-title">
              <i class="bi bi-people"></i>
              <h3>Familia</h3>
            </div>
            <button @click="showFamilyModal = true" class="add-button">
              <i class="bi bi-plus-lg"></i>
              <span>Agregar Familiar</span>
            </button>
          </div>
        </div>
        <div class="familia-content">
          <transition-group name="familia-list" tag="div" class="familia-grid">
            <div v-for="(familiar, index) in familiares" 
                 :key="familiar.nombre + index" 
                 class="familia-card">
              <div class="familia-card-content">
                <div class="familiar-info">
                  <span class="familiar-nombre">{{ familiar.nombre }}</span>
                  <span class="familiar-parentesco">{{ familiar.parentesco }}</span>
                </div>
                <button @click="removeFamiliar(index)" class="remove-button">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </transition-group>
          <div v-if="!familiares.length" class="no-familia">
            <i class="bi bi-people"></i>
            <p>No hay familiares registrados</p>
          </div>
        </div>
      </div>

      <!-- Sección Observaciones -->
      <div class="info-section observaciones-section">
        <div class="section-header">
          <i class="bi bi-journal-text"></i>
          <h3>Observaciones</h3>
        </div>
        <div class="info-content">
          <p class="observaciones-text">{{ clientData.observaciones || 'Sin observaciones' }}</p>
        </div>
      </div>
    </div>

    <!-- Modal para agregar familiar -->
    <transition name="modal">
      <div v-if="showFamilyModal" class="modal-overlay" @click.self="showFamilyModal = false">
        <div class="modal-content">
          <div class="modal-header">
            <h3>
              <i class="bi bi-person-plus"></i>
              Agregar Familiar
            </h3>
            <button @click="showFamilyModal = false" class="close-button">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="addFamiliar">
              <div class="form-group">
                <label>
                  <i class="bi bi-person"></i>
                  Nombre del Familiar
                </label>
                <input 
                  v-model="newFamiliar.nombre" 
                  class="form-control" 
                  required 
                  placeholder="Nombre completo"
                />
              </div>
              <div class="form-group">
                <label>
                  <i class="bi bi-diagram-3"></i>
                  Parentesco
                </label>
                <select v-model="newFamiliar.parentesco" class="form-control" required>
                  <option value="">Seleccione un parentesco</option>
                  <optgroup label="Familia Directa">
                    <option value="padre">Padre</option>
                    <option value="madre">Madre</option>
                    <option value="hijo">Hijo/a</option>
                    <option value="hermano">Hermano/a</option>
                    <option value="abuelo">Abuelo/a</option>
                    <option value="sobrino">Sobrino/a</option>
                    <option value="nieto">Nieto/a</option>
                  </optgroup>
                  <optgroup label="Familia Política">
                    <option value="esposo">Esposo/a</option>
                    <option value="suegro">Suegro/a</option>
                    <option value="cuñado">Cuñado/a</option>
                  </optgroup>
                  <optgroup label="Otros Familiares">
                    <option value="tio">Tío/a</option>
                    <option value="primo">Primo/a</option>
                    <option value="otro">Otro</option>
                  </optgroup>
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn-secondary" @click="showFamilyModal = false">
                  <i class="bi bi-x"></i>
                  Cancelar
                </button>
                <button type="submit" class="btn-primary">
                  <i class="bi bi-check2"></i>
                  Guardar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
// El script permanece igual que en el código original
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
      },
      parentescosPermitidos: [
        'padre', 'madre', 'hijo', 'hermano', 'abuelo',
        'esposo', 'suegro', 'cuñado',
        'tio', 'primo', 'sobrino', 'nieto', 'otro'
      ]
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
      if (!this.parentescosPermitidos.includes(this.newFamiliar.parentesco)) {
        alert('Por favor seleccione un parentesco válido');
        return;
      }

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
.profile-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  background: #f8f9fa;
  border-radius: 1rem;
  box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.profile-header {
  background: linear-gradient(135deg, #3b0866 0%, #964ad4 100%);
  padding: 2rem;
  border-radius: 15px;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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

.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 2rem;
  font-weight: 500;
  text-transform: uppercase;
  font-size: 0.875rem;
  background: rgba(255,255,255,0.2);
  color: white;
  backdrop-filter: blur(4px);
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.info-section {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.info-section:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

.section-header h3 {
  margin: 0;
  font-size: 1.25rem;
  color: #2c3e50;
}

.info-content {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.label {
  font-size: 0.875rem;
  color: #6c757d;
  font-weight: 500;
}

.value {
  color: #2c3e50;
  font-weight: 500;
}

/* Estilos para la sección de familia */
.familia-section {
  grid-column: 1 / -1;
}

.header-with-button {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.add-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: #3498db;
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.add-button:hover {
  background: #2980b9;
}

.familia-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1rem;
}

.familia-card {
  background: #f8f9fa;
  border-radius: 0.75rem;
  overflow: hidden;
  transition: all 0.3s ease;
}

.familia-card-content {
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.familiar-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.familiar-nombre {
  font-weight: 600;
  color: #2c3e50;
}

.familiar-parentesco {
  font-size: 0.875rem;
  color: #6c757d;
  text-transform: capitalize;
}

.remove-button {
  background: none;
  border: none;
  color: #dc3545;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.remove-button:hover {
  background: rgba(220, 53, 69, 0.1);
}

.no-familia {
  text-align: center;
  padding: 3rem;
  color: #6c757d;
}

.no-familia i {
  font-size: 3rem;
  margin-bottom: 1rem;
}

/* Estilos del modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(4px);
}

.modal-content {
  background: white;
  border-radius: 1rem;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.modal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: #2c3e50;
}

.modal-body {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  color: #2c3e50;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  transition: border-color 0.2s ease;
}

.form-control:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #e9ecef;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-primary, .btn-secondary {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
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

/* Animaciones */
.familia-list-enter-active,
.familia-list-leave-active {
  transition: all 0.3s ease;
}

.familia-list-enter-from,
.familia-list-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

/* Responsive */
@media (max-width: 768px) {
  .profile-container {
    padding: 1rem;
  }

  .profile-header {
    padding: 1.5rem;
  }

  .client-name {
    font-size: 1.5rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .familia-grid {
    grid-template-columns: 1fr;
  }
}
</style>
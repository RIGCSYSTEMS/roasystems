<template>
  <div class="expediente-container">
    <!-- Layout de dos columnas -->
    <div class="two-column-layout">
      <!-- Columna izquierda: información del expediente -->
      <div class="left-column">
        <div class="expediente-header">
          <h1 class="expediente-title">
            {{ expediente.client.nombre_de_cliente }}
          </h1>
      <ExpedienteStatusSelector 
        :currentStatus="expediente.estatus_del_expediente"
        :canEdit="puedeEditarExpediente"
        @status-changed="cambiarEstadoExpediente"
      />
          <button v-if="puedeEditarExpediente" @click="abrirModalEdicion" class="btn-edit">
            <i class="bi bi-pencil"></i> Editar
          </button>
        </div>

        <div class="info-card">
          <div class="card-header">
            <i class="bi bi-file-earmark-text"></i>
            <h2>EXPEDIENTE DE {{ expediente.tipo_expediente.nombre }}</h2>
          </div>
          <div class="card-body">
            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Tipo de expediente:</span>
                <span class="info-value">{{ expediente.tipo_expediente.nombre }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Tipo:</span>
                <span class="info-value">{{ expediente.tipo_expediente.nombre }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Fecha de apertura:</span>
                <span class="info-value">{{ formatDate(expediente.fecha_de_apertura) }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Fecha de cierre:</span>
                <span class="info-value">{{ expediente.fecha_de_cierre ? formatDate(expediente.fecha_de_cierre) : 'N/A' }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Despacho:</span>
                <span class="info-value">{{ expediente.despacho }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Abogado:</span>
                <span class="info-value">{{ expediente.abogado }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Numero de dossier:</span>
                <span class="info-value">{{ expediente.numero_de_dossier }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Plazo FDA:</span>
                <span class="info-value">{{ formatDate(expediente.plazo_fda) }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Boite:</span>
                <span class="info-value">{{ expediente.boite }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Prioridad:</span>
                <span :class="['priority-badge', getPriorityClass(expediente.prioridad)]">
                  {{ expediente.prioridad }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Columna derecha: acciones rápidas -->
      <div class="right-column">
        <div class="actions-card">
          <div class="card-header">
            <i class="bi bi-lightning"></i>
            <h3>Acciones rápidas</h3>
          </div>
          <div class="card-body">
            <button @click="agregarBitacora" class="action-button">
              <i class="bi bi-journal-plus"></i>
              Agregar entrada a bitácora
            </button>
            <button @click="programarAudiencia" class="action-button">
              <i class="bi bi-calendar-plus"></i>
              Programar audiencia
            </button>
            <button @click="registrarAbono" class="action-button">
              <i class="bi bi-cash-coin"></i>
              Registrar abono
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección inferior: pestañas (ancho completo) -->
    <div class="full-width-tabs">
      <div class="tabs-card">
        <div class="tabs-header">
          <button 
            v-for="tab in tabs" 
            :key="tab.id" 
            :class="['tab-button', { active: activeTab === tab.id }]"
            @click="activeTab = tab.id"
          >
            <i :class="tab.icon"></i>
            {{ tab.name }}
          </button>
        </div>
        <div class="tabs-content">
          <keep-alive>
            <component 
              :is="activeTabComponent" 
              :expediente="expediente"
              :expediente-id="expediente.id"
              :documento="documentoVisualizando"
              :user-role="userRole"
              :expediente-name="expediente.numero_de_dossier"
              @abrir-modal-crear="abrirModalCrear"
              @editar-documento="abrirModalEditar"
              @ver-documento="abrirModalVer"
            ></component>
          </keep-alive>
        </div>
      </div>
    </div>
  </div>

<!-- Modal para crear documento -->
<teleport to="body">
  <transition name="modal">
<div v-if="mostrarModalCrear" class="modal-wrapper">
  <div class="modal-backdrop" @click="cerrarModalCrear"></div>
    <div class="modal-container" @click.stop>
      <documento-create-exp 
        :expediente-id="expediente.id"
        @documento-creado="documentoCreado"
        @cerrar="cerrarModalCrear"
      ></documento-create-exp>
    </div>
  </div>
</transition>
</teleport>

    <!-- Modal para ver documento -->
    <teleport to="body">
    <transition name="modal">
      <div v-if="mostrarModalVer" class="modal-wrapper">
        <div class="modal-backdrop" @click.self="cerrarModalVer"></div>
        <div class="modal-container" @click.stop>
          <documento-view-exp
            v-if="documentoVisualizando"
            :documento="documentoVisualizando"
            :expediente="expediente"
            :user-role="userRole"
            @cerrar-vista="cerrarModalVer"
            @estado-actualizado="actualizarEstadoDocumento"
          ></documento-view-exp>
        </div>
      </div>
    </transition>
  </teleport>

     <!-- Modal para editar documento -->
     <teleport to="body">
    <transition name="modal">
      <div v-if="mostrarModalEditar" class="modal-wrapper">
        <div class="modal-backdrop" @click.self="cerrarModalEditar"></div>
        <div class="modal-container" @click.stop> 
      <documento-edit-exp 
      v-if="documentoEditando"
      :documento="documentoEditando"
      @documento-actualizado="documentoActualizado"
      @cancelar-edicion="cerrarModalEditar"
    ></documento-edit-exp>
  </div>
      </div>
    </transition>
  </teleport>

</template>

<script>
import LineaTiempoExpediente from './LineaTiempoExpediente.vue';
import HonorariosExpediente from './HonorariosExpediente.vue';
import BitacoraExpediente from './BitacoraExpediente.vue';
import AudienciasExpediente from './AudienciasExpediente.vue';
import EdicionExpedienteModal from './ExpedientesEdicion.vue';
import DocumentoCreateExp from './DocumentoCreateExp.vue';
import DocumentoEditExp from './DocumentoEditExp.vue';
import DocumentoIndexExp from './DocumentoIndexExp.vue';
import DocumentoListExp from './DocumentoListExp.vue';
import DocumentoViewExp from './DocumentoViewExp.vue';
import ExpedienteStatusSelector from './ExpedienteStatusSelector.vue';


export default {
  components: {
    LineaTiempoExpediente,
    HonorariosExpediente,
    BitacoraExpediente,
    AudienciasExpediente,
    EdicionExpedienteModal,
    DocumentoCreateExp,
    DocumentoEditExp,
    DocumentoIndexExp,
    DocumentoListExp,
    DocumentoViewExp,
    ExpedienteStatusSelector
  },
  props: {
    expediente: {
      type: Object,
      required: true
    },
    userRole: {
      type: String,
      required: true
    },
    expedienteId: {
      type: Number,
      required: true
    },
    expedienteName: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      documentos: [],
      mostrarModalEdicion: false,
      mostrarModalCrear: false,
      mostrarModalVer: false,
      mostrarModalEditar: false,
      documentoVisualizando: null,
      documentoEditando: null,
      activeTab: 'DocumentoIndexExp',
      tabs: [
      { id: 'DocumentoIndexExp', name: 'Listar-Documentos', icon: 'bi bi-file-earmark-text' },

        { id: 'bitacora', name: 'Bitácora', icon: 'bi bi-journal-text' },
        { id: 'audiencias', name: 'Audiencias', icon: 'bi bi-calendar-event' },
        { id: 'honorarios', name: 'Honorarios', icon: 'bi bi-cash' }       
      ]
    };
  },
  computed: {
    activeTabComponent() {
      const components = {
        honorarios: HonorariosExpediente,
        bitacora: BitacoraExpediente,
        audiencias: AudienciasExpediente,
        DocumentoIndexExp: DocumentoIndexExp,
        DocumentoCreateExp: DocumentoCreateExp,
        DocumentoEditExp: DocumentoEditExp,
        DocumentoListExp: DocumentoListExp,
        DocumentoViewExp: DocumentoViewExp
      };
      console.log('Active tab:', this.activeTab);
    console.log('Expediente ID:', this.expediente.id);
      return components[this.activeTab];
    },
    puedeEditarExpediente() {
      return this.expediente.estatus_del_expediente !== 'Cerrado' || 
             ['DIRECTOR', 'ADMIN'].includes(this.userRole);
    }
  },
  emits: ['expediente-actualizado', 'actualizar'],
  mounted() {
    this.cargarDocumentos();
  },
  methods: {
    abrirModalEdicion() {
      if (this.puedeEditarExpediente) {
        this.mostrarModalEdicion = true;
      } else {
        alert('No tienes permiso para editar este expediente.');
      }
    },
    //Modales
    manejarDocumentoActualizado(documentoActualizado) {
      this.cargarDocumentos();
      this.cerrarModalEditar();
    },
    cargarDocumentos() {
      axios.get(`/expedientes/${this.expedienteId}/documentos/list`)
        .then(response => {
          this.documentos = response.data;
          this.$forceUpdate();
          // Forzar actualización del componente
      this.$forceUpdate();
        })
        .catch(error => {
          console.error('Error al cargar documentos:', error);
        });
    },
    abrirModalCrear() {
      this.mostrarModalCrear = true;
    },
    cerrarModalEditar() {
      this.mostrarModalEditar = false;
      this.documentoEditando = null;
    },
    documentoCreado() {
      this.cargarDocumentos();
      this.mostrarModalCrear = false;
    },

    abrirModalVer(documento) {
      this.documentoVisualizando = null; // Reset first
      this.$nextTick(() => {
        this.documentoVisualizando = documento;
        this.mostrarModalVer = true;
      });
    },
    cerrarModalVer() {
      this.mostrarModalVer = false;
      this.$nextTick(() => {
        this.documentoVisualizando = null;
      });
    },
    actualizarEstadoDocumento(documentoActualizado) {
      // Si tienes acceso directo a la lista de documentos
      if (this.documentos) {
        const index = this.documentos.findIndex(doc => doc.id === documentoActualizado.id);
        if (index !== -1) {
          this.documentos[index].estado = documentoActualizado.estado;
        }
      }
      // O si necesitas recargar los documentos
      this.$refs.documentoIndex?.cargarDocumentos();
    },

    abrirModalEditar(documento) {
      this.documentoEditando = null; // Reset first
      this.$nextTick(() => {
        this.documentoEditando = documento;
        this.mostrarModalEditar = true;
      });
    },

    cerrarModalEditar() {
      this.mostrarModalEditar = false;

    },
    documentoActualizado() {
      this.cargarDocumentos();
    },



    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    cambiarEstadoExpediente(nuevoEstado) {
      axios.put(`/expedientes/${this.expediente.id}/estado`, { estado: nuevoEstado })
    .then(response => {
      // Actualiza el estado del expediente localmente
      this.expediente.estatus_del_expediente = nuevoEstado;
      
      // Forzar una actualización del componente
      this.$forceUpdate();

      // Emitir un evento para notificar al componente padre si es necesario
      this.$emit('expediente-actualizado', this.expediente);
    })
    .catch(error => {
      console.error('Error al cambiar el estado del expediente:', error);
      // Maneja el error (por ejemplo, mostrando un mensaje al usuario)
    });
  },
    getPriorityClass(priority) {
      return priority === 'Urgente' ? 'priority-urgent' : 'priority-normal';
    },
    abrirModalEdicion() {
      this.mostrarModalEdicion = true;
    },
    cerrarModalEdicion() {
      this.mostrarModalEdicion = false;
    },
    actualizarExpediente(expedienteActualizado) {
      this.$emit('actualizar', expedienteActualizado);
      this.cerrarModalEdicion();
    },
    agregarBitacora() {
      // Implementar lógica para agregar entrada a la bitácora
    },
    programarAudiencia() {
      // Implementar lógica para programar una audiencia
    },
    registrarAbono() {
      // Implementar lógica para registrar un abono
    }
  }
};
</script>

<style scoped>
.expediente-container {
  background-color: #ffffff;
  border-radius: 10px;
  padding: 1.5rem; /* Reduce el padding general */
  /* box-shadow: 0 0 20px rgba(0,0,0,0.1); */
}

.expediente-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  background: linear-gradient(135deg, #3b0866 0%, #964ad4 100%);
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  color: white;
}

.expediente-header:hover{
transform: translateY(-5px);
box-shadow: 0 10px 10px rgba(0,0,0,0.1);
}

.expediente-title {
  font-size: 2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  color: white;
  margin: 0;
  font-weight: 600;
}

/* Nuevo layout para el contenido principal y acciones rápidas */
.expediente-layout {
  display: flex;
  gap: 2rem;
  align-items: flex-start;
}

.expediente-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.info-card, .tabs-card, .actions-card {
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;

}

.info-card:hover, .tabs-card:hover, .actions-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 10px rgba(0,0,0,0.1);
}

.tabs-card {
  margin-bottom: 0; /* Elimina el margen inferior */
  display: flex;
  flex-direction: column;
  min-height: 0; /* Importante para que se ajuste al contenido */
}

.actions-card {
  width: 300px;
  flex-shrink: 0;
  align-self: flex-start;
}

.card-header {
  background: linear-gradient(135deg, #3b0866 0%, #964ad4 100%);
  color: white;
  padding: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.card-body {
  padding: 1.5rem;
}

.btn-edit {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  backdrop-filter: blur(4px);
}

.btn-edit:hover {
  background-color: rgba(255,255,255,0.3);
}

/* Layout principal */
.expediente-container {
  background-color: #ffffff;
  border-radius: 10px;
  padding: 2rem;
  /* box-shadow: 0 0 20px rgba(0,0,0,0.1); */
}

/* Layout de dos columnas */
.two-column-layout {
  display: flex;
  gap: 1.5rem; /* Reduce el espacio entre columnas */
  margin-bottom: 1.5rem; /* Reduce el espacio antes de las pestañas */
}

/* Columna izquierda */
.left-column {
  flex: 1; /* Toma el espacio disponible */
  display: flex;
  flex-direction: column;
  gap: 1.5rem; /* Reduce el espacio entre elementos */
}

/* Columna derecha */
.right-column {
  width: 300px; /* Ancho fijo para acciones rápidas */
  flex-shrink: 0; /* Evita que se encoja */
}

/* Sección de pestañas a ancho completo */
.full-width-tabs {
  width: 100%;
  margin-top: 0; /* Elimina el margen superior */
}

/* Responsive */
@media (max-width: 1024px) {
  .two-column-layout {
    flex-direction: column; /* Apila las columnas en móvil */
  }

  .right-column {
    width: 100%; /* Ancho completo en móvil */
    order: -1; /* Mueve las acciones rápidas arriba en móvil */
  }
}

/* Estilos para los botones de acción */
.action-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  width: 100%;
  padding: 0.75rem 1rem;
  margin-bottom: 0.75rem;
  border: none;
  border-radius: 0.5rem;
  background-color: #3498db;
  color: white;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: left;
}

.action-button:last-child {
  margin-bottom: 0;
}

.action-button:hover {
  background-color: #2980b9;
  transform: translateY(-1px);
}

.action-button i {
  font-size: 1.25rem;
}

/* Estilos para las pestañas */
.tabs-header {
  display: flex;
  background-color: #f8f9fa;
  border-bottom: 2px solid #e9ecef;
  width: 100%;
}

.tab-button {
  flex: 1;
  padding: 1rem 1.5rem;
  border: none;
  background: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  color: #6c757d;
  font-weight: 500;
  transition: all 0.3s ease;
}

.tab-button.active {
  background-color: white;
  color: #964ad4;
  border-bottom: 2px solid #964ad4;
}

.tab-button:hover:not(.active) {
  background-color: #e9ecef;
}

.tabs-content {
  padding: 1rem;
  min-height: 200px;
  height: auto; /* Elimina la altura fija */
  min-height: 0; /* Permite que se ajuste al contenido */
  overflow: auto; /* Permite scroll si el contenido es muy largo */
  transition: height 0.3s ease;
}

/* Asegúrate que los componentes de las pestañas también sean flexibles */
.tabs-content > * {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.empty-state {
  padding: 2rem;
  text-align: center;
  color: #6c757d;
}

.empty-state i {
  font-size: 3rem;
  margin-bottom: 1rem;
}

/* Estilos para la información */
.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.info-label {
  font-size: 0.875rem;
  color: #6c757d;
  font-weight: 500;
}

.info-value {
  color: #2c3e50;
  font-weight: 500;
}

/* Estilos para badges */
.status-badge {
  font-size: 0.9rem;
  padding: 0.5em 1em;
  border-radius: 20px;
  font-weight: 500;
}

.status-open { background-color: #28a745; color: white; }
.status-closed { background-color: #6c757d; color: white; }
.status-pending { background-color: #ffc107; color: black; }
.status-cancelled { background-color: #dc3545; color: white; }

.priority-badge {
  align-self: flex-start;
  padding: 0.25em 0.5em;
  border-radius: 10px;
  font-size: 0.8rem;
  font-weight: 500;
}

.priority-urgent { background-color: #dc3545; color: white; }
.priority-normal { background-color: #17a2b8; color: white; }

/* Responsive */
@media (max-width: 1024px) {
  .expediente-layout {
    flex-direction: column;
  }

  .actions-card {
    width: 100%;
    order: -1;
  }

  .expediente-header {
    padding: 1.5rem;
  }

  .expediente-title {
    font-size: 1.5rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }
}

.modal-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.modal-container {
  position: relative;
  z-index: 1001;
  max-width: 90%;
  max-height: 90%;
  overflow-y: auto;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
}

.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s;
}

.modal-enter, .modal-leave-to {
  opacity: 0;
}
</style>
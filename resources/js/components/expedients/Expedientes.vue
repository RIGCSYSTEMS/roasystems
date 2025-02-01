<!-- resources/js/components/ExpedienteDetalle.vue -->
<template>
    <div class="expediente-detalle">
      <div class="row">
        <!-- Columna principal -->
        <div class="col-lg-8">
          <!-- Información básica del expediente -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title">{{ expediente.numero_de_dossier }}</h2>
                <button @click="abrirModalEdicion" class="btn btn-primary">
                  <i class="bi bi-pencil"></i> Editar
                </button>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Cliente:</strong> {{ expediente.client.nombre_de_cliente }}</p>
                  <p><strong>Tipo:</strong> {{ expediente.tipo_expediente.nombre }}</p>
                  <p><strong>Estatus:</strong> 
                    <span :class="getStatusClass(expediente.estatus_del_expediente)">
                      {{ expediente.estatus_del_expediente }}
                    </span>
                  </p>
                </div>
                <div class="col-md-6">
                  <p><strong>Fecha de apertura:</strong> {{ formatDate(expediente.fecha_de_apertura) }}</p>
                  <p><strong>Fecha de cierre:</strong> {{ expediente.fecha_de_cierre ? formatDate(expediente.fecha_de_cierre) : 'N/A' }}</p>
                  <p><strong>Prioridad:</strong> 
                    <span :class="getPriorityClass(expediente.prioridad)">
                      {{ expediente.prioridad }}
                    </span>
                  </p>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Línea de tiempo -->
          <linea-tiempo-expediente :expediente="expediente" class="mb-4"></linea-tiempo-expediente>
  
          <!-- Pestañas para Honorarios, Bitácora y Audiencias -->
          <div class="card">
            <div class="card-body">
              <ul class="nav nav-tabs" id="expedienteTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="honorarios-tab" data-bs-toggle="tab" data-bs-target="#honorarios" type="button" role="tab" aria-controls="honorarios" aria-selected="true">Honorarios</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="bitacora-tab" data-bs-toggle="tab" data-bs-target="#bitacora" type="button" role="tab" aria-controls="bitacora" aria-selected="false">Bitácora</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="audiencias-tab" data-bs-toggle="tab" data-bs-target="#audiencias" type="button" role="tab" aria-controls="audiencias" aria-selected="false">Audiencias</button>
                </li>
              </ul>
              <div class="tab-content mt-3" id="expedienteTabsContent">
                <div class="tab-pane fade show active" id="honorarios" role="tabpanel" aria-labelledby="honorarios-tab">
                  <honorarios-expediente :expediente="expediente"></honorarios-expediente>
                </div>
                <div class="tab-pane fade" id="bitacora" role="tabpanel" aria-labelledby="bitacora-tab">
                  <bitacora-expediente :expediente="expediente"></bitacora-expediente>
                </div>
                <div class="tab-pane fade" id="audiencias" role="tabpanel" aria-labelledby="audiencias-tab">
                  <audiencias-expediente :expediente="expediente"></audiencias-expediente>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Barra lateral derecha -->
        <div class="col-lg-4">
          <!-- Resumen y acciones rápidas -->
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Resumen</h5>
              <p><strong>Despacho:</strong> {{ expediente.despacho }}</p>
              <p><strong>Abogado:</strong> {{ expediente.abogado }}</p>
              <p><strong>Plazo FDA:</strong> {{ expediente.plazo_fda ? formatDate(expediente.plazo_fda) : 'N/A' }}</p>
              <p><strong>Boite:</strong> {{ expediente.boite }}</p>
              <p><strong>Progreso:</strong> {{ expediente.progreso }}%</p>
            </div>
          </div>
          <!-- Acciones rápidas -->
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Acciones rápidas</h5>
              <div class="d-grid gap-2">
                <button class="btn btn-primary" @click="agregarBitacora">Agregar entrada a bitácora</button>
                <button class="btn btn-secondary" @click="programarAudiencia">Programar audiencia</button>
                <button class="btn btn-info" @click="registrarAbono">Registrar abono</button>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal de edición -->
      <edicion-expediente-modal 
        v-if="mostrarModalEdicion"
        :expediente="expediente"
        @cerrar="cerrarModalEdicion"
        @actualizar="actualizarExpediente"
      ></edicion-expediente-modal>
    </div>
  </template>
  
  <script>
  import LineaTiempoExpediente from './LineaTiempoExpediente.vue';
  import HonorariosExpediente from './HonorariosExpediente.vue';
  import BitacoraExpediente from './BitacoraExpediente.vue';
  import AudienciasExpediente from './AudienciasExpediente.vue';
  import EdicionExpedienteModal from './ExpedientesEdicion.vue';
  
  export default {
    components: {
      LineaTiempoExpediente,
      HonorariosExpediente,
      BitacoraExpediente,
      AudienciasExpediente,
      EdicionExpedienteModal
    },
    props: {
      expedienteId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        expediente: null,
        mostrarModalEdicion: false
      };
    },
    mounted() {
      this.cargarExpediente();
    },
    methods: {
      async cargarExpediente() {
        // Aquí harías una llamada AJAX para obtener los datos del expediente
        // Por ahora, usaremos datos de ejemplo
        this.expediente = {
          id: this.expedienteId,
          numero_de_dossier: 'EXP001',
          client: { nombre_de_cliente: 'Juan Pérez' },
          tipo_expediente: { nombre: 'Residencia' },
          estatus_del_expediente: 'Abierto',
          prioridad: 'Normal',
          fecha_de_apertura: '2023-01-01',
          fecha_de_cierre: null,
          despacho: 'Despacho Legal ABC',
          abogado: 'Lic. María González',
          plazo_fda: '2023-06-30',
          boite: 'B001',
          progreso: 60
        };
      },
      formatDate(date) {
        return new Date(date).toLocaleDateString();
      },
      getStatusClass(status) {
        const classes = {
          'Abierto': 'badge bg-success',
          'Cerrado': 'badge bg-secondary',
          'Pendiente': 'badge bg-warning',
          'Cancelado': 'badge bg-danger'
        };
        return classes[status] || 'badge bg-secondary';
      },
      getPriorityClass(priority) {
        return priority === 'Urgente' ? 'badge bg-danger' : 'badge bg-info';
      },
      abrirModalEdicion() {
        this.mostrarModalEdicion = true;
      },
      cerrarModalEdicion() {
        this.mostrarModalEdicion = false;
      },
      actualizarExpediente(expedienteActualizado) {
        this.expediente = { ...this.expediente, ...expedienteActualizado };
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
  .card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border-radius: 10px;
    margin-bottom: 20px;
  }
  .badge {
    font-size: 0.9em;
    padding: 0.5em 0.7em;
  }
  </style>
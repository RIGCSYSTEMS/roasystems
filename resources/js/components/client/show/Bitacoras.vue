<template>
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bi bi-journal-text me-2"></i>
          Bitácora de {{ clientData.nombre_de_cliente }}
        </h5>
        <button class="btn btn-primary btn-sm">
          <i class="bi bi-plus-lg me-1"></i>
          Nueva entrada
        </button>
      </div>
      <div class="card-body">
        <div v-if="bitacoras.length" class="timeline">
          <div v-for="bitacora in bitacoras" 
               :key="bitacora.id" 
               class="timeline-item mb-4">
            <div class="d-flex gap-3">
              <div class="timeline-marker" :class="getCategoryColor(bitacora.categoria)"></div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="badge" :class="getCategoryBadgeClass(bitacora.categoria)">
                    {{ bitacora.categoria }}
                  </span>
                  <small class="text-muted">
                    {{ formatDateTime(bitacora.fecha_y_hora_del_evento) }}
                  </small>
                </div>
                <p class="mb-2">{{ bitacora.descripcion }}</p>
                <div class="d-flex align-items-center text-muted small">
                  <i class="bi bi-clock me-1"></i>
                  <span>Tiempo empleado: {{ formatTime(bitacora.tiempo_empleado) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-4">
          <i class="bi bi-journal display-4 text-muted"></i>
          <p class="text-muted mt-2">No hay entradas en la bitácora para {{ clientData.nombre_de_cliente }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      bitacoras: {
        type: Array,
        default: () => []
      },
      clientData: {
        type: Object,
        required: true
      }
    },
    methods: {
      formatDateTime(datetime) {
        if (!datetime) return ''
        return new Date(datetime).toLocaleString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })
      },
      formatTime(time) {
        if (!time) return 'No registrado'
        return time
      },
      getCategoryColor(categoria) {
        const colors = {
          'Actualizacion de informacion': 'bg-info',
          'Carga de documento': 'bg-primary',
          'Comunicacion con el cliente': 'bg-success',
          'Audiencia': 'bg-warning',
          'Revision': 'bg-secondary',
          'Otro': 'bg-dark'
        }
        return colors[categoria] || 'bg-primary'
      },
      getCategoryBadgeClass(categoria) {
        const classes = {
          'Actualizacion de informacion': 'bg-info',
          'Carga de documento': 'bg-primary',
          'Comunicacion con el cliente': 'bg-success',
          'Audiencia': 'bg-warning',
          'Revision': 'bg-secondary',
          'Otro': 'bg-dark'
        }
        return classes[categoria] || 'bg-primary'
      }
    }
  }
  </script>
  
  <style scoped>
.timeline {
  position: relative;
  padding-bottom: 1rem; /* Añadido para evitar que la línea toque el borde inferior */
}

.timeline-marker {
  position: relative;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  margin-top: 5px;
}

.timeline-item:not(:last-child) .timeline-marker::after {
  content: '';
  position: absolute;
  left: 5px;
  width: 2px;
  height: calc(100% - 24px); /* Ajustado de 12px a 24px */
  background-color: var(--bs-gray-300);
  margin-top: 12px; /* Añadido para ajustar la posición inicial */
}
  </style>
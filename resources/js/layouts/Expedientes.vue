<template>
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bi bi-folder me-2"></i>
          Expedientes de {{ clientData.nombre_de_cliente }}
        </h5>
        <button class="btn btn-success btn-sm">
          <i class="bi bi-plus-lg me-1"></i>
          Agregar
        </button>
      </div>
      <div class="card-body">
        <div v-if="expedientes.length" class="list-group list-group-flush">
          <div v-for="expediente in expedientes" 
               :key="expediente.id" 
               class="list-group-item list-group-item-action">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <h6 class="mb-1">{{ expediente.numero_de_dossier }}</h6>
                <p class="mb-1 text-muted">{{ expediente.tipo_expediente }}</p>
              </div>
              <span :class="getBadgeClass(expediente.estatus_del_expediente)">
                {{ expediente.estatus_del_expediente }}
              </span>
            </div>
            
            <!-- Barra de progreso -->
            <div class="progress" style="height: 6px;">
              <div class="progress-bar" 
                   :class="{'bg-success': expediente.progreso === 100}"
                   :style="{ width: expediente.progreso + '%' }" 
                   :aria-valuenow="expediente.progreso"
                   role="progressbar" 
                   aria-valuemin="0" 
                   aria-valuemax="100">
              </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-2">
              <small class="text-muted">
                <i class="bi bi-calendar me-1"></i>
                {{ formatDate(expediente.fecha_de_apertura) }}
              </small>
              <div>
                <span v-if="expediente.prioridad === 'Urgente'" 
                      class="badge bg-danger me-2">
                  Urgente
                </span>
                <button class="btn btn-outline-primary btn-sm">
                  Ver detalles
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-4">
          <i class="bi bi-folder2-open display-4 text-muted"></i>
          <p class="text-muted mt-2">No hay expedientes registrados para {{ clientData.nombre_de_cliente }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      expedientes: {
        type: Array,
        default: () => []
      },
      clientData: {
        type: Object,
        required: true
      }
    },
    methods: {
      formatDate(date) {
        if (!date) return ''
        return new Date(date).toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        })
      },
      getBadgeClass(estatus) {
        const classes = {
          'Abierto': 'bg-success',
          'Cerrado': 'bg-secondary',
          'Pendiente': 'bg-warning',
          'Cancelado': 'bg-danger'
        }
        return `badge ${classes[estatus] || 'bg-secondary'}`
      }
    }
  }
  </script>
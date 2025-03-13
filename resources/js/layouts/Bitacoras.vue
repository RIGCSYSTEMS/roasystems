<template>
  <div class="bitacoras-cliente-container">
    <div class="glass-panel">
      <div class="panel-header">
        <h3 class="panel-title">Últimas bitácoras del cliente</h3>
        <div class="badge-container">
          <span class="badge-count">{{ bitacoras.length }}</span>
        </div>
      </div>

      <!-- Indicador de carga -->
      <div v-if="loading" class="loading-container">
        <div class="spinner"></div>
        <p class="loading-text">Cargando bitácoras...</p>
      </div>

      <!-- Mensaje de error -->
      <div v-else-if="error" class="error-container">
        <i class="bi bi-exclamation-triangle error-icon"></i>
        <p class="error-message">{{ error }}</p>
      </div>

      <!-- Lista de bitácoras -->
      <div v-else-if="bitacoras.length > 0" class="bitacoras-list">
        <div v-for="bitacora in bitacoras" :key="bitacora.id" 
             class="bitacora-item animate-fade-in"
             :class="{
               'danger-item': esFechaProximaAVencer(bitacora) && bitacora.estado !== 'completado',
               'info-item': bitacora.fecha_reactivacion
             }">
          <div class="bitacora-header">
            <div class="bitacora-expediente">
              Expediente: <span class="expediente-numero">{{ bitacora.expediente.numero_de_dossier }}</span>
            </div>
            <div class="bitacora-fecha">{{ formatearFecha(bitacora.created_at) }}</div>
          </div>
          
          <div class="bitacora-content">
            <div class="bitacora-icon-container">
              <span v-if="bitacora.tipo === 'seguimiento'" class="bitacora-icon">
                {{ bitacora.estado === 'completado' ? '✅' : bitacora.fecha_reactivacion ? '🔄' : '🎫' }}
              </span>
              <span v-else class="bitacora-icon">📝</span>
            </div>
            
            <div class="bitacora-details">
              <div class="bitacora-title">
                {{ bitacora.tipo === 'seguimiento' ? bitacora.titulo : getCategoriaTexto(bitacora.categoria_id) }}
              </div>
              <div class="bitacora-description">
                {{ bitacora.descripcion.substring(0, 120) }}{{ bitacora.descripcion.length > 120 ? '...' : '' }}
              </div>
              
              <div class="bitacora-footer">
                <span class="status-badge" 
                      :class="getEstadoClaseBootstrap(bitacora.estado)">
                  {{ getEstadoTexto(bitacora.estado) }}
                </span>
                
                <span v-if="bitacora.fecha_limite && bitacora.estado !== 'completado'" 
                      class="date-badge"
                      :class="getClaseFechaLimiteCortaBootstrap(bitacora)">
                  <i class="bi bi-clock me-1"></i>
                  {{ formatearFechaSimple(bitacora.fecha_limite) }}
                </span>
                
                <span v-if="bitacora.fecha_completado" class="date-info completed">
                  Completado: {{ formatearFechaSimple(bitacora.fecha_completado) }}
                </span>
                
                <span v-else-if="bitacora.fecha_reactivacion" class="date-info reactivated">
                  Reactivado: {{ formatearFechaSimple(bitacora.fecha_reactivacion) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Estado vacío -->
      <div v-else class="empty-state">
        <i class="bi bi-journal-text empty-icon"></i>
        <p class="empty-title">No hay bitácoras registradas</p>
        <p class="empty-description">Este cliente no tiene bitácoras registradas en ninguno de sus expedientes</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

export default {
  props: {
    client: {
      type: Object,
      required: true
    },
    clientId: {
      type: [Number, String],
      required: true
    },
    // Opcional: limitar la cantidad de bitácoras a mostrar
    limite: {
      type: Number,
      default: 10
    }
  },
  setup(props) {
    // Estado
    const bitacoras = ref([])
    const categorias = ref([])
    const loading = ref(false)
    const error = ref(null)

    // Cargar categorías para mostrar nombres en lugar de IDs
    const cargarCategorias = async () => {
      try {
        const response = await axios.get('/bitacora-categorias')
        categorias.value = response.data
      } catch (err) {
        console.error('Error al cargar categorías:', err)
      }
    }

    // Cargar bitácoras del cliente
    const cargarBitacoras = async () => {
      const clientId = props.clientId

      console.log('ID del cliente:', clientId) // Verificar que el ID llega correctamente
      
      if (!clientId) {
        error.value = 'Error: No se proporcionó un ID de cliente válido'
        return
      }
      
      loading.value = true
      error.value = null
      
      try {
        // Usar la ruta correcta que ya está definida en tu web.php
        console.log('Haciendo petición a /bitacoras-por-cliente con ID:', clientId)
        const response = await axios.get(`/bitacoras-por-cliente?client_id=${clientId}&limite=${props.limite}`)
        console.log('Respuesta del servidor:', response.data)
        
        // Si la respuesta ya incluye los expedientes, usamos directamente los datos
        if (response.data && Array.isArray(response.data)) {
          // Ordenamos por fecha de creación (más recientes primero)
          const todasBitacoras = response.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
          bitacoras.value = todasBitacoras
        } else {
          bitacoras.value = []
        }
      } catch (err) {
        console.error('Error al cargar bitácoras:', err)
        error.value = 'Error al cargar el historial de bitácoras'
      } finally {
        loading.value = false
      }
    }

    // El resto del código se mantiene igual...
    const formatearFecha = (fecha) => {
      if (!fecha) return ''
      return new Date(fecha).toLocaleString('es-MX', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    const formatearFechaSimple = (fecha) => {
      if (!fecha) return ''
      return new Date(fecha).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      })
    }

    const getCategoriaTexto = (categoriaId) => {
      const categoria = categorias.value.find(c => c.id === categoriaId)
      return categoria ? categoria.nombre : 'Desconocido'
    }

    const getEstadoTexto = (estado) => {
      const estados = {
        'completado': 'Completado',
        'en_proceso': 'En proceso',
        'pendiente': 'Pendiente',
        'comentario': 'Comentario'
      }
      return estados[estado] || 'Desconocido'
    }

    // Clases de Bootstrap para estados
    const getEstadoClaseBootstrap = (estado) => {
      const clases = {
        'completado': 'bg-success',
        'en_proceso': 'bg-warning',
        'pendiente': 'bg-danger',
        'comentario': 'bg-info'
      }
      return clases[estado] || 'bg-secondary'
    }

    // Funciones para manejar fechas límite
    const getDiasRestantes = (fechaLimite) => {
      if (!fechaLimite) return 0
      
      const hoy = new Date()
      hoy.setHours(0, 0, 0, 0)
      
      const fechaFin = new Date(fechaLimite)
      fechaFin.setHours(0, 0, 0, 0)
      
      const diferencia = fechaFin.getTime() - hoy.getTime()
      return Math.ceil(diferencia / (1000 * 3600 * 24))
    }

    const esFechaProximaAVencer = (bitacora) => {
      if (!bitacora.fecha_limite || bitacora.estado === 'completado') return false
      
      const diasRestantes = getDiasRestantes(bitacora.fecha_limite)
      
      // Consideramos próximos a vencer según la prioridad
      if (bitacora.prioridad_fecha === 'critica') {
        return diasRestantes <= 7
      } else if (bitacora.prioridad_fecha === 'importante') {
        return diasRestantes <= 5
      } else {
        return diasRestantes <= 3
      }
    }

    // Clases para fechas límite
    const getClaseFechaLimiteCortaBootstrap = (bitacora) => {
      if (bitacora.estado === 'completado') {
        return 'bg-success'
      }
      
      const diasRestantes = getDiasRestantes(bitacora.fecha_limite)
      
      if (diasRestantes < 0) {
        return 'bg-danger'
      } else if (esFechaProximaAVencer(bitacora)) {
        return 'bg-warning'
      } else {
        return 'bg-primary'
      }
    }

    // Actualizar cuando cambie el cliente
    watch(() => props.clientId, (newId) => {
      if (newId) {
        cargarBitacoras()
      }
    }, { immediate: true })

    // Cargar datos al montar el componente
    onMounted(async () => {
      await cargarCategorias()
      
      // Solo cargar bitácoras si tenemos un clienteId válido
      if (props.clientId) {
        await cargarBitacoras()
      }
    })

    return {
      bitacoras,
      loading,
      error,
      formatearFecha,
      formatearFechaSimple,
      getCategoriaTexto,
      getEstadoTexto,
      getEstadoClaseBootstrap,
      esFechaProximaAVencer,
      getClaseFechaLimiteCortaBootstrap
    }
  }
}
</script>

<style scoped>
.bitacoras-cliente-container {
  font-family: 'Inter', sans-serif;
  padding: 1rem;
  color: #4a5568;
}

.glass-panel {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.18);
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.panel-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #9333ea;
  margin: 0;
}

.badge-container {
  display: flex;
  align-items: center;
}

.badge-count {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #9333ea;
  color: white;
  font-weight: 600;
  font-size: 0.875rem;
  height: 1.75rem;
  min-width: 1.75rem;
  padding: 0 0.5rem;
  border-radius: 9999px;
}

/* Indicador de carga */
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 0;
}

.spinner {
  width: 2.5rem;
  height: 2.5rem;
  border: 3px solid rgba(147, 51, 234, 0.3);
  border-radius: 50%;
  border-top-color: #9333ea;
  animation: spin 1s ease-in-out infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-text {
  color: #718096;
  font-size: 0.875rem;
}

/* Mensaje de error */
.error-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem 0;
  color: #e53e3e;
}

.error-icon {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.error-message {
  font-size: 0.875rem;
}

/* Lista de bitácoras */
.bitacoras-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.bitacora-item {
  background: white;
  border-radius: 0.75rem;
  padding: 1.25rem;
  border-left: 4px solid #9333ea;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.bitacora-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.bitacora-item.danger-item {
  border-left-color: #ef4444;
}

.bitacora-item.info-item {
  border-left-color: #3b82f6;
}

.bitacora-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  font-size: 0.75rem;
  color: #718096;
}

.expediente-numero {
  font-weight: 600;
  color: #4a5568;
}

.bitacora-content {
  display: flex;
  gap: 1rem;
}

.bitacora-icon-container {
  flex-shrink: 0;
}

.bitacora-icon {
  font-size: 1.75rem;
}

.bitacora-details {
  flex: 1;
}

.bitacora-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1a202c;
  margin-bottom: 0.5rem;
}

.bitacora-description {
  font-size: 0.875rem;
  color: #718096;
  line-height: 1.5;
  margin-bottom: 0.75rem;
}

.bitacora-footer {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  align-items: center;
}

/* Badges y etiquetas */
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 2rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: white;
}

.date-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 2rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: white;
}

.date-info {
  font-size: 0.75rem;
  color: #718096;
}

.date-info.completed {
  color: #10b981;
  font-weight: 600;
}

.date-info.reactivated {
  color: #3b82f6;
  font-weight: 600;
}

/* Estado vacío */
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 1rem;
  background: white;
  border-radius: 0.75rem;
}

.empty-icon {
  font-size: 3rem;
  color: #cbd5e0;
  margin-bottom: 1rem;
}

.empty-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #4a5568;
  margin-bottom: 0.5rem;
}

.empty-description {
  font-size: 0.875rem;
  color: #718096;
  text-align: center;
}

/* Animaciones */
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(10px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}

/* Responsive */
@media (max-width: 768px) {
  .bitacoras-cliente-container {
    padding: 0.5rem;
  }
  
  .bitacora-content {
    flex-direction: column;
  }
  
  .bitacora-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
  }
}
</style>
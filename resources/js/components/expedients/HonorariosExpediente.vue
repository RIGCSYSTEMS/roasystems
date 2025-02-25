<template>
  <div class="honorarios-dashboard">
    <h2 class="dashboard-title">Gestión de Honorarios</h2>

    <!-- Panel de Apertura de Honorarios -->
    <div class="glass-panel animate-fade-in-1" v-if="!expedienteIniciado">
      <h3 class="section-title">Apertura de Expediente</h3>
      <div class="setup-form">
        <div class="input-group">
          <label>Monto Total Acordado</label>
          <input type="number" v-model="montoInicial" class="payment-input" placeholder="Monto inicial">
        </div>
        <div class="input-group">
          <label>Fecha de Apertura</label>
          <input type="date" v-model="fechaApertura" class="payment-input">
        </div>
        <button @click="iniciarExpediente" class="payment-button">Iniciar Expediente</button>
      </div>
    </div>

    <div v-else>
      <!-- Tarjetas de Estadísticas -->
      <div class="stats-container">
        <div v-for="(stat, index) in stats" :key="index" class="stat-card" :class="`animate-fade-in-${index + 1}`">
          <div class="stat-content">
            <h3 class="stat-label">{{ stat.label }}</h3>
            <p class="stat-value" :style="{ color: stat.color }">${{ stat.value.toFixed(2) }}</p>
          </div>
          <div class="stat-bar" :style="{ backgroundColor: stat.color }"></div>
        </div>
      </div>

      <div class="details-container">
        <!-- Panel de Desglose -->
        <div class="glass-panel animate-slide-in-left">
          <h3 class="section-title">Desglose de Costos</h3>
          <div class="cost-items">
            <div v-for="(item, index) in desglose" :key="index" 
                 class="cost-item" 
                 :class="{ 'total-row': item.label === 'Total General' }">
              <span class="cost-label">{{ item.label }}:</span>
              <span class="cost-value" :style="{ color: item.color }">${{ item.value.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Panel de Acciones -->
        <div class="glass-panel animate-slide-in-right">
          <h3 class="section-title">Acciones</h3>
          <div class="action-buttons">
            <button @click="showAbonoModal = true" class="action-button primary">
              Agregar Abono
            </button>
            <button @click="showExtrasModal = true" class="action-button secondary">
              Agregar Extra
            </button>
          </div>
        </div>
      </div>

      <!-- Historial de Movimientos -->
      <div class="payment-history glass-panel animate-fade-in-up">
        <h3 class="section-title">Historial de Movimientos</h3>
        <div class="history-tabs">
          <button 
            :class="['tab-button', { active: activeTab === 'abonos' }]"
            @click="activeTab = 'abonos'"
          >
            Abonos
          </button>
          <button 
            :class="['tab-button', { active: activeTab === 'extras' }]"
            @click="activeTab = 'extras'"
          >
            Extras
          </button>
        </div>
        <table class="history-table">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Concepto</th>
              <th>Monto</th>
              <th>Impuestos</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(movimiento, index) in movimientosFiltrados" :key="index">
              <td>{{ formatDate(movimiento.fecha) }}</td>
              <td>{{ movimiento.concepto || 'Abono' }}</td>
              <td>${{ movimiento.monto.toFixed(2) }}</td>
              <td>${{ movimiento.impuestos.toFixed(2) }}</td>
              <td>${{ (movimiento.monto + movimiento.impuestos).toFixed(2) }}</td>
              <td>
                <button class="icon-button" @click="editarMovimiento(movimiento)">
                  ✏️
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal para Agregar/Editar Abono -->
    <div class="modal" v-if="showAbonoModal">
      <div class="modal-content glass-panel">
        <h3 class="section-title">{{ editingMovimiento ? 'Editar' : 'Agregar' }} Abono</h3>
        <div class="modal-form">
          <div class="input-group">
            <label>Monto</label>
            <input type="number" v-model="nuevoAbono.monto" class="payment-input" step="0.01">
          </div>
          <div class="input-group">
            <label>Fecha</label>
            <input type="date" v-model="nuevoAbono.fecha" class="payment-input">
          </div>
          <div class="input-group">
            <label>GST (%)</label>
            <input type="number" v-model="nuevoAbono.gstRate" class="payment-input" step="0.01">
          </div>
          <div class="input-group">
            <label>QST (%)</label>
            <input type="number" v-model="nuevoAbono.qstRate" class="payment-input" step="0.01">
          </div>
          <div class="modal-actions">
            <button @click="guardarAbono" class="payment-button">Guardar</button>
            <button @click="showAbonoModal = false" class="cancel-button">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para Agregar Extra -->
    <div class="modal" v-if="showExtrasModal">
      <div class="modal-content glass-panel">
        <h3 class="section-title">Agregar Servicio Extra</h3>
        <div class="modal-form">
          <div class="input-group">
            <label>Concepto</label>
            <input type="text" v-model="nuevoExtra.concepto" class="payment-input">
          </div>
          <div class="input-group">
            <label>Monto</label>
            <input type="number" v-model="nuevoExtra.monto" class="payment-input" step="0.01">
          </div>
          <div class="input-group">
            <label>Fecha</label>
            <input type="date" v-model="nuevoExtra.fecha" class="payment-input">
          </div>
          <div class="input-group">
            <label>GST (%)</label>
            <input type="number" v-model="nuevoExtra.gstRate" class="payment-input" step="0.01">
          </div>
          <div class="input-group">
            <label>QST (%)</label>
            <input type="number" v-model="nuevoExtra.qstRate" class="payment-input" step="0.01">
          </div>
          <div class="modal-actions">
            <button @click="guardarExtra" class="payment-button">Guardar</button>
            <button @click="showExtrasModal = false" class="cancel-button">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'HonorariosComponent',
  props: {
    honorarioId: {
      type: Number,
      required: false
    },
    initialData: {
      type: Object,
      required: false,
      default: () => ({})
    },
    expedienteId: {
      type: Number,
      required: false
    }
  },
  setup(props) {
    const expedienteIniciado = ref(false)
    const montoInicial = ref(0)
    const fechaApertura = ref('')
    const activeTab = ref('abonos')
    const showAbonoModal = ref(false)
    const showExtrasModal = ref(false)
    const editingMovimiento = ref(null)

    const abonos = ref([])
    const extras = ref([])

    const nuevoAbono = ref({
      monto: 0,
      fecha: new Date().toISOString().split('T')[0],
      gstRate: 5,
      qstRate: 9.975
    })

    const nuevoExtra = ref({
      concepto: '',
      monto: 0,
      fecha: new Date().toISOString().split('T')[0],
      gstRate: 5,
      qstRate: 9.975
    })

    // Cargar datos iniciales si existen
    const loadInitialData = () => {
      if (props.initialData && Object.keys(props.initialData).length > 0) {
        montoInicial.value = props.initialData.montoInicial
        fechaApertura.value = props.initialData.fechaApertura
        abonos.value = props.initialData.abonos || []
        extras.value = props.initialData.extras || []
        expedienteIniciado.value = true
      }
    }

    // Cargar datos del honorario desde el servidor
    const loadHonorarioData = async () => {
      if (props.honorarioId) {
        try {
          const response = await axios.get(`/honorarios/${props.honorarioId}/data`)
          const { honorario, abonos: abonosData, extras: extrasData } = response.data
          montoInicial.value = honorario.monto_inicial
          fechaApertura.value = honorario.fecha_apertura
          abonos.value = abonosData
          extras.value = extrasData
          expedienteIniciado.value = true
        } catch (error) {
          console.error('Error al cargar datos:', error)
        }
      }
    }

    const iniciarExpediente = async () => {
      if (montoInicial.value > 0 && fechaApertura.value) {
        try {
          const response = await axios.post('/honorarios', {
            expediente_id: props.expedienteId,
            monto_inicial: montoInicial.value,
            fecha_apertura: fechaApertura.value
          })
          expedienteIniciado.value = true
          // Actualizar el honorarioId si es necesario
          if (response.data.id) {
            props.honorarioId = response.data.id
          }
        } catch (error) {
          console.error('Error al iniciar expediente:', error)
          alert('Error al iniciar el expediente. Por favor, intente nuevamente.')
        }
      }
    }

    const totalAbonos = computed(() => {
      return abonos.value.reduce((total, abono) => total + Number(abono.monto), 0)
    })

    const totalImpuestos = computed(() => {
      const impuestosAbonos = abonos.value.reduce((total, abono) => total + Number(abono.impuestos), 0)
      const impuestosExtras = extras.value.reduce((total, extra) => total + Number(extra.impuestos), 0)
      return impuestosAbonos + impuestosExtras
    })

    const totalExtras = computed(() => {
      return extras.value.reduce((total, extra) => total + Number(extra.monto), 0)
    })

    const saldoPendiente = computed(() => {
      return montoInicial.value - totalAbonos.value
    })

    const totalGeneral = computed(() => {
      return montoInicial.value + totalExtras.value + totalImpuestos.value
    })

    const stats = computed(() => [
      { 
        label: 'Monto Inicial', 
        value: montoInicial.value, 
        color: '#3b82f6' 
      },
      { 
        label: 'Saldo Pendiente', 
        value: saldoPendiente.value, 
        color: '#ef4444' 
      },
      { 
        label: 'Total Abonos', 
        value: totalAbonos.value, 
        color: '#10b981' 
      },
      { 
        label: 'Total General', 
        value: totalGeneral.value, 
        color: '#f59e0b' 
      }
    ])

    const desglose = computed(() => [
      { 
        label: 'Monto Inicial', 
        value: montoInicial.value, 
        color: '#ffffff' 
      },
      { 
        label: 'Saldo Pendiente', 
        value: saldoPendiente.value, 
        color: '#ef4444' 
      },
      { 
        label: 'Total Abonos', 
        value: totalAbonos.value, 
        color: '#10b981' 
      },
      { 
        label: 'Total Impuestos', 
        value: totalImpuestos.value, 
        color: '#93c5fd' 
      },
      { 
        label: 'Total Extras', 
        value: totalExtras.value, 
        color: '#8b5cf6' 
      },
      { 
        label: 'Total General', 
        value: totalGeneral.value, 
        color: '#f59e0b',
        className: 'font-bold text-lg' 
      }
    ])

    const movimientosFiltrados = computed(() => {
      return activeTab.value === 'abonos' ? abonos.value : extras.value
    })

    const guardarAbono = async () => {
      if (nuevoAbono.value.monto > 0) {
        try {
          const datos = {
            monto: nuevoAbono.value.monto,
            fecha: nuevoAbono.value.fecha,
            gst_rate: nuevoAbono.value.gstRate,
            qst_rate: nuevoAbono.value.qstRate
          }

          let response
          if (editingMovimiento.value) {
            response = await axios.put(
              `/honorarios/${props.honorarioId}/abonos/${editingMovimiento.value.id}`,
              datos
            )
            const index = abonos.value.findIndex(a => a.id === editingMovimiento.value.id)
            if (index !== -1) {
              abonos.value[index] = response.data
            }
          } else {
            response = await axios.post(`/honorarios/${props.honorarioId}/abonos`, datos)
            abonos.value.push(response.data)
          }
          
          showAbonoModal.value = false
          resetNuevoAbono()
        } catch (error) {
          console.error('Error al guardar abono:', error)
          alert('Error al guardar el abono. Por favor, intente nuevamente.')
        }
      }
    }

    const guardarExtra = async () => {
      if (nuevoExtra.value.monto > 0 && nuevoExtra.value.concepto) {
        try {
          const datos = {
            concepto: nuevoExtra.value.concepto,
            monto: nuevoExtra.value.monto,
            fecha: nuevoExtra.value.fecha,
            gst_rate: nuevoExtra.value.gstRate,
            qst_rate: nuevoExtra.value.qstRate
          }

          const response = await axios.post(`/honorarios/${props.honorarioId}/extras`, datos)
          extras.value.push(response.data)
          showExtrasModal.value = false
          resetNuevoExtra()
        } catch (error) {
          console.error('Error al guardar extra:', error)
          alert('Error al guardar el servicio extra. Por favor, intente nuevamente.')
        }
      }
    }

    const editarMovimiento = (movimiento) => {
      editingMovimiento.value = movimiento
      nuevoAbono.value = {
        monto: movimiento.monto,
        fecha: movimiento.fecha,
        gstRate: movimiento.gst_rate,
        qstRate: movimiento.qst_rate
      }
      showAbonoModal.value = true
    }

    const resetNuevoAbono = () => {
      nuevoAbono.value = {
        monto: 0,
        fecha: new Date().toISOString().split('T')[0],
        gstRate: 5,
        qstRate: 9.975
      }
      editingMovimiento.value = null
    }

    const resetNuevoExtra = () => {
      nuevoExtra.value = {
        concepto: '',
        monto: 0,
        fecha: new Date().toISOString().split('T')[0],
        gstRate: 5,
        qstRate: 9.975
      }
    }

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    }

    // Cargar datos cuando el componente se monta
    onMounted(() => {
      loadInitialData()
      if (props.honorarioId && !props.initialData) {
        loadHonorarioData()
      }
    })

    return {
      expedienteIniciado,
      montoInicial,
      fechaApertura,
      activeTab,
      showAbonoModal,
      showExtrasModal,
      editingMovimiento,
      nuevoAbono,
      nuevoExtra,
      stats,
      desglose,
      movimientosFiltrados,
      iniciarExpediente,
      guardarAbono,
      guardarExtra,
      editarMovimiento,
      formatDate
    }
  }
}
</script>

<style scoped>
.honorarios-dashboard {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
  padding: 2rem;
  border-radius: 1rem;
  color: #ffffff;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.dashboard-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  background: linear-gradient(to right, #a78bfa, #ec4899);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 0.75rem;
  overflow: hidden;
  backdrop-filter: blur(10px);
  transition: transform 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
}

.stat-content {
  padding: 1.5rem;
}

.stat-label {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #e2e8f0;
}

.stat-value {
  font-size: 1.875rem;
  font-weight: 700;
}

.stat-bar {
  height: 4px;
  width: 100%;
}

.details-container {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.glass-panel {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 0.75rem;
  padding: 1.5rem;
  backdrop-filter: blur(10px);
  flex: 1;
  min-width: 300px;
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: #a78bfa;
}

.cost-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cost-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
}

.cost-item.total-row {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.cost-label {
  color: #e2e8f0;
}

.cost-value {
  font-weight: 600;
}

.setup-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.input-group label {
  font-size: 0.875rem;
  color: #e2e8f0;
}

.payment-input {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 0.5rem;
  padding: 0.5rem;
  color: white;
  outline: none;
  transition: border-color 0.3s ease;
}

.payment-input:focus {
  border-color: #a78bfa;
}

.payment-button {
  background: linear-gradient(to right, #3b82f6, #8b5cf6);
  color: white;
  border: none;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.payment-button:hover {
  transform: translateY(-2px);
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.action-buttons {
  display: flex;
  gap: 1rem;
}

.action-button {
  flex: 1;
  padding: 0.75rem;
  border-radius: 0.5rem;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.action-button.primary {
  background: linear-gradient(to right, #3b82f6, #8b5cf6);
  color: white;
}

.action-button.secondary {
  background: linear-gradient(to right, #10b981, #059669);
  color: white;
}

.history-tabs {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.tab-button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  background: rgba(255, 255, 255, 0.1);
  color: #e2e8f0;
  cursor: pointer;
  transition: all 0.3s ease;
}

.tab-button.active {
  background: rgba(255, 255, 255, 0.2);
  color: white;
}

.history-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.history-table th,
.history-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.history-table th {
  font-weight: 600;
  color: #e2e8f0;
}

.icon-button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: all 0.3s ease;
}

.icon-button:hover {
  background: rgba(255, 255, 255, 0.1);
}

.cancel-button {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border: none;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.cancel-button:hover {
  background: rgba(255, 255, 255, 0.2);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInLeft {
  from { opacity: 0; transform: translateX(-50px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInRight {
  from { opacity: 0; transform: translateX(50px); }
  to { opacity: 1; transform: translateX(0); }
}

.animate-fade-in-1 { animation: fadeIn 0.6s ease-out 0.1s both; }
.animate-fade-in-2 { animation: fadeIn 0.6s ease-out 0.2s both; }
.animate-fade-in-3 { animation: fadeIn 0.6s ease-out 0.3s both; }
.animate-slide-in-left { animation: slideInLeft 0.6s ease-out both; }
.animate-slide-in-right { animation: slideInRight 0.6s ease-out both; }
.animate-fade-in-up { animation: fadeIn 0.6s ease-out 0.4s both; }

@media (max-width: 768px) {
  .stats-container {
    grid-template-columns: 1fr;
  }

  .details-container {
    flex-direction: column;
  }

  .modal-content {
    width: 95%;
    margin: 1rem;
  }
}
</style>
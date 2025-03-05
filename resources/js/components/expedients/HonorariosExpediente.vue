<template>
  <div class="honorarios-dashboard">
    <h2 class="dashboard-title">Gestión de Honorarios</h2>

    <!-- Panel de Apertura de Honorarios -->
    <div class="glass-panel animate-fade-in-1" v-if="!expedienteIniciado">
      <h3 class="section-title">Apertura de Expediente</h3>
      <div v-if="cargando" class="loading-indicator">
        <i class="bi bi-arrow-repeat spin"></i> Cargando...
      </div>
      <div v-else class="setup-form">
        <div class="input-group">
          <label>Monto Total Acordado</label>
          <input 
            type="number" 
            v-model="montoInicial" 
            class="payment-input" 
            placeholder="Monto inicial"
            :disabled="!puedeEditar"
          >
          <small v-if="errorMonto" class="error-text">{{ errorMonto }}</small>
        </div>
        <div class="input-group">
          <label>Fecha de Apertura</label>
          <input 
            type="date" 
            v-model="fechaApertura" 
            class="payment-input"
            :disabled="!puedeEditar"
          >
          <small v-if="errorFecha" class="error-text">{{ errorFecha }}</small>
        </div>
        <button 
          @click="iniciarExpediente" 
          class="payment-button"
          :disabled="!puedeEditar || cargando"
        >
          <span v-if="cargando"><i class="bi bi-arrow-repeat spin"></i> Procesando...</span>
          <span v-else>Iniciar Expediente</span>
        </button>
      </div>
    </div>

    <div v-else>
      <!-- Tarjetas de Estadísticas -->
      <div class="stats-container">
        <div v-for="(stat, index) in stats" :key="index" class="stat-card" :class="`animate-fade-in-${index + 1}`">
          <div class="stat-content">
            <h3 class="stat-label">{{ stat.label }}</h3>
            <p class="stat-value" :style="{ color: stat.color }">${{ formatMoney(stat.value) }}</p>
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
              <span class="cost-value" :style="{ color: item.color }">${{ formatMoney(item.value) }}</span>
            </div>
          </div>
        </div>

        <!-- Panel de Acciones -->
        <div class="glass-panel animate-slide-in-right">
          <h3 class="section-title">Acciones</h3>
          <div class="action-buttons">
            <button 
              @click="showAbonoModal = true" 
              class="action-button primary"
              :disabled="!puedeEditar"
            >
              <i class="bi bi-plus-circle"></i>
              Agregar Abono
            </button>
            <button 
              @click="showExtrasModal = true" 
              class="action-button secondary"
              :disabled="!puedeEditar"
            >
              <i class="bi bi-plus-square"></i>
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
        <div class="table-container">
          <table class="history-table">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Monto</th>
                <th>GST (5%)</th>
                <th>QST (9.975%)</th>
                <th>Total</th>
                <th v-if="puedeEditar">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(movimiento, index) in movimientosFiltrados" :key="index">
                <td>{{ formatDate(movimiento.fecha) }}</td>
                <td>{{ movimiento.concepto || 'Abono' }}</td>
                <td>${{ formatMoney(movimiento.monto) }}</td>
                <td>${{ formatMoney(movimiento.monto * (movimiento.gst_rate || 5) / 100) }}</td>
                <td>${{ formatMoney(movimiento.monto * (movimiento.qst_rate || 9.975) / 100) }}</td>
                <td>${{ formatMoney(calcularTotal(movimiento)) }}</td>
                <td v-if="puedeEditar" class="actions-cell">
                  <button class="icon-button edit" @click="editarMovimiento(movimiento)">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="icon-button delete" @click="eliminarMovimiento(movimiento)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="movimientosFiltrados.length === 0">
                <td colspan="7" class="text-center">No hay registros disponibles</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modales -->
    <div v-if="showAbonoModal" class="modal">
      <div class="modal-backdrop" @click="closeAbonoModal"></div>
      <div class="modal-content glass-panel">
        <div class="modal-header">
          <h3 class="section-title">
            {{ editingMovimiento ? 'Editar' : 'Agregar' }} Abono
          </h3>
          <button class="close-button" @click="closeAbonoModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="modal-form">
          <div class="input-group">
            <label>Monto</label>
            <input type="number" v-model="nuevoAbono.monto" class="payment-input" step="0.01">
            <small v-if="errorAbonoMonto" class="error-text">{{ errorAbonoMonto }}</small>
          </div>
          <div class="input-group">
            <label>Fecha</label>
            <input type="date" v-model="nuevoAbono.fecha" class="payment-input">
            <small v-if="errorAbonoFecha" class="error-text">{{ errorAbonoFecha }}</small>
          </div>
          <div class="input-group">
            <label>GST (%)</label>
            <input type="number" v-model="nuevoAbono.gstRate" class="payment-input" step="0.01">
          </div>
          <div class="input-group">
            <label>QST (%)</label>
            <input type="number" v-model="nuevoAbono.qstRate" class="payment-input" step="0.01">
          </div>
          <div class="taxes-preview" v-if="nuevoAbono.monto > 0">
            <p>GST ({{ nuevoAbono.gstRate }}%): ${{ formatMoney(nuevoAbono.monto * (nuevoAbono.gstRate / 100)) }}</p>
            <p>QST ({{ nuevoAbono.qstRate }}%): ${{ formatMoney(nuevoAbono.monto * (nuevoAbono.qstRate / 100)) }}</p>
            <p class="total">Total: ${{ formatMoney(calcularTotalNuevoAbono()) }}</p>
          </div>
          <div class="modal-actions">
            <button @click="guardarAbono" class="payment-button" :disabled="cargando">
              <span v-if="cargando"><i class="bi bi-arrow-repeat spin"></i> Procesando...</span>
              <span v-else>{{ editingMovimiento ? 'Actualizar' : 'Guardar' }}</span>
            </button>
            <button @click="closeAbonoModal" class="cancel-button" :disabled="cargando">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para Agregar Extra -->
    <div v-if="showExtrasModal" class="modal">
      <div class="modal-backdrop" @click="closeExtrasModal"></div>
      <div class="modal-content glass-panel">
        <div class="modal-header">
          <h3 class="section-title">
            {{ editingMovimiento ? 'Editar' : 'Agregar' }} Servicio Extra
          </h3>
          <button class="close-button" @click="closeExtrasModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="modal-form">
          <div class="input-group">
            <label>Concepto</label>
            <input type="text" v-model="nuevoExtra.concepto" class="payment-input">
            <small v-if="errorExtraConcepto" class="error-text">{{ errorExtraConcepto }}</small>
          </div>
          <div class="input-group">
            <label>Monto</label>
            <input type="number" v-model="nuevoExtra.monto" class="payment-input" step="0.01">
            <small v-if="errorExtraMonto" class="error-text">{{ errorExtraMonto }}</small>
          </div>
          <div class="input-group">
            <label>Fecha</label>
            <input type="date" v-model="nuevoExtra.fecha" class="payment-input">
            <small v-if="errorExtraFecha" class="error-text">{{ errorExtraFecha }}</small>
          </div>
          <div class="input-group">
            <label>GST (%)</label>
            <input type="number" v-model="nuevoExtra.gstRate" class="payment-input" step="0.01">
          </div>
          <div class="input-group">
            <label>QST (%)</label>
            <input type="number" v-model="nuevoExtra.qstRate" class="payment-input" step="0.01">
          </div>
          <div class="taxes-preview" v-if="nuevoExtra.monto > 0">
            <p>GST ({{ nuevoExtra.gstRate }}%): ${{ formatMoney(nuevoExtra.monto * (nuevoExtra.gstRate / 100)) }}</p>
            <p>QST ({{ nuevoExtra.qstRate }}%): ${{ formatMoney(nuevoExtra.monto * (nuevoExtra.qstRate / 100)) }}</p>
            <p class="total">Total: ${{ formatMoney(calcularTotalNuevoExtra()) }}</p>
          </div>
          <div class="modal-actions">
            <button @click="guardarExtra" class="payment-button" :disabled="cargando">
              <span v-if="cargando"><i class="bi bi-arrow-repeat spin"></i> Procesando...</span>
              <span v-else>{{ editingMovimiento ? 'Actualizar' : 'Guardar' }}</span>
            </button>
            <button @click="closeExtrasModal" class="cancel-button" :disabled="cargando">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

export default {
  name: 'HonorariosExpediente',
  props: {
    expediente: {
      type: Object,
      required: true
    },
    expedienteId: {
      type: Number,
      required: false,
      default: null
    },
    userRole: {
      type: String,
      required: false,
      default: 'USER'
    }
  },
  emits: ['honorario-actualizado'],
  setup(props, { emit }) {
    // Estado general
    const cargando = ref(false)
    const expedienteIniciado = ref(false)
    const montoInicial = ref(0)
    const fechaApertura = ref(new Date().toISOString().split('T')[0])
    const activeTab = ref('abonos')
    
    // Estado de modales
    const showAbonoModal = ref(false)
    const showExtrasModal = ref(false)
    const editingMovimiento = ref(null)

    // Datos
    const honorario = ref(null)
    const abonos = ref([])
    const extras = ref([])

    // Mensajes de error
    const errorMonto = ref('')
    const errorFecha = ref('')
    const errorAbonoMonto = ref('')
    const errorAbonoFecha = ref('')
    const errorExtraConcepto = ref('')
    const errorExtraMonto = ref('')
    const errorExtraFecha = ref('')

    // Formularios
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

    // Permisos
    const puedeEditar = computed(() => {
      return props.expediente.estatus_del_expediente !== 'Cerrado' || 
             ['DIRECTOR', 'ADMIN'].includes(props.userRole);
    });

    // Cargar datos del honorario
    const loadHonorarioData = async () => {
      cargando.value = true
      errorMonto.value = ''
      errorFecha.value = ''
      
      try {
        console.log('Cargando datos de honorario...')
        
        // Obtenemos el ID del expediente
        const expedienteId = props.expedienteId || props.expediente?.id
        
        if (!expedienteId) {
          console.error('No se proporcionó un ID de expediente válido')
          throw new Error('ID de expediente no válido')
        }
        
        // Primero verificamos si existe un honorario para este expediente
        const response = await axios.get(`/expedientes/${expedienteId}/honorarios`)
        console.log('Respuesta de honorarios:', response.data)
        
        if (response.data && response.data.length > 0) {
          honorario.value = response.data[0]
          console.log('Honorario encontrado:', honorario.value)
          montoInicial.value = parseFloat(honorario.value.monto_total_expediente) || 0
          fechaApertura.value = honorario.value.created_at?.split('T')[0] || new Date().toISOString().split('T')[0]
          expedienteIniciado.value = true
          
          // Cargar abonos y extras
          if (honorario.value.id) {
            try {
              const [abonosResponse, extrasResponse] = await Promise.all([
                axios.get(`/honorarios/${honorario.value.id}/abonos`),
                axios.get(`/honorarios/${honorario.value.id}/extras`)
              ])
              
              console.log('Respuesta de abonos:', abonosResponse.data)
              console.log('Respuesta de extras:', extrasResponse.data)
              
              abonos.value = abonosResponse.data || []
              extras.value = extrasResponse.data || []
              
              // Emitir evento para actualizar el honorario en el componente padre
              emit('honorario-actualizado', honorario.value)
            } catch (error) {
              console.error('Error al cargar abonos y extras:', error)
              // No lanzamos el error aquí para permitir que se muestre el honorario aunque fallen los abonos
            }
          }
        } else {
          console.log('No se encontraron honorarios para este expediente')
          expedienteIniciado.value = false
          montoInicial.value = 0
          fechaApertura.value = new Date().toISOString().split('T')[0]
        }
      } catch (error) {
        console.error('Error al cargar datos:', error)
        expedienteIniciado.value = false
        // Mostrar mensaje de error más amigable
        if (error.response?.status === 404) {
          console.error('No se encontró el expediente especificado.')
        } else {
          console.error('Ocurrió un error al cargar los datos. Por favor, intente nuevamente.')
        }
      } finally {
        cargando.value = false
      }
    }

    const iniciarExpediente = async () => {
      // Validación
      errorMonto.value = ''
      errorFecha.value = ''
      
      if (!montoInicial.value || montoInicial.value <= 0) {
        errorMonto.value = 'Por favor, ingrese un monto inicial válido.'
        return
      }

      if (!fechaApertura.value) {
        errorFecha.value = 'Por favor, seleccione una fecha de apertura.'
        return
      }

      // Obtener el ID del expediente
      const expedienteId = props.expedienteId || props.expediente?.id
      if (!expedienteId) {
        console.error('No se proporcionó un ID de expediente válido')
        alert('Error: No se pudo determinar el ID del expediente.')
        return
      }

      cargando.value = true
      try {
        console.log('Iniciando expediente con ID:', expedienteId)
        console.log('Datos a enviar:', {
          expediente_id: expedienteId,
          monto_total_expediente: parseFloat(montoInicial.value),
          monto_adicional: 0,
          monto_total_a_pagar: parseFloat(montoInicial.value),
          total_abonos: 0,
          saldo_pendiente: parseFloat(montoInicial.value),
          estado: 'pendiente',
          usuario_id: props.expediente.usuario_id || null
        })
        
        const response = await axios.post('/honorarios', {
          expediente_id: expedienteId,
          monto_total_expediente: parseFloat(montoInicial.value),
          monto_adicional: 0,
          monto_total_a_pagar: parseFloat(montoInicial.value),
          total_abonos: 0,
          saldo_pendiente: parseFloat(montoInicial.value),
          estado: 'pendiente',
          usuario_id: props.expediente.usuario_id || null
        })
        
        console.log('Respuesta del servidor:', response.data)
        
        if (response.data && response.data.id) {
          honorario.value = response.data
          expedienteIniciado.value = true
          emit('honorario-actualizado', response.data)
          
          // Recargar los datos del honorario
          await loadHonorarioData()
        } else {
          throw new Error('La respuesta del servidor no incluye el ID del honorario.')
        }
      } catch (error) {
        console.error('Error al iniciar expediente:', error)
        alert('Error al iniciar el expediente. Por favor, intente nuevamente.')
      } finally {
        cargando.value = false
      }
    }

    // Cálculos
    const totalAbonos = computed(() => {
      return abonos.value.reduce((total, abono) => total + parseFloat(abono.monto || 0), 0)
    })

    const totalImpuestos = computed(() => {
      const impuestosAbonos = abonos.value.reduce((total, abono) => {
        const monto = parseFloat(abono.monto || 0)
        const gstRate = parseFloat(abono.gst_rate || 5)
        const qstRate = parseFloat(abono.qst_rate || 9.975)
        return total + (monto * (gstRate / 100)) + (monto * (qstRate / 100))
      }, 0)
      
      const impuestosExtras = extras.value.reduce((total, extra) => {
        const monto = parseFloat(extra.monto || 0)
        const gstRate = parseFloat(extra.gst_rate || 5)
        const qstRate = parseFloat(extra.qst_rate || 9.975)
        return total + (monto * (gstRate / 100)) + (monto * (qstRate / 100))
      }, 0)
      
      return impuestosAbonos + impuestosExtras
    })

    const totalExtras = computed(() => {
      return extras.value.reduce((total, extra) => total + parseFloat(extra.monto || 0), 0)
    })

    const saldoPendiente = computed(() => {
      return parseFloat(montoInicial.value) - parseFloat(totalAbonos.value)
    })

    const totalGeneral = computed(() => {
      const montoBase = parseFloat(montoInicial.value) || 0
      const extrasTotal = parseFloat(totalExtras.value) || 0
      const impuestosTotal = parseFloat(totalImpuestos.value) || 0
      return montoBase + extrasTotal + impuestosTotal
    })

    // Stats y desglose
    const stats = computed(() => [
      { 
        label: 'Monto Inicial', 
        value: parseFloat(montoInicial.value) || 0, 
        color: '#3b82f6' 
      },
      { 
        label: 'Saldo Pendiente', 
        value: parseFloat(saldoPendiente.value) || 0, 
        color: '#ef4444' 
      },
      { 
        label: 'Total Abonado', 
        value: parseFloat(totalAbonos.value) || 0, 
        color: '#10b981' 
      },
      { 
        label: 'Total General', 
        value: parseFloat(totalGeneral.value) || 0, 
        color: '#f59e0b' 
      }
    ])

    const desglose = computed(() => [
      { 
        label: 'Monto Inicial', 
        value: parseFloat(montoInicial.value) || 0, 
        color: '#3b82f6' 
      },
      { 
        label: 'Total Abonado', 
        value: parseFloat(totalAbonos.value) || 0, 
        color: '#10b981' 
      },
      { 
        label: 'Saldo Pendiente', 
        value: parseFloat(saldoPendiente.value) || 0, 
        color: '#ef4444' 
      },
      { 
        label: 'Total Impuestos', 
        value: parseFloat(totalImpuestos.value) || 0, 
        color: '#93c5fd' 
      },
      { 
        label: 'Total Extras', 
        value: parseFloat(totalExtras.value) || 0, 
        color: '#8b5cf6' 
      },
      { 
        label: 'Total General', 
        value: parseFloat(totalGeneral.value) || 0, 
        color: '#f59e0b',
        isTotal: true
      }
    ])

    const movimientosFiltrados = computed(() => {
      return activeTab.value === 'abonos' ? abonos.value : extras.value
    })

    // Métodos auxiliares
    const calcularTotal = (movimiento) => {
      const monto = parseFloat(movimiento.monto || 0)
      const gstRate = parseFloat(movimiento.gst_rate || 5)
      const qstRate = parseFloat(movimiento.qst_rate || 9.975)
      const gst = monto * (gstRate / 100)
      const qst = monto * (qstRate / 100)
      return monto + gst + qst
    }

    const calcularTotalNuevoAbono = () => {
      const monto = parseFloat(nuevoAbono.value.monto || 0)
      const gstRate = parseFloat(nuevoAbono.value.gstRate || 5)
      const qstRate = parseFloat(nuevoAbono.value.qstRate || 9.975)
      const gst = monto * (gstRate / 100)
      const qst = monto * (qstRate / 100)
      return monto + gst + qst
    }

    const calcularTotalNuevoExtra = () => {
      const monto = parseFloat(nuevoExtra.value.monto || 0)
      const gstRate = parseFloat(nuevoExtra.value.gstRate || 5)
      const qstRate = parseFloat(nuevoExtra.value.qstRate || 9.975)
      const gst = monto * (gstRate / 100)
      const qst = monto * (qstRate / 100)
      return monto + gst + qst
    }

    // Métodos para guardar datos
    const guardarAbono = async () => {
      // Validación
      errorAbonoMonto.value = ''
      errorAbonoFecha.value = ''
      
      if (!nuevoAbono.value.monto || nuevoAbono.value.monto <= 0) {
        errorAbonoMonto.value = 'Por favor, ingrese un monto válido.'
        return
      }
      
      if (!nuevoAbono.value.fecha) {
        errorAbonoFecha.value = 'Por favor, seleccione una fecha.'
        return
      }
      
      cargando.value = true
      try {
        if (!honorario.value || !honorario.value.id) {
          alert('No se pudo determinar el ID del honorario.')
          return
        }
        
        const monto = parseFloat(nuevoAbono.value.monto)
        const gstRate = parseFloat(nuevoAbono.value.gstRate)
        const qstRate = parseFloat(nuevoAbono.value.qstRate)
        const impuestos = (monto * gstRate / 100) + (monto * qstRate / 100)
        
        const datos = {
          honorario_id: honorario.value.id,
          monto: monto,
          fecha: nuevoAbono.value.fecha,
          gst_rate: gstRate,
          qst_rate: qstRate,
          impuestos: impuestos,
          usuario_id: props.expediente.usuario_id || null
        }

        console.log('Guardando abono para honorario ID:', honorario.value.id)
        console.log('Datos a enviar:', datos)

        let response
        if (editingMovimiento.value) {
          response = await axios.put(
            `/honorarios/abonos/${editingMovimiento.value.id}`,
            datos
          )
          console.log('Respuesta de actualización de abono:', response.data)
          
          const index = abonos.value.findIndex(a => a.id === editingMovimiento.value.id)
          if (index !== -1) {
            abonos.value[index] = response.data
          }
        } else {
          response = await axios.post(`/honorarios/abonos`, datos)
          console.log('Respuesta de creación de abono:', response.data)
          abonos.value.push(response.data)
        }
        
        // Actualizar el honorario con el nuevo saldo
        await actualizarSaldoHonorario()
        
        closeAbonoModal()
      } catch (error) {
        console.error('Error al guardar abono:', error)
        alert('Error al guardar el abono. Por favor, intente nuevamente.')
      } finally {
        cargando.value = false
      }
    }

    const guardarExtra = async () => {
      // Validación
      errorExtraConcepto.value = ''
      errorExtraMonto.value = ''
      errorExtraFecha.value = ''
      
      if (!nuevoExtra.value.concepto) {
        errorExtraConcepto.value = 'Por favor, ingrese un concepto para el servicio extra.'
        return
      }
      
      if (!nuevoExtra.value.monto || nuevoExtra.value.monto <= 0) {
        errorExtraMonto.value = 'Por favor, ingrese un monto válido.'
        return
      }
      
      if (!nuevoExtra.value.fecha) {
        errorExtraFecha.value = 'Por favor, seleccione una fecha.'
        return
      }
      
      cargando.value = true
      try {
        if (!honorario.value || !honorario.value.id) {
          alert('No se pudo determinar el ID del honorario.')
          return
        }
        
        const monto = parseFloat(nuevoExtra.value.monto)
        const gstRate = parseFloat(nuevoExtra.value.gstRate)
        const qstRate = parseFloat(nuevoExtra.value.qstRate)
        const impuestos = (monto * gstRate / 100) + (monto * qstRate / 100)
        
        const datos = {
          honorario_id: honorario.value.id,
          concepto: nuevoExtra.value.concepto,
          monto: monto,
          fecha: nuevoExtra.value.fecha,
          gst_rate: gstRate,
          qst_rate: qstRate,
          impuestos: impuestos,
          usuario_id: props.expediente.usuario_id || null
        }

        console.log('Guardando extra para honorario ID:', honorario.value.id)
        console.log('Datos a enviar:', datos)

        let response
        if (editingMovimiento.value) {
          response = await axios.put(
            `/honorarios/extras/${editingMovimiento.value.id}`,
            datos
          )
          console.log('Respuesta de actualización de extra:', response.data)
          
          const index = extras.value.findIndex(e => e.id === editingMovimiento.value.id)
          if (index !== -1) {
            extras.value[index] = response.data
          }
        } else {
          response = await axios.post(`/honorarios/extras`, datos)
          console.log('Respuesta de creación de extra:', response.data)
          extras.value.push(response.data)
        }
        
        // Actualizar el honorario con el nuevo monto adicional
        await actualizarMontoAdicionalHonorario()
        
        closeExtrasModal()
      } catch (error) {
        console.error('Error al guardar extra:', error)
        alert('Error al guardar el servicio extra. Por favor, intente nuevamente.')
      } finally {
        cargando.value = false
      }
    }
    
    // Eliminar movimiento (abono o extra)
    const eliminarMovimiento = async (movimiento) => {
      if (!confirm('¿Está seguro de eliminar este registro?')) {
        return
      }

      cargando.value = true
      try {
        const endpoint = activeTab.value === 'abonos' 
          ? `/honorarios/abonos/${movimiento.id}`
          : `/honorarios/extras/${movimiento.id}`

        await axios.delete(endpoint)
        
        if (activeTab.value === 'abonos') {
          abonos.value = abonos.value.filter(a => a.id !== movimiento.id)
          await actualizarSaldoHonorario()
        } else {
          extras.value = extras.value.filter(e => e.id !== movimiento.id)
          await actualizarMontoAdicionalHonorario()
        }
        
        console.log(`${activeTab.value === 'abonos' ? 'Abono' : 'Extra'} eliminado correctamente`)
      } catch (error) {
        console.error('Error al eliminar:', error)
        alert('Error al eliminar el registro. Por favor, intente nuevamente.')
      } finally {
        cargando.value = false
      }
    }
    
    // Actualizar saldo del honorario
    const actualizarSaldoHonorario = async () => {
      if (!honorario.value || !honorario.value.id) return
      
      try {
        const nuevoTotalAbonos = parseFloat(totalAbonos.value)
        const nuevoSaldoPendiente = parseFloat(montoInicial.value) - nuevoTotalAbonos
        const nuevoEstado = nuevoSaldoPendiente <= 0 ? 'pagado' : 'pendiente'
        
        const response = await axios.put(`/honorarios/${honorario.value.id}`, {
          total_abonos: nuevoTotalAbonos,
          saldo_pendiente: nuevoSaldoPendiente,
          estado: nuevoEstado
        })
        
        console.log('Honorario actualizado con nuevo saldo:', response.data)
        honorario.value = response.data
        emit('honorario-actualizado', honorario.value)
      } catch (error) {
        console.error('Error al actualizar saldo del honorario:', error)
      }
    }
    
    // Actualizar monto adicional del honorario
    const actualizarMontoAdicionalHonorario = async () => {
      if (!honorario.value || !honorario.value.id) return
      
      try {
        const nuevoMontoAdicional = parseFloat(totalExtras.value)
        const nuevoMontoTotalAPagar = parseFloat(montoInicial.value) + nuevoMontoAdicional + parseFloat(totalImpuestos.value)
        
        const response = await axios.put(`/honorarios/${honorario.value.id}`, {
          monto_adicional: nuevoMontoAdicional,
          monto_total_a_pagar: nuevoMontoTotalAPagar
        })
        
        console.log('Honorario actualizado con nuevo monto adicional:', response.data)
        honorario.value = response.data
        emit('honorario-actualizado', honorario.value)
      } catch (error) {
        console.error('Error al actualizar monto adicional del honorario:', error)
      }
    }

    // Métodos para manejar modales
    const editarMovimiento = (movimiento) => {
      editingMovimiento.value = movimiento
      
      if (activeTab.value === 'abonos') {
        nuevoAbono.value = {
          monto: parseFloat(movimiento.monto),
          fecha: movimiento.fecha,
          gstRate: parseFloat(movimiento.gst_rate || 5),
          qstRate: parseFloat(movimiento.qst_rate || 9.975)
        }
        showAbonoModal.value = true
      } else {
        nuevoExtra.value = {
          concepto: movimiento.concepto,
          monto: parseFloat(movimiento.monto),
          fecha: movimiento.fecha,
          gstRate: parseFloat(movimiento.gst_rate || 5),
          qstRate: parseFloat(movimiento.qst_rate || 9.975)
        }
        showExtrasModal.value = true
      }
    }

    const closeAbonoModal = () => {
      showAbonoModal.value = false
      editingMovimiento.value = null
      errorAbonoMonto.value = ''
      errorAbonoFecha.value = ''
      nuevoAbono.value = {
        monto: 0,
        fecha: new Date().toISOString().split('T')[0],
        gstRate: 5,
        qstRate: 9.975
      }
    }

    const closeExtrasModal = () => {
      showExtrasModal.value = false
      editingMovimiento.value = null
      errorExtraConcepto.value = ''
      errorExtraMonto.value = ''
      errorExtraFecha.value = ''
      nuevoExtra.value = {
        concepto: '',
        monto: 0,
        fecha: new Date().toISOString().split('T')[0],
        gstRate: 5,
        qstRate: 9.975
      }
    }

    // Formateo
    const formatDate = (date) => {
      if (!date) return 'N/A'
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    }

    const formatMoney = (amount) => {
      return parseFloat(amount || 0).toFixed(2)
    }

    // Lifecycle
    onMounted(() => {
      console.log('Componente HonorariosExpediente montado')
      loadHonorarioData()
    })

    // Observar cambios en las props
    watch(() => props.expedienteId, (newVal) => {
      console.log('Prop expedienteId cambió:', newVal)
      if (newVal) {
        loadHonorarioData()
      }
    })

    return {
      cargando,
      expedienteIniciado,
      montoInicial,
      fechaApertura,
      activeTab,
      showAbonoModal,
      showExtrasModal,
      editingMovimiento,
      nuevoAbono,
      nuevoExtra,
      puedeEditar,
      stats,
      desglose,
      movimientosFiltrados,
      errorMonto,
      errorFecha,
      errorAbonoMonto,
      errorAbonoFecha,
      errorExtraConcepto,
      errorExtraMonto,
      errorExtraFecha,
      iniciarExpediente,
      guardarAbono,
      guardarExtra,
      editarMovimiento,
      eliminarMovimiento,
      closeAbonoModal,
      closeExtrasModal,
      formatDate,
      formatMoney,
      calcularTotal,
      calcularTotalNuevoAbono,
      calcularTotalNuevoExtra
    }
  }
}
</script>

<style scoped>
.honorarios-dashboard {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(135deg, rgb(76, 21, 165) 0%, #f3e8ff 100%);
  padding: 2rem;
  border-radius: 1rem;
  color: #4a5568;
  box-shadow: 0 10px 15px -3px rgba(168, 85, 247, 0.1);
}

.dashboard-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  background: linear-gradient(to right, #ffffff, #d4cdda);
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
  background: #ffffff;
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(168, 85, 247, 0.1);
  transition: transform 0.3s ease;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 15px -3px rgba(168, 85, 247, 0.2);
  border-color: rgba(192, 38, 211, 0.2);
}

.stat-content {
  padding: 1.5rem;
}

.stat-label {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #718096;
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
  background: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(168, 85, 247, 0.1);
  flex: 1;
  min-width: 300px;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: #9333ea;
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
  border-top: 10px solid rgba(192, 38, 211, 0.2);
}

.cost-label {
  color: #4a5568;
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
  color: #4a5568;
}

.payment-input {
  background: #faf5ff;
  border: 1px solid rgba(192, 38, 211, 0.2);
  border-radius: 0.5rem;
  padding: 0.5rem;
  color: #2d3748;
  outline: none;
  transition: border-color 0.3s ease;
}

.payment-input:focus {
  border-color: #9333ea;
  box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.1);
}

.payment-button {
  background: linear-gradient(to right, #9333ea, #c026d3);
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
  box-shadow: 0 4px 6px -1px rgba(168, 85, 247, 0.3);
}

.payment-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
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
  z-index: 1050;
}

.modal-backdrop {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.modal-content {
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  z-index: 1051;
  background: #ffffff;
  border-radius: 0.75rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.close-button {
  background: none;
  border: none;
  color: #718096;
  font-size: 1.5rem;
  cursor: pointer;
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
  background: linear-gradient(to right, #9333ea, #c026d3);
  color: white;
}

.action-button.secondary {
  background: #ffffff;
  color: #9333ea;
  border: 1px solid #9333ea;
}

.action-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.history-tabs {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.tab-button {
  padding: 0.5rem 1rem;
  border: 1px solid rgba(192, 38, 211, 0.2);
  border-radius: 0.5rem;
  background: #ffffff;
  color: #718096;
  cursor: pointer;
  transition: all 0.3s ease;
}

.tab-button.active {
  background: #9333ea;
  color: white;
  border-color: #9333ea;
}

.table-container {
  overflow-x: auto;
}

.history-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.history-table th {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 2px solid rgba(192, 38, 211, 0.1);
  font-weight: 600;
  color: #4a5568;
  background: #faf5ff;
}

.history-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid rgba(192, 38, 211, 0.1);
  color: #4a5568;
}

.actions-cell {
  display: flex;
  gap: 0.5rem;
}

.icon-button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: all 0.3s ease;
  color: #718096;
}

.icon-button.edit:hover {
  background: rgba(147, 51, 234, 0.1);
  color: #9333ea;
}

.icon-button.delete:hover {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.cancel-button {
  background: #faf5ff;
  color: #4a5568;
  border: 1px solid #e2e8f0;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.cancel-button:hover {
  background: #f3e8ff;
}

.taxes-preview {
  margin-top: 1rem;
  padding: 1rem;
  background: #faf5ff;
  border-radius: 0.5rem;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.taxes-preview p {
  margin: 0.5rem 0;
  color: #4a5568;
}

.taxes-preview .total {
  font-weight: bold;
  margin-top: 0.5rem;
  padding-top: 0.5rem;
  border-top: 1px solid rgba(192, 38, 211, 0.1);
  color: #9333ea;
}

.error-text {
  color: #e53e3e;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.loading-indicator {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
  color: #718096;
}

.spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
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
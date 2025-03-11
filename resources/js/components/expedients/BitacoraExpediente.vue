<template>
  <div class="bitacora-dashboard">
    <div class="container-fluid">
      <h2 class="dashboard-title">Bitácora de Expediente</h2>
      
      <!-- Selector de modo de bitácora -->
      <div class="glass-panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="section-title mb-0">Crear nueva bitácora</h3>
          <button 
            @click="abrirModalCreacion" 
            class="action-button primary"
          >
            <i class="bi bi-plus-lg me-2"></i>
            Nueva bitácora
          </button>
        </div>
        
        <div class="row g-4">
          <div class="col-md-6">
            <button 
              @click="modoBitacora = 'normal'" 
              class="mode-button"
              :class="modoBitacora === 'normal' ? 'active' : ''"
            >
              <div class="mode-icon">📝</div>
              <h3 class="mode-title">Bitácora Simple</h3>
              <p class="mode-description">Registrar una acción ya completada</p>
            </button>
          </div>
          
          <div class="col-md-6">
            <button 
              @click="modoBitacora = 'seguimiento'" 
              class="mode-button"
              :class="modoBitacora === 'seguimiento' ? 'active' : ''"
            >
              <div class="mode-icon">🎫</div>
              <h3 class="mode-title">Bitácora de Seguimiento</h3>
              <p class="mode-description">Iniciar un proceso con fecha límite</p>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Historial de registros (siempre visible) -->
      <div class="glass-panel animate-fade-in-2 mt-4">
        <h3 class="section-title">Historial de bitácoras</h3>
        
        <!-- Filtros -->
        <div class="filter-tabs mb-3">
          <button 
            @click="filtroActual = 'todos'" 
            :class="['tab-button', { active: filtroActual === 'todos' }]"
          >
            Todos
          </button>
          <button 
            @click="filtroActual = 'normal'" 
            :class="['tab-button', { active: filtroActual === 'normal' }]"
          >
            Bitácoras simples
          </button>
          <button 
            @click="filtroActual = 'seguimiento'" 
            :class="['tab-button', { active: filtroActual === 'seguimiento' }]"
          >
            Procesos de seguimiento
          </button>
          <button 
            @click="filtroActual = 'pendientes'" 
            :class="['tab-button', { active: filtroActual === 'pendientes' }]"
          >
            Pendientes
          </button>
          <button 
            @click="filtroActual = 'completados'" 
            :class="['tab-button', { active: filtroActual === 'completados' }]"
          >
            Completados
          </button>
          <button 
            @click="filtroActual = 'proximos'" 
            :class="['tab-button', { active: filtroActual === 'proximos' }]"
          >
            Próximos a vencer
          </button>
          <button 
            @click="filtroActual = 'reactivados'" 
            :class="['tab-button', { active: filtroActual === 'reactivados' }]"
          >
            Reactivados
          </button>
        </div>
        
        <!-- Indicador de carga -->
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
          <p class="mt-2">Cargando bitácoras...</p>
        </div>
        
        <!-- Mensaje de error -->
        <div v-else-if="error" class="alert alert-danger" role="alert">
          {{ error }}
          <button @click="cargarBitacoras" class="btn btn-sm btn-outline-danger ms-2">
            Reintentar
          </button>
        </div>
        
        <!-- Lista de bitácoras -->
        <div v-else-if="historialRegistros.length > 0" class="bitacora-list">
          <div v-for="(reg, index) in registrosFiltrados" :key="reg.id" 
               class="bitacora-item animate-fade-in-up"
               :class="{
                 'danger-item': esFechaProximaAVencer(reg) && reg.estado !== 'completado',
                 'info-item': reg.fecha_reactivacion
               }">
            <div class="d-flex justify-content-between align-items-center position-relative">
              <div class="d-flex align-items-center cursor-pointer" @click="abrirModalDetalle(reg)" style="cursor: pointer;">
                <span v-if="reg.tipo === 'seguimiento'" class="item-icon">
                  {{ reg.estado === 'completado' ? '✅' : reg.fecha_reactivacion ? '🔄' : '🎫' }}
                </span>
                <span v-else class="item-icon">📝</span>
                <div>
                  <div class="item-title">
                    {{ reg.tipo === 'seguimiento' ? reg.titulo : getCategoriaTexto(reg.categoria_id) }}
                  </div>
                  <div class="item-description">
                    {{ reg.descripcion.substring(0, 60) }}{{ reg.descripcion.length > 60 ? '...' : '' }}
                  </div>
                </div>
              </div>
              <div class="d-flex align-items-center">
                <div class="d-flex flex-column align-items-end me-3 cursor-pointer" @click="abrirModalDetalle(reg)" style="cursor: pointer;">
                  <span class="status-badge" 
                        :class="getEstadoClaseBootstrap(reg.estado)">
                    {{ getEstadoTexto(reg.estado) }}
                  </span>
                  <div class="d-flex align-items-center gap-2 mt-1">
                    <span v-if="reg.fecha_limite && reg.estado !== 'completado'" 
                          class="date-badge"
                          :class="getClaseFechaLimiteCortaBootstrap(reg)">
                      <i class="bi bi-clock me-1"></i>
                      {{ formatearFechaSimple(reg.fecha_limite) }}
                    </span>
                    <span v-if="reg.fecha_completado" class="date-info completed">
                      Completado: {{ formatearFechaSimple(reg.fecha_completado) }}
                    </span>
                    <span v-else-if="reg.fecha_reactivacion" class="date-info reactivated">
                      Reactivado: {{ formatearFechaSimple(reg.fecha_reactivacion) }}
                    </span>
                    <span v-else class="date-info">{{ formatearFecha(reg.created_at) }}</span>
                  </div>
                </div>
                <!-- Botones de acción (editar) -->
                <div class="edit-button">
                  <button 
                    @click.stop="editarBitacora(reg)" 
                    class="icon-button edit" 
                    title="Editar bitácora"
                  >
                    <i class="bi bi-pencil-square"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="empty-state animate-fade-in-up">
          <i class="bi bi-file-earmark-text empty-icon"></i>
          <p class="empty-title">No hay bitácoras registradas</p>
          <p class="empty-description">Crea una nueva bitácora para comenzar a registrar actividades</p>
          <button 
            @click="abrirModalCreacion" 
            class="action-button primary mt-3"
          >
            <i class="bi bi-plus-lg me-2"></i>
            Crear bitácora
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- MODAL: Creación/Edición de bitácora -->
  <div v-if="modalCreacionAbierto" class="custom-modal">
    <div class="custom-modal-overlay" @click="cerrarModalCreacion"></div>
    <div class="custom-modal-container">
      <div class="custom-modal-content glass-panel">
        <div class="custom-modal-header">
          <h3 class="section-title">
            {{ modoEdicion ? 'Editar bitácora' : (editandoSubBitacora ? 'Agregar actualización' : 'Nueva bitácora') }}
          </h3>
          <button type="button" class="close-button" @click="cerrarModalCreacion">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="custom-modal-body">
          <!-- Formulario -->
          <form @submit.prevent="guardarRegistro" class="modal-form">
            <!-- Título (solo para bitácoras de seguimiento) -->
            <div v-if="(modoBitacora === 'seguimiento' || (modoEdicion && registro.tipo === 'seguimiento')) && !editandoSubBitacora" class="input-group">
              <label for="titulo" class="form-label">Título del proceso</label>
              <input 
                type="text" 
                id="titulo" 
                v-model="registro.titulo" 
                class="form-input"
                placeholder="Ej: Preparación de documentos para audiencia"
                required
              />
            </div>
            
            <!-- Categoría -->
            <div class="input-group">
              <label for="categoria" class="form-label">Categoría</label>
              <select 
                id="categoria" 
                v-model="registro.categoria_id" 
                class="form-select"
                required
              >
                <option value="" disabled>Seleccione una categoría</option>
                <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
              </select>
            </div>
            
            <!-- Descripción -->
            <div class="input-group">
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea 
                id="descripcion" 
                v-model="registro.descripcion" 
                rows="4" 
                class="form-textarea"
                placeholder="Describa detalladamente la acción realizada o el proceso a seguir..."
                required
              ></textarea>
            </div>
            
            <!-- Campos adicionales -->
            <div class="row">
              <!-- Tiempo dedicado -->
              <div class="col-md-6 mb-3">
                <div class="input-group">
                  <label for="tiempoDedicado" class="form-label">
                    Tiempo dedicado (minutos)
                  </label>
                  <input 
                    type="number" 
                    id="tiempoDedicado" 
                    v-model="registro.tiempo_dedicado" 
                    min="1"
                    class="form-input"
                  />
                </div>
              </div>
              
              <!-- Estado -->
              <div class="col-md-6 mb-3">
                <div class="input-group">
                  <label for="estado" class="form-label">Estado</label>
                  <select 
                    id="estado" 
                    v-model="registro.estado" 
                    :disabled="(modoBitacora === 'normal' && !modoEdicion) || (editandoSubBitacora && bitacoraSeleccionada && bitacoraSeleccionada.fecha_completado)"
                    class="form-select"
                  >
                    <option value="completado">Completado</option>
                    <option value="en_proceso">En proceso</option>
                    <option value="pendiente">Pendiente</option>
                    <option v-if="editandoSubBitacora && bitacoraSeleccionada && bitacoraSeleccionada.fecha_completado" value="comentario">Comentario</option>
                  </select>
                  <small v-if="modoBitacora === 'normal' && !modoEdicion" class="form-text">
                    Las bitácoras simples siempre se guardan como completadas
                  </small>
                  <small v-if="editandoSubBitacora && bitacoraSeleccionada && bitacoraSeleccionada.fecha_completado" class="form-text">
                    Esta bitácora ya está completada, las actualizaciones se guardarán como comentarios
                  </small>
                </div>
              </div>
            </div>
            
            <!-- Fecha límite (solo para bitácoras de seguimiento principales) -->
            <div v-if="(modoBitacora === 'seguimiento' || (modoEdicion && registro.tipo === 'seguimiento')) && !editandoSubBitacora" class="input-group border-top pt-3">
              <div class="d-flex align-items-center mb-2">
                <i class="bi bi-alarm text-danger me-2 fs-5"></i>
                <label for="fechaLimite" class="form-label mb-0">
                  Fecha límite <span class="text-danger">*</span>
                </label>
              </div>
              <div class="row">
                <div class="col-md-8 mb-2">
                  <input 
                    type="date" 
                    id="fechaLimite" 
                    v-model="registro.fecha_limite" 
                    class="form-input"
                    required
                  />
                </div>
                <div class="col-md-4 mb-2">
                  <select 
                    v-model="registro.prioridad_fecha" 
                    class="form-select"
                  >
                    <option value="normal">Normal</option>
                    <option value="importante">Importante</option>
                    <option value="critica">Crítica</option>
                  </select>
                </div>
              </div>
              <small class="form-text">
                Esta fecha es el plazo máximo para completar todas las acciones relacionadas con este proceso.
              </small>
            </div>
            
            <!-- Fecha y hora (automática pero visible) -->
            <div v-if="!modoEdicion && !editandoSubBitacora" class="input-group">
              <label class="form-label">Fecha y hora</label>
              <div class="form-static">
                {{ fechaHoraFormateada }}
              </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="modal-actions">
              <button 
                type="submit" 
                class="action-button primary"
                :disabled="guardando"
              >
                <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                {{ modoEdicion ? 'Guardar cambios' : (editandoSubBitacora ? 'Guardar actualización' : 'Guardar registro') }}
              </button>
              <button 
                type="button"
                @click="cerrarModalCreacion" 
                class="action-button secondary"
                :disabled="guardando"
              >
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!-- MODAL: Detalle de bitácora -->
  <div v-if="modalDetalleAbierto && bitacoraSeleccionada" class="custom-modal">
    <div class="custom-modal-overlay" @click="cerrarModalDetalle"></div>
    <div class="custom-modal-container">
      <div class="custom-modal-content glass-panel">
        <div class="custom-modal-header">
          <h3 class="section-title">Detalle de bitácora</h3>
          <button type="button" class="close-button" @click="cerrarModalDetalle">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="custom-modal-body">
          <!-- Encabezado de la bitácora -->
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h3 class="detail-title">{{ bitacoraSeleccionada.titulo }}</h3>
              <div class="d-flex align-items-center mt-2 gap-2 flex-wrap">
                <span class="status-badge" 
                      :class="getEstadoClaseBootstrap(bitacoraSeleccionada.estado)">
                  {{ getEstadoTexto(bitacoraSeleccionada.estado) }}
                </span>
                <span class="date-info">
                  Creado: {{ formatearFecha(bitacoraSeleccionada.created_at) }}
                </span>
                <span v-if="bitacoraSeleccionada.fecha_completado" class="date-info completed">
                  Completado: {{ formatearFecha(bitacoraSeleccionada.fecha_completado) }}
                </span>
                <span v-if="bitacoraSeleccionada.fecha_reactivacion" class="date-info reactivated">
                  Reactivado: {{ formatearFechaSimple(bitacoraSeleccionada.fecha_reactivacion) }}
                </span>
              </div>
            </div>
            <div class="d-flex gap-2">
              <!-- Botón para editar -->
              <button 
                @click="editarBitacora(bitacoraSeleccionada)" 
                class="action-button secondary btn-sm"
              >
                <i class="bi bi-pencil-square me-1"></i>
                Editar
              </button>
              
              <!-- Botón para completar (solo visible si no está completada) -->
              <button 
                v-if="bitacoraSeleccionada.estado !== 'completado'"
                @click="completarBitacora(bitacoraSeleccionada.id)" 
                class="action-button success btn-sm"
                :disabled="accionEnProceso"
              >
                <span v-if="accionEnProceso" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Marcar como completado
              </button>
              
              <!-- Botón para reactivar (solo visible si está completada y el usuario tiene permisos) -->
              <button 
                v-if="bitacoraSeleccionada.estado === 'completado' && tienePermisosReactivacion"
                @click="reactivarBitacora(bitacoraSeleccionada.id)" 
                class="action-button primary btn-sm"
                :disabled="accionEnProceso"
              >
                <span v-if="accionEnProceso" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Reactivar bitácora
              </button>
            </div>
          </div>
          
          <!-- Botón para mostrar/ocultar historial de cambios -->
          <div v-if="bitacoraSeleccionada.historialEstados && bitacoraSeleccionada.historialEstados.length > 0" class="mb-3">
            <button 
              @click="mostrarHistorial = !mostrarHistorial" 
              class="toggle-button"
            >
              <i v-if="!mostrarHistorial" class="bi bi-chevron-down me-1"></i>
              <i v-else class="bi bi-chevron-up me-1"></i>
              {{ mostrarHistorial ? 'Ocultar historial de cambios' : 'Mostrar historial de cambios' }}
              <span class="history-badge">
                {{ bitacoraSeleccionada.historialEstados.length }}
              </span>
            </button>
            
            <!-- Historial de cambios (desplegable) -->
            <div v-if="mostrarHistorial" class="history-panel animate-fade-in-up">
              <h6 class="history-title">Historial de cambios de estado</h6>
              <div class="history-list">
                <div v-for="(cambio, index) in bitacoraSeleccionada.historialEstados" :key="index" class="history-item">
                  <span class="history-date">{{ formatearFecha(cambio.fecha) }}:</span> 
                  <span class="history-action">{{ cambio.accion }}</span>
                  <span v-if="cambio.usuario" class="history-user"> por {{ cambio.usuario.name }}</span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Fecha límite destacada -->
          <div v-if="bitacoraSeleccionada.fecha_limite" 
               class="deadline-panel"
               :class="getClaseFechaLimiteBootstrap(bitacoraSeleccionada)">
            <i class="bi bi-alarm deadline-icon"></i>
            <div>
              <div class="deadline-title">Fecha límite: {{ formatearFechaSimple(bitacoraSeleccionada.fecha_limite) }}</div>
              <div class="deadline-status">
                {{ getTextoEstadoFecha(bitacoraSeleccionada) }}
              </div>
              <div v-if="getDiasRestantes(bitacoraSeleccionada.fecha_limite) > 0 && bitacoraSeleccionada.estado !== 'completado'" class="deadline-days">
                {{ getDiasRestantes(bitacoraSeleccionada.fecha_limite) }} días restantes
              </div>
            </div>
          </div>
          
          <!-- Detalles de la bitácora -->
          <div class="detail-panel">
            <div class="mb-3">
              <h6 class="detail-label">Categoría</h6>
              <p class="detail-value">{{ getCategoriaTexto(bitacoraSeleccionada.categoria_id) }}</p>
            </div>
            <div class="mb-3">
              <h6 class="detail-label">Descripción</h6>
              <p class="detail-value" style="white-space: pre-line;">{{ bitacoraSeleccionada.descripcion }}</p>
            </div>
            <div v-if="bitacoraSeleccionada.tiempo_dedicado" class="mb-3">
              <h6 class="detail-label">Tiempo dedicado</h6>
              <p class="detail-value">{{ bitacoraSeleccionada.tiempo_dedicado }} minutos</p>
            </div>
          </div>
          
          <!-- Progreso hacia la fecha límite (solo si no está completada) -->
          <div v-if="bitacoraSeleccionada.fecha_limite && bitacoraSeleccionada.estado !== 'completado'" class="progress-panel">
            <h6 class="detail-label">Progreso</h6>
            <div class="progress-bar-container">
              <div class="progress-bar-bg">
                <div class="progress-bar-fill" 
                     :style="{ width: `${calcularPorcentajeProgreso(bitacoraSeleccionada)}%` }"
                     :class="getClaseBarraProgresoBootstrap(bitacoraSeleccionada)"></div>
              </div>
            </div>
            <div class="progress-dates">
              <span>Inicio: {{ formatearFechaSimple(bitacoraSeleccionada.created_at) }}</span>
              <span>Límite: {{ formatearFechaSimple(bitacoraSeleccionada.fecha_limite) }}</span>
            </div>
          </div>
          
          <!-- Banner de bitácora completada -->
          <div v-if="bitacoraSeleccionada.fecha_completado" class="completed-banner">
            <i class="bi bi-check-circle completed-icon"></i>
            <div>
              <div class="completed-title">Bitácora completada</div>
              <div class="completed-message">
                Este proceso fue completado el {{ formatearFecha(bitacoraSeleccionada.fecha_completado) }}. 
                Las nuevas actualizaciones se registrarán como comentarios.
              </div>
            </div>
          </div>
          
          <!-- Agregar actualización a la bitácora de seguimiento -->
          <div class="updates-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h4 class="updates-title">
                {{ bitacoraSeleccionada.fecha_completado ? 'Comentarios y actualizaciones' : 'Actualizaciones' }}
              </h4>
              <button 
                @click="iniciarNuevaSubBitacora" 
                class="action-button primary btn-sm"
              >
                {{ bitacoraSeleccionada.fecha_completado ? 'Agregar comentario' : 'Agregar actualización' }}
              </button>
            </div>
            
            <!-- Cargando actualizaciones -->
            <div v-if="cargandoActualizaciones" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando actualizaciones...</span>
              </div>
              <p class="mt-2">Cargando actualizaciones...</p>
            </div>
            
            <!-- Lista de actualizaciones -->
            <div v-else-if="actualizaciones.length > 0" class="updates-list">
              <div v-for="(subBitacora, index) in actualizaciones" :key="subBitacora.id" 
                   class="update-item animate-fade-in-up"
                   :class="{'comment-item': subBitacora.es_comentario}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span v-if="!subBitacora.es_comentario" class="status-badge" 
                          :class="getEstadoClaseBootstrap(subBitacora.estado)">
                      {{ getEstadoTexto(subBitacora.estado) }}
                    </span>
                    <span v-else class="status-badge bg-success">
                      Comentario
                    </span>
                    <span class="update-category">{{ getCategoriaTexto(subBitacora.categoria_id) }}</span>
                  </div>
                  <div class="d-flex align-items-center">
                    <span class="update-date">{{ formatearFecha(subBitacora.created_at) }}</span>
                    <!-- Botón para editar sub-bitácora -->
                    <button 
                      @click.stop="editarSubBitacora(subBitacora, index)" 
                      class="icon-button edit" 
                      title="Editar actualización"
                    >
                      <i class="bi bi-pencil-square"></i>
                    </button>
                  </div>
                </div>
                <p class="update-description">{{ subBitacora.descripcion }}</p>
                <div v-if="subBitacora.tiempo_dedicado" class="update-time">
                  Tiempo dedicado: {{ subBitacora.tiempo_dedicado }} minutos
                </div>
              </div>
            </div>
            <div v-else class="empty-updates">
              <p class="empty-updates-title">No hay actualizaciones registradas</p>
              <p class="empty-updates-message">
                {{ bitacoraSeleccionada.fecha_completado 
                  ? 'Agrega comentarios para mantener un registro de actividades posteriores' 
                  : 'Agrega actualizaciones para registrar el progreso hacia la fecha límite' }}
              </p>
            </div>
          </div>
        </div>
        <div class="custom-modal-footer">
          <button 
            type="button" 
            @click="cerrarModalDetalle" 
            class="action-button secondary"
          >
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

export default {
  props: {
    expediente: {
      type: Object,
      required: true
    },
    expedienteId: {
      type: [Number, String],
      required: true
    }
  },
  emits: ['bitacora-actualizada'],
  setup(props, { emit }) {
    // Estado
    const categorias = ref([])
    const historialRegistros = ref([])
    const loading = ref(false)
    const error = ref(null)
    const guardando = ref(false)
    const accionEnProceso = ref(false)
    const cargandoActualizaciones = ref(false)

    // Modo de bitácora (normal o seguimiento)
    const modoBitacora = ref('normal')

    // Estado inicial del registro
    const registro = ref({
      tipo: 'normal',
      titulo: '',
      categoria_id: '',
      descripcion: '',
      tiempo_dedicado: null,
      estado: 'completado',
      fecha_limite: null,
      prioridad_fecha: 'normal',
      expediente_id: null
    })

    // Resto de variables de estado
    const bitacoraSeleccionada = ref(null)
    const actualizaciones = ref([])
    const editandoSubBitacora = ref(false)
    const subBitacoraEditandoIndex = ref(-1)
    const modoEdicion = ref(false)
    const filtroActual = ref('todos')
    const tienePermisosReactivacion = ref(true)
    const mostrarHistorial = ref(false)
    const modalCreacionAbierto = ref(false)
    const modalDetalleAbierto = ref(false)

    // Cargar categorías
    const cargarCategorias = async () => {
      try {
        console.log('Cargando categorías...')
        const response = await axios.get('/bitacora-categorias')
        categorias.value = response.data
        console.log('Categorías cargadas:', categorias.value)
      } catch (err) {
        console.error('Error al cargar categorías:', err)
        error.value = 'Error al cargar las categorías'
      }
    }

    // Cargar bitácoras
    const cargarBitacoras = async () => {
      const expedienteId = props.expedienteId
      console.log('Cargando bitácoras para expediente ID:', expedienteId)
      
      if (!expedienteId) {
        console.error('Error: No se proporcionó un ID de expediente válido')
        error.value = 'Error: No se proporcionó un ID de expediente válido'
        return
      }
      
      loading.value = true
      error.value = null
      
      try {
        const response = await axios.get(`/bitacoras?expediente_id=${expedienteId}`)
        console.log('Respuesta de bitácoras:', response.data)
        historialRegistros.value = response.data
      } catch (err) {
        console.error('Error al cargar bitácoras:', err)
        error.value = 'Error al cargar el historial de bitácoras'
      } finally {
        loading.value = false
      }
    }

    // Cargar actualizaciones de una bitácora
    const cargarActualizaciones = async (bitacoraId) => {
      cargandoActualizaciones.value = true
      try {
        const response = await axios.get(`/bitacora-actualizaciones?bitacora_id=${bitacoraId}`)
        actualizaciones.value = response.data
      } catch (err) {
        console.error('Error al cargar actualizaciones:', err)
        actualizaciones.value = []
      } finally {
        cargandoActualizaciones.value = false
      }
    }

    // Métodos para manejar modales
    const abrirModalCreacion = () => {
      limpiarFormulario()
      modoEdicion.value = false
      editandoSubBitacora.value = false
      modalCreacionAbierto.value = true
    }

    const cerrarModalCreacion = () => {
      if (!guardando.value) {
        modalCreacionAbierto.value = false
        modoEdicion.value = false
        editandoSubBitacora.value = false
        subBitacoraEditandoIndex.value = -1
      }
    }

    const abrirModalDetalle = async (bitacora) => {
      // Solo mostramos detalles para bitácoras de seguimiento
      if (bitacora.tipo === 'seguimiento') {
        try {
          // Obtener la bitácora completa con sus relaciones
          const response = await axios.get(`/bitacoras/${bitacora.id}`)
          bitacoraSeleccionada.value = response.data
          
          // Cargar actualizaciones
          await cargarActualizaciones(bitacora.id)
          
          // Reiniciamos el estado del historial al seleccionar una nueva bitácora
          mostrarHistorial.value = false
          modalDetalleAbierto.value = true
        } catch (err) {
          console.error('Error al cargar detalles de la bitácora:', err)
          alert('Error al cargar los detalles de la bitácora')
        }
      } else {
        // Para bitácoras normales, podríamos mostrar un modal o un mensaje
        alert(`Bitácora simple: ${bitacora.descripcion}`)
      }
    }

    const cerrarModalDetalle = () => {
      modalDetalleAbierto.value = false
      bitacoraSeleccionada.value = null
      editandoSubBitacora.value = false
      mostrarHistorial.value = false
      actualizaciones.value = []
    }

    // Métodos para edición
    const editarBitacora = (bitacora) => {
      // Copiar la bitácora para edición
      registro.value = { 
        id: bitacora.id,
        tipo: bitacora.tipo,
        titulo: bitacora.titulo,
        categoria_id: bitacora.categoria_id,
        descripcion: bitacora.descripcion,
        tiempo_dedicado: bitacora.tiempo_dedicado,
        estado: bitacora.estado,
        expediente_id: bitacora.expediente_id
      }
      
      // Si es una bitácora de seguimiento, asegurarse de que la fecha límite esté en formato YYYY-MM-DD
      if (bitacora.tipo === 'seguimiento' && bitacora.fecha_limite) {
        const fecha = new Date(bitacora.fecha_limite)
        registro.value.fecha_limite = fecha.toISOString().split('T')[0]
        registro.value.prioridad_fecha = bitacora.prioridad_fecha || 'normal'
      }
      
      modoEdicion.value = true
      editandoSubBitacora.value = false
      
      // Cerrar el modal de detalle si está abierto
      if (modalDetalleAbierto.value) {
        modalDetalleAbierto.value = false
      }
      
      // Abrir el modal de creación/edición
      modalCreacionAbierto.value = true
    }

    const editarSubBitacora = (subBitacora, index) => {
      // Copiar la sub-bitácora para edición
      registro.value = {
        id: subBitacora.id,
        bitacora_id: subBitacora.bitacora_id,
        categoria_id: subBitacora.categoria_id,
        descripcion: subBitacora.descripcion,
        tiempo_dedicado: subBitacora.tiempo_dedicado,
        estado: subBitacora.estado,
        es_comentario: subBitacora.es_comentario
      }
      
      editandoSubBitacora.value = true
      subBitacoraEditandoIndex.value = index
      
      // Cerrar el modal de detalle
      modalDetalleAbierto.value = false
      
      // Abrir el modal de creación/edición
      modalCreacionAbierto.value = true
    }

    // Guardar bitácora
    const guardarRegistro = async () => {
      guardando.value = true
      
      // Asegurarse de que el expediente_id esté establecido
      registro.value.expediente_id = props.expedienteId
      
      console.log('Guardando registro con datos:', registro.value)
      
      try {
        // Si estamos editando una sub-bitácora existente
        if (editandoSubBitacora.value && bitacoraSeleccionada.value && subBitacoraEditandoIndex.value >= 0) {
          await axios.put(`/bitacora-actualizaciones/${registro.value.id}`, registro.value)
          await cargarActualizaciones(bitacoraSeleccionada.value.id)
          
          // Salir del modo de edición
          editandoSubBitacora.value = false
          subBitacoraEditandoIndex.value = -1
          cerrarModalCreacion()
          
          // Mostrar mensaje de éxito
          alert('Actualización editada correctamente')
          return
        }
        
        // Si estamos creando una nueva sub-bitácora
        if (editandoSubBitacora.value && bitacoraSeleccionada.value && subBitacoraEditandoIndex.value === -1) {
          const nuevaActualizacion = {
            bitacora_id: bitacoraSeleccionada.value.id,
            categoria_id: registro.value.categoria_id,
            descripcion: registro.value.descripcion,
            tiempo_dedicado: registro.value.tiempo_dedicado,
            estado: bitacoraSeleccionada.value.fecha_completado ? 'comentario' : registro.value.estado,
            es_comentario: bitacoraSeleccionada.value.fecha_completado ? true : false
          }
          
          await axios.post('/bitacora-actualizaciones', nuevaActualizacion)
          await cargarActualizaciones(bitacoraSeleccionada.value.id)
          
          // Salir del modo de edición
          editandoSubBitacora.value = false
          cerrarModalCreacion()
          
          // Mostrar mensaje de éxito
          alert('Actualización agregada correctamente')
          return
        }
        
        // Si estamos editando una bitácora existente
        if (modoEdicion.value) {
          await axios.put(`/bitacoras/${registro.value.id}`, registro.value)
          await cargarBitacoras()
          
          // Salir del modo de edición
          modoEdicion.value = false
          cerrarModalCreacion()
          
          // Mostrar mensaje de éxito
          alert('Bitácora actualizada correctamente')
          emit('bitacora-actualizada')
          return
        }
        
        // Crear nueva bitácora
        const nuevaBitacora = {
          expediente_id: props.expedienteId,
          tipo: modoBitacora.value,
          titulo: registro.value.titulo || getCategoriaTexto(registro.value.categoria_id),
          categoria_id: registro.value.categoria_id,
          descripcion: registro.value.descripcion,
          tiempo_dedicado: registro.value.tiempo_dedicado,
          estado: modoBitacora.value === 'normal' ? 'completado' : registro.value.estado,
          fecha_limite: registro.value.fecha_limite,
          prioridad_fecha: registro.value.prioridad_fecha || 'normal'
        }
        
        await axios.post('/bitacoras', nuevaBitacora)
        await cargarBitacoras()
        
        // Limpiamos el formulario y cerramos el modal
        limpiarFormulario()
        cerrarModalCreacion()
        
        // Mostramos mensaje de éxito
        alert('Bitácora guardada correctamente')
        emit('bitacora-actualizada')
      } catch (err) {
        console.error('Error al guardar:', err)
        alert('Error al guardar: ' + (err.response?.data?.message || 'Error desconocido'))
      } finally {
        guardando.value = false
      }
    }

    const limpiarFormulario = () => {
      registro.value = {
        tipo: modoBitacora.value,
        titulo: '',
        categoria_id: '',
        descripcion: '',
        tiempo_dedicado: null,
        estado: modoBitacora.value === 'normal' ? 'completado' : 'en_proceso',
        fecha_limite: null,
        prioridad_fecha: 'normal',
        expediente_id: props.expedienteId
      }
    }

    // Completar bitácora
    const completarBitacora = async (id) => {
      accionEnProceso.value = true
      try {
        await axios.post(`/bitacoras/${id}/completar`)
        
        // Actualizar la bitácora en la vista
        const response = await axios.get(`/bitacoras/${id}`)
        bitacoraSeleccionada.value = response.data
        
        // Actualizar la lista de bitácoras
        await cargarBitacoras()
        
        alert('Bitácora marcada como completada')
        emit('bitacora-actualizada')
      } catch (err) {
        console.error('Error al completar la bitácora:', err)
        alert('Error al completar la bitácora')
      } finally {
        accionEnProceso.value = false
      }
    }

    // Reactivar bitácora
    const reactivarBitacora = async (id) => {
      accionEnProceso.value = true
      try {
        await axios.post(`/bitacoras/${id}/reactivar`)
        
        // Actualizar la bitácora en la vista
        const response = await axios.get(`/bitacoras/${id}`)
        bitacoraSeleccionada.value = response.data
        
        // Actualizar la lista de bitácoras
        await cargarBitacoras()
        
        alert('Bitácora reactivada correctamente')
        emit('bitacora-actualizada')
      } catch (err) {
        console.error('Error al reactivar la bitácora:', err)
        alert('Error al reactivar la bitácora')
      } finally {
        accionEnProceso.value = false
      }
    }

    // Iniciar nueva sub-bitácora
    const iniciarNuevaSubBitacora = () => {
      editandoSubBitacora.value = true
      subBitacoraEditandoIndex.value = -1 // Indicar que es una nueva sub-bitácora
      registro.value = {
        categoria_id: '',
        descripcion: '',
        tiempo_dedicado: null,
        estado: bitacoraSeleccionada.value.fecha_completado ? 'comentario' : 'completado'
      }
      cerrarModalDetalle()
      modalCreacionAbierto.value = true
    }

    // Funciones de utilidad
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

    // Clases de Bootstrap para fechas límite
    const getClaseFechaLimiteBootstrap = (bitacora) => {
      if (bitacora.estado === 'completado') {
        return 'alert-success'
      }
      
      const diasRestantes = getDiasRestantes(bitacora.fecha_limite)
      
      if (diasRestantes < 0) {
        return 'alert-danger'
      } else if (esFechaProximaAVencer(bitacora)) {
        return 'alert-warning'
      } else {
        return 'alert-primary'
      }
    }

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

    const getTextoEstadoFecha = (bitacora) => {
      if (bitacora.estado === 'completado') {
        return 'Proceso completado antes de la fecha límite'
      }
      
      const diasRestantes = getDiasRestantes(bitacora.fecha_limite)
      
      if (diasRestantes < 0) {
        return `¡ATENCIÓN! Fecha límite vencida hace ${Math.abs(diasRestantes)} días`
      } else if (diasRestantes === 0) {
        return '¡ATENCIÓN! La fecha límite es hoy'
      } else if (esFechaProximaAVencer(bitacora)) {
        return `¡ATENCIÓN! Quedan pocos días para cumplir con el plazo`
      } else {
        return 'Proceso en curso, dentro del plazo establecido'
      }
    }

    const calcularPorcentajeProgreso = (bitacora) => {
      if (!bitacora.fecha_limite) return 0
      if (bitacora.estado === 'completado') return 100
      
      const fechaInicio = new Date(bitacora.created_at)
      const fechaFin = new Date(bitacora.fecha_limite)
      const hoy = new Date()
      
      const totalDias = (fechaFin.getTime() - fechaInicio.getTime()) / (1000 * 3600 * 24)
      if (totalDias === 0) return 0 // Evitar división por cero
      
      const diasTranscurridos = (hoy.getTime() - fechaInicio.getTime()) / (1000 * 3600 * 24)
      
      let porcentaje = Math.round((diasTranscurridos / totalDias) * 100)
      
      // Limitamos el porcentaje entre 0 y 100
      return Math.max(0, Math.min(100, porcentaje))
    }

    // Clases de Bootstrap para barras de progreso
    const getClaseBarraProgresoBootstrap = (bitacora) => {
      const diasRestantes = getDiasRestantes(bitacora.fecha_limite)
      
      if (diasRestantes < 0) {
        return 'bg-danger'
      } else if (esFechaProximaAVencer(bitacora)) {
        return 'bg-warning'
      } else {
        return 'bg-primary'
      }
    }

    // Registros filtrados
    const registrosFiltrados = computed(() => {
      if (filtroActual.value === 'todos') {
        return historialRegistros.value
      } else if (filtroActual.value === 'normal') {
        return historialRegistros.value.filter(reg => reg.tipo === 'normal')
      } else if (filtroActual.value === 'seguimiento') {
        return historialRegistros.value.filter(reg => reg.tipo === 'seguimiento')
      } else if (filtroActual.value === 'pendientes') {
        return historialRegistros.value.filter(reg => reg.estado !== 'completado')
      } else if (filtroActual.value === 'completados') {
        return historialRegistros.value.filter(reg => reg.estado === 'completado')
      } else if (filtroActual.value === 'proximos') {
        return historialRegistros.value.filter(reg => 
          reg.fecha_limite && 
          reg.estado !== 'completado' && 
          esFechaProximaAVencer(reg)
        )
      } else if (filtroActual.value === 'reactivados') {
        return historialRegistros.value.filter(reg => reg.fecha_reactivacion)
      }
      return historialRegistros.value
    })

    // Fecha y hora formateada
    const fechaHoraFormateada = computed(() => {
      return new Date().toLocaleString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      })
    })

    // Controlar el scroll del body cuando se abre un modal
    watch([modalCreacionAbierto, modalDetalleAbierto], ([nuevoModalCreacion, nuevoModalDetalle], [antiguoModalCreacion, antiguoModalDetalle]) => {
      if ((nuevoModalCreacion || nuevoModalDetalle) && (!antiguoModalCreacion && !antiguoModalDetalle)) {
        // Si se abre cualquier modal, bloquear el scroll del body
        document.body.style.overflow = 'hidden'
      } else if ((!nuevoModalCreacion && !nuevoModalDetalle) && (antiguoModalCreacion || antiguoModalDetalle)) {
        // Si se cierran todos los modales, restaurar el scroll del body
        document.body.style.overflow = ''
      }
    })

    // Actualizar el expediente_id en el registro cuando cambie props.expedienteId
    watch(() => props.expedienteId, (newId) => {
      if (newId) {
        console.log('expedienteId actualizado:', newId)
        registro.value.expediente_id = newId
        cargarBitacoras()
      }
    }, { immediate: true })

    // Cargar datos al montar el componente
    onMounted(async () => {
      console.log('BitacoraExpediente montado, expedienteId:', props.expedienteId)
      await cargarCategorias()
      
      // Solo cargar bitácoras si tenemos un expedienteId válido
      if (props.expedienteId) {
        registro.value.expediente_id = props.expedienteId
        await cargarBitacoras()
      } else {
        console.warn('No se pudo cargar bitácoras: expedienteId no disponible')
      }
    })

    return {
      categorias,
      historialRegistros,
      loading,
      error,
      guardando,
      accionEnProceso,
      cargandoActualizaciones,
      modoBitacora,
      registro,
      bitacoraSeleccionada,
      actualizaciones,
      editandoSubBitacora,
      subBitacoraEditandoIndex,
      modoEdicion,
      filtroActual,
      tienePermisosReactivacion,
      mostrarHistorial,
      modalCreacionAbierto,
      modalDetalleAbierto,
      cargarCategorias,
      cargarBitacoras,
      cargarActualizaciones,
      abrirModalCreacion,
      cerrarModalCreacion,
      abrirModalDetalle,
      cerrarModalDetalle,
      editarBitacora,
      editarSubBitacora,
      guardarRegistro,
      limpiarFormulario,
      completarBitacora,
      reactivarBitacora,
      iniciarNuevaSubBitacora,
      formatearFecha,
      formatearFechaSimple,
      getCategoriaTexto,
      getEstadoTexto,
      getEstadoClaseBootstrap,
      getDiasRestantes,
      esFechaProximaAVencer,
      getClaseFechaLimiteBootstrap,
      getClaseFechaLimiteCortaBootstrap,
      getTextoEstadoFecha,
      calcularPorcentajeProgreso,
      getClaseBarraProgresoBootstrap,
      registrosFiltrados,
      fechaHoraFormateada
    }
  }
}
</script>

<style scoped>
/* Estilos base optimizados */
.bitacora-dashboard {
  font-family: 'Inter', sans-serif;
  min-height: 100vh;
  background: linear-gradient(135deg, rgb(76, 21, 165) 0%, #f3e8ff 100%);
  padding: 2rem;
  color: #4a5568;
}

.dashboard-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  color: white;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Panel de cristal optimizado */
.glass-panel {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.18);
  margin-bottom: 2rem;
}

/* Títulos y encabezados */
.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: #9333ea;
}

/* Botones de modo */
.mode-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  min-height: 200px;
  padding: 2rem;
  border-radius: 1rem;
  border: 2px solid rgba(147, 51, 234, 0.2);
  background: white;
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
  cursor: pointer;
}

.mode-button:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(147, 51, 234, 0.2);
  border-color: #9333ea;
}

.mode-button.active {
  border-color: #9333ea;
  background: rgba(147, 51, 234, 0.05);
  box-shadow: 0 10px 20px rgba(147, 51, 234, 0.2);
}

.mode-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.mode-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  color: #4a5568;
}

.mode-description {
  font-size: 1rem;
  color: #718096;
  text-align: center;
}

/* Pestañas de filtro */
.filter-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-bottom: 2rem;
  padding: 0.5rem;
  background: rgba(255, 255, 255, 0.5);
  border-radius: 0.75rem;
}

.tab-button {
  padding: 0.75rem 1.5rem;
  border-radius: 2rem;
  border: 1px solid rgba(147, 51, 234, 0.2);
  background: white;
  color: #718096;
  font-size: 0.875rem;
  font-weight: 500;
  transition: background-color 0.3s ease, color 0.3s ease;
  cursor: pointer;
  white-space: nowrap;
}

.tab-button:hover {
  background: rgba(147, 51, 234, 0.05);
  color: #9333ea;
}

.tab-button.active {
  background: #9333ea;
  color: white;
  border-color: #9333ea;
}

/* Lista de bitácoras */
.bitacora-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.bitacora-item {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
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

.item-icon {
  font-size: 2rem;
  margin-right: 1.5rem;
}

.item-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1a202c;
  margin-bottom: 0.5rem;
}

.item-description {
  font-size: 0.875rem;
  color: #718096;
  line-height: 1.5;
}

/* Badges y etiquetas */
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.375rem 1rem;
  border-radius: 2rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: white;
}

.date-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.375rem 0.75rem;
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

/* Botones de acción */
.action-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-weight: 600;
  transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
  cursor: pointer;
  border: none;
}

.action-button.primary {
  background: linear-gradient(to right, #9333ea, #c026d3);
  color: white;
}

.action-button.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(168, 85, 247, 0.3);
}

.action-button.secondary {
  background: white;
  color: #9333ea;
  border: 1px solid #9333ea;
}

.action-button.secondary:hover {
  background: rgba(147, 51, 234, 0.05);
}

.action-button.success {
  background: #10b981;
  color: white;
}

.action-button.success:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
}

.action-button.btn-sm {
  padding: 0.25rem 0.75rem;
  font-size: 0.875rem;
}

.icon-button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: background-color 0.3s ease, color 0.3s ease;
  color: #718096;
}

.icon-button.edit:hover {
  background: rgba(147, 51, 234, 0.1);
  color: #9333ea;
}

/* Modal personalizado */
.custom-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1050;
  display: flex;
  align-items: center;
  justify-content: center;
}

.custom-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1051;
}

.custom-modal-container {
  position: relative;
  z-index: 1052;
  width: 100%;
  max-width: 800px;
  margin: 1.75rem auto;
}

.custom-modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 100%;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 0.75rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.custom-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid rgba(192, 38, 211, 0.1);
}

.custom-modal-body {
  position: relative;
  flex: 1 1 auto;
  padding: 1.5rem;
  max-height: 70vh;
  overflow-y: auto;
}

.custom-modal-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 1rem 1.5rem;
  border-top: 1px solid rgba(192, 38, 211, 0.1);
}

.close-button {
  background: none;
  border: none;
  color: #718096;
  font-size: 1.5rem;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-button:hover {
  color: #9333ea;
}

/* Formulario */
.modal-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #4a5568;
}

.form-input, .form-select, .form-textarea {
  padding: 0.75rem;
  border-radius: 0.5rem;
  border: 1px solid rgba(192, 38, 211, 0.2);
  background: white;
  color: #4a5568;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: #9333ea;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.1);
}

.form-static {
  padding: 0.75rem;
  border-radius: 0.5rem;
  background: rgba(147, 51, 234, 0.05);
  color: #4a5568;
}

.form-text {
  font-size: 0.75rem;
  color: #718096;
  margin-top: 0.25rem;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
  justify-content: flex-end;
}

/* Detalles */
.detail-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #4a5568;
  margin-bottom: 0.5rem;
}

.detail-panel {
  background: white;
  border-radius: 0.75rem;
  padding: 1.25rem;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.detail-label {
  font-size: 0.75rem;
  font-weight: 600;
  color: #718096;
  margin-bottom: 0.25rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.detail-value {
  color: #4a5568;
}

/* Panel de fecha límite */
.deadline-panel {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1.25rem;
  border-radius: 0.75rem;
  margin-bottom: 1.5rem;
}

.deadline-icon {
  font-size: 1.5rem;
}

.deadline-title {
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.deadline-status {
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.deadline-days {
  font-size: 0.875rem;
  font-weight: 600;
}

/* Panel de progreso */
.progress-panel {
  background: white;
  border-radius: 0.75rem;
  padding: 1.25rem;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.progress-bar-container {
  margin: 1rem 0;
}

.progress-bar-bg {
  height: 0.5rem;
  background: #e2e8f0;
  border-radius: 1rem;
  overflow: hidden;
}

.progress-bar-fill {
  height: 100%;
  border-radius: 1rem;
  transition: width 0.3s ease;
}

.progress-dates {
  display: flex;
  justify-content: space-between;
  font-size: 0.75rem;
  color: #718096;
}

/* Banner de completado */
.completed-banner {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.25rem;
  background: #f0fdf4;
  border-radius: 0.75rem;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.completed-icon {
  font-size: 1.5rem;
  color: #10b981;
}

.completed-title {
  font-weight: 600;
  color: #10b981;
  margin-bottom: 0.25rem;
}

.completed-message {
  font-size: 0.875rem;
  color: #065f46;
}

/* Sección de actualizaciones */
.updates-section {
  border-top: 1px solid rgba(192, 38, 211, 0.1);
  padding-top: 1.5rem;
  margin-top: 1.5rem;
}

.updates-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #9333ea;
  margin-bottom: 1rem;
}

.updates-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.update-item {
  background: white;
  border-radius: 0.75rem;
  padding: 1rem;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.update-item.comment-item {
  background: #f0fdf4;
  border-color: rgba(16, 185, 129, 0.2);
}

.update-category {
  font-size: 0.875rem;
  font-weight: 600;
  color: #4a5568;
}

.update-date {
  font-size: 0.75rem;
  color: #718096;
  margin-right: 0.5rem;
}

.update-description {
  margin: 0.5rem 0;
  color: #4a5568;
}

.update-time {
  font-size: 0.75rem;
  color: #718096;
}

/* Actualizaciones vacías */
.empty-updates {
  background: white;
  border-radius: 0.75rem;
  padding: 2rem;
  text-align: center;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.empty-updates-title {
  font-weight: 600;
  color: #4a5568;
  margin-bottom: 0.5rem;
}

.empty-updates-message {
  font-size: 0.875rem;
  color: #718096;
}

/* Botón de alternar */
.toggle-button {
  display: inline-flex;
  align-items: center;
  background: none;
  border: none;
  color: #9333ea;
  font-size: 0.875rem;
  cursor: pointer;
  padding: 0;
}

.history-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 1.5rem;
  height: 1.5rem;
  border-radius: 50%;
  background: #9333ea;
  color: white;
  font-size: 0.75rem;
  margin-left: 0.5rem;
}

/* Panel de historial */
.history-panel {
  background: white;
  border-radius: 0.75rem;
  padding: 1.25rem;
  margin: 1rem 0;
  border: 1px solid rgba(192, 38, 211, 0.1);
}

.history-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #718096;
  margin-bottom: 1rem;
}

.history-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.history-item {
  font-size: 0.875rem;
  color: #4a5568;
}

.history-date {
  font-weight: 600;
}

.history-action {
  color: #4a5568;
}

.history-user {
  color: #718096;
}

/* Animaciones optimizadas */
.animate-fade-in-up {
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
  .bitacora-dashboard {
    padding: 1rem;
  }

  .dashboard-title {
    font-size: 2rem;
  }

  .custom-modal-container {
    width: 95%;
    margin: 1rem;
  }

  .filter-tabs {
    overflow-x: auto;
    padding-bottom: 0.5rem;
  }

  .tab-button {
    white-space: nowrap;
  }
}
</style>
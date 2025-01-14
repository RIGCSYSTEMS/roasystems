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
      <h3>Familia</h3>
      <p>{{ clientData.familia || 'No hay información familiar registrada' }}</p>
      
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
  methods: {
    formatDate(date) {
      if (!date) return 'No registrado'
      // Creamos un objeto Date con la fecha UTC
      const utcDate = new Date(date + 'T00:00:00Z');
      // Formateamos la fecha en la zona horaria local
      return utcDate.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'UTC'
      })
    },
    capitalizeFirstLetter(string) {
      if (!string) return ''
      return string.charAt(0).toUpperCase() + string.slice(1)
    }
  }
}
</script>


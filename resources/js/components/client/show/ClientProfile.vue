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
      <div v-if="familiaArray && familiaArray.length > 0">
        <div v-for="(familiar, index) in familiaArray" :key="index" class="mb-2">
          <p class="mb-1">
            <strong>Nombre:</strong> {{ familiar.nombre }} {{ familiar.apellido }}
            <span v-if="familiar.parentesco">, <strong>Parentesco:</strong> {{ familiar.parentesco }}</span>
          </p>
        </div>
      </div>
      <p v-else>No hay información familiar registrada</p>
      
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
  computed: {
    familiaArray() {
      try {
        if (!this.clientData.familia) return null;
        // Si ya es un array, lo devolvemos tal cual
        if (Array.isArray(this.clientData.familia)) {
          return this.clientData.familia;
        }
        // Si es una cadena JSON, la parseamos
        if (typeof this.clientData.familia === 'string') {
          return JSON.parse(this.clientData.familia);
        }
        return null;
      } catch (error) {
        console.error('Error al procesar familia:', error);
        return null;
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
    }
  }
}
</script>

<style scoped>
.mb-2 {
  margin-bottom: 0.5rem;
}
.mb-1 {
  margin-bottom: 0.25rem;
}
</style>
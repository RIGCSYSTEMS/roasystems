<template>
    <div class="container-fluid py-4">
      <div class="row g-4">
        <!-- Columna principal con la información del cliente -->
        <div class="col-lg-8">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Perfil del Cliente</h1>
            <button class="btn btn-primary" @click="showEditModal = true">
              <i class="bi bi-pencil me-2"></i>
              Editar Cliente
            </button>
          </div>
          <ClientProfile :clientData="clientData" />
        </div>
        
        <!-- Barra lateral derecha -->
        <div class="col-lg-4">
          <Expedientes :expedientes="clientData.expedientes" :clientData="clientData" class="mb-4" />
          <Bitacoras :bitacoras="clientData.bitacoras" :clientData="clientData" />
        </div>
      </div>
  
      <!-- Modal de Edición -->
      <edicion
        v-model:isOpen="showEditModal"
        :clientData="clientData"
        @client-updated="handleClientUpdated"
      />
    </div>
  </template>
  
  <script>
  import { ref } from 'vue'
  import ClientProfile from './ClientProfile.vue'
  import Expedientes from './Expedientes.vue'
  import Bitacoras from './Bitacoras.vue'
  import edicion from './edicion.vue'
  
  export default {
    name: 'Vista',
    components: {
      ClientProfile,
      Expedientes,
      Bitacoras,
      edicion
    },
    props: {
      clientData: {
        type: Object,
        required: true
      }
    },
    setup() {
      const showEditModal = ref(false)
  
      const handleClientUpdated = (updatedClient) => {
        window.location.reload()
      }
  
      return {
        showEditModal,
        handleClientUpdated
      }
    }
  }
  </script>
  
  
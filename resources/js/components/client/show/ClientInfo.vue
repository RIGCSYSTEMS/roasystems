<template>
    <div class="card mb-4">
      <div class="card-body">
        <!-- Encabezado del cliente -->
        <div class="d-flex justify-content-between align-items-start mb-4">
          <div>
            <h3 class="mb-1">{{ client.nombre_de_cliente }}</h3>
            <p class="text-muted mb-0">{{ client.pais }}</p>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-eye"></i> Ver todo
            </button>
            <router-link :to="`/client/${client.id}/edit`" class="btn btn-dark btn-sm">
              <i class="fas fa-edit"></i> Editar
            </router-link>
            <button class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-clock"></i> Seguimiento
            </button>
            <button class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-calendar-plus"></i> Agregar evento
            </button>
            <button class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-folder"></i> Expedientes
            </button>
          </div>
        </div>
  
        <!-- Información de contacto -->
        <div class="card bg-light mb-4">
          <div class="card-body">
            <h5 class="card-title">Información de contacto</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <p class="mb-1">
                  <i class="fas fa-envelope text-muted me-2"></i>
                  {{ client.email }}
                </p>
              </div>
              <div class="col-md-6">
                <p class="mb-1">
                  <i class="fas fa-phone text-muted me-2"></i>
                  {{ client.telefono }}
                </p>
              </div>
              <div class="col-12">
                <p class="mb-1">
                  <i class="fas fa-map-marker-alt text-muted me-2"></i>
                  {{ client.direccion }}
                </p>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Estado y Fechas -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <h6 class="text-muted mb-3">Activo desde</h6>
                <p class="mb-0">{{ formatDate(client.created_at) }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <h6 class="text-muted mb-3">Llegada a Canadá</h6>
                <p class="mb-0">{{ client.llegada_a_canada ? formatDate(client.llegada_a_canada) : 'No registrado' }}</p>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Información Personal -->
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="d-flex align-items-center mb-4">
              <i class="fas fa-user-circle me-2"></i>
              Información personal
            </h5>
            <div class="row g-3">
              <div class="col-md-6">
                <p class="mb-1"><strong>Nombres:</strong> {{ client.nombre_de_cliente }}</p>
                <p class="mb-1"><strong>Nacionalidad:</strong> {{ client.pais }}</p>
                <p class="mb-1"><strong>Fecha de nacimiento:</strong> {{ client.fecha_de_nacimiento ? formatDate(client.fecha_de_nacimiento) : 'No registrado' }}</p>
                <p class="mb-1"><strong>Género:</strong> {{ client.genero }}</p>
              </div>
              <div class="col-md-6">
                <p class="mb-1"><strong>Pasaporte:</strong> {{ client.pasaporte }}</p>
                <p class="mb-1"><strong>Estado civil:</strong> {{ client.estado_civil }}</p>
                <p class="mb-1"><strong>Profesión:</strong> {{ client.profesion }}</p>
                <p class="mb-1"><strong>Estatus en Canadá:</strong> {{ client.estatus }}</p>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Familia -->
        <div class="card">
          <div class="card-body">
            <h5 class="d-flex align-items-center mb-4">
              <i class="fas fa-users me-2"></i>
              Familia
            </h5>
            <div v-if="client.familia && client.familia.length > 0">
              <div v-for="(familiar, index) in client.familia" :key="index" class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <p class="mb-0">{{ familiar.nombre }} {{ familiar.apellido }}</p>
                  <small class="text-muted">{{ familiar.parentesco }}</small>
                </div>
                <a href="#" class="btn btn-link btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
              </div>
            </div>
            <p v-else class="text-muted mb-0">No hay familiares registrados</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    client: {
      type: Object,
      required: true
    }
  });
  
  function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
  }
  </script>
  
  <style scoped>
  .card {
    border: none;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 8px;
  }
  </style>
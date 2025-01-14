<template>
    <div class="card">
      <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Actividades recientes</h5>
          <router-link :to="{ name: 'client.bitacoras.index', params: { id: clientId } }" class="btn btn-success btn-sm">
            Agregar
          </router-link>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
          <div v-for="bitacora in bitacoras" :key="bitacora.id" class="list-group-item">
            <p class="mb-1">{{ bitacora.descripcion }}</p>
            <small class="text-muted">{{ formatDate(bitacora.created_at) }}</small>
          </div>
          <div v-if="bitacoras.length === 0" class="list-group-item">
            <p class="text-muted mb-0">No hay actividades registradas</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  defineProps({
    bitacoras: {
      type: Array,
      default: () => []
    },
    clientId: {
      type: [Number, String],
      required: true
    }
  });
  
  function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' });
  }
  </script>
  
  <style scoped>
  .list-group-item {
    border-left: none;
    border-right: none;
  }
  .list-group-item:first-child {
    border-top: none;
  }
  .list-group-item:last-child {
    border-bottom: none;
  }
  </style>
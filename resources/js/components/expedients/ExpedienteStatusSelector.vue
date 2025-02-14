<template>
    <div class="expediente-status-selector">
      <button 
        v-for="status in statusOptions" 
        :key="status.value"
        :class="['status-button', { active: currentStatus === status.value }]"
        @click="changeStatus(status.value)"
        :disabled="!canEdit"
      >
        <i :class="status.icon"></i>
        {{ status.label }}
      </button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      currentStatus: {
        type: String,
        required: true,
        watch: {
    currentStatus(newStatus) {
      // Esto forzará una re-renderización cuando cambie el estado
      this.$forceUpdate();
      } 
    },
      },
      canEdit: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        statusOptions: [
          { value: 'Abierto', label: 'Abierto', icon: 'bi bi-folder2-open' },
          { value: 'Cerrado', label: 'Cerrado', icon: 'bi bi-folder2' },
          { value: 'Cancelado', label: 'Cancelado', icon: 'bi bi-x-circle' }
        ]
      }
    },
    methods: {
      changeStatus(newStatus) {
        if (this.canEdit && newStatus !== this.currentStatus) {
          this.$emit('status-changed', newStatus);
        }
      }
    }
  }
  </script>
  
  <style scoped>
  .expediente-status-selector {
    display: flex;
    gap: 10px;
    background-color: #f8f9fa;
    padding: 5px;
    border-radius: 8px;
  }
  
  .status-button {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    border: none;
    background-color: transparent;
    color: #6c757d;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  
  .status-button:hover:not(:disabled) {
    background-color: #e9ecef;
  }
  
  .status-button.active {
    background-color: #007bff;
    color: white;
  }
  
  .status-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .status-button i {
    font-size: 1.1em;
  }
  </style>
  
  
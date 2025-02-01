<!-- resources/js/components/EdicionExpedienteModal.vue -->
<template>
  <div class="modal" tabindex="-1" role="dialog" style="display: block;">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Expediente</h5>
          <button type="button" class="btn-close" @click="$emit('cerrar')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="guardarCambios">
            <div class="mb-3">
              <label for="numero_de_dossier" class="form-label">Número de Dossier</label>
              <input type="text" class="form-control" id="numero_de_dossier" v-model="expedienteEditado.numero_de_dossier" required>
            </div>
            <div class="mb-3">
              <label for="estatus_del_expediente" class="form-label">Estatus</label>
              <select class="form-select" id="estatus_del_expediente" v-model="expedienteEditado.estatus_del_expediente">
                <option value="Abierto">Abierto</option>
                <option value="Cerrado">Cerrado</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Cancelado">Cancelado</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="prioridad" class="form-label">Prioridad</label>
              <select class="form-select" id="prioridad" v-model="expedienteEditado.prioridad">
                <option value="Normal">Normal</option>
                <option value="Urgente">Urgente</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="fecha_de_apertura" class="form-label">Fecha de Apertura</label>
              <input type="date" class="form-control" id="fecha_de_apertura" v-model="expedienteEditado.fecha_de_apertura" required>
            </div>
            <div class="mb-3">
              <label for="fecha_de_cierre" class="form-label">Fecha de Cierre</label>
              <input type="date" class="form-control" id="fecha_de_cierre" v-model="expedienteEditado.fecha_de_cierre">
            </div>
            <div class="mb-3">
              <label for="despacho" class="form-label">Despacho</label>
              <input type="text" class="form-control" id="despacho" v-model="expedienteEditado.despacho">
            </div>
            <div class="mb-3">
              <label for="abogado" class="form-label">Abogado</label>
              <input type="text" class="form-control" id="abogado" v-model="expedienteEditado.abogado">
            </div>
            <div class="mb-3">
              <label for="plazo_fda" class="form-label">Plazo FDA</label>
              <input type="date" class="form-control" id="plazo_fda" v-model="expedienteEditado.plazo_fda">
            </div>
            <div class="mb-3">
              <label for="boite" class="form-label">Boite</label>
              <input type="text" class="form-control" id="boite" v-model="expedienteEditado.boite">
            </div>
            <!-- Agrega más campos según sea necesario -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="$emit('cerrar')">Cancelar</button>
          <button type="button" class="btn btn-primary" @click="guardarCambios">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    expediente: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      expedienteEditado: { ...this.expediente }
    };
  },
  methods: {
    guardarCambios() {
      this.$emit('actualizar', this.expedienteEditado);
    }
  }
};
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-content {
  border-radius: 15px;
}
</style>
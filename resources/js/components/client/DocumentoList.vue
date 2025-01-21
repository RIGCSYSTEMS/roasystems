<template>
  <div class="mt-4">
    <h2>Lista de Documentos</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Fecha de subida</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="documento in documentos" :key="documento.id">
          <td>{{ documento.nombre_de_documento }}</td>
          <td>{{ documento.tipo_de_documento }}</td>
          <td>{{ formatDate(documento.created_at) }}</td>
          <td>
            <button @click="$emit('ver-documento', documento)" class="btn btn-info btn-sm">Ver</button>
            <button @click="$emit('editar-documento', documento)" class="btn btn-warning btn-sm">Editar</button>
            <button @click="$emit('eliminar-documento', documento.id)" class="btn btn-danger btn-sm">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    documentos: {
      type: Array,
      required: true
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    }
  }
}
</script>
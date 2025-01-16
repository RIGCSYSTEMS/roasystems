<template>
    <div>
      <h3>{{ title }}</h3>
      <p v-for="(value, key) in data" :key="key">
        <strong>{{ key }}:</strong> {{ value }}
      </p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      title: String,
      data: Object
    }
  }
  </script>
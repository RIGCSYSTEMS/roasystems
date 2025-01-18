<template>
    <div class="crear-cliente-container">
      <h2 class="titulo-principal">Crear Nuevo Cliente</h2>
      <form @submit.prevent="handleSubmit" class="formulario-cliente">
        <!-- Información Personal -->
        <div class="seccion-formulario">
          <div class="encabezado-seccion">
            <i class="bi bi-person-circle"></i>
            <h3>Información Personal</h3>
          </div>
          <div class="grid-formulario">
            <div class="grupo-formulario">
              <label for="nombre">
                <i class="bi bi-person"></i>
                Nombre Completo
              </label>
              <input 
                id="nombre" 
                v-model="formData.nombre_de_cliente" 
                class="control-formulario" 
                required 
                placeholder="Nombre completo del cliente"
              />
            </div>
            
            <div class="grupo-formulario">
              <label for="fecha_de_nacimiento">
                <i class="bi bi-calendar-event"></i>
                Fecha de nacimiento
              </label>
              <input 
                id="fecha_de_nacimiento" 
                v-model="formData.fecha_de_nacimiento" 
                type="date" 
                class="control-formulario" 
                required 
                :max="getCurrentDate()" 
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="genero">
                <i class="bi bi-gender-ambiguous"></i>
                Género
              </label>
              <select v-model="formData.genero" class="control-formulario" required>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
              </select>
            </div>
  
            <div class="grupo-formulario">
              <label for="estado_civil">
                <i class="bi bi-heart"></i>
                Estado Civil
              </label>
              <select v-model="formData.estado_civil" class="control-formulario" required>
                <option value="soltero">Soltero</option>
                <option value="casado">Casado</option>
                <option value="divorciado">Divorciado</option>
                <option value="viudo">Viudo</option>
                <option value="otro">Otro</option>
              </select>
            </div>
  
            <div class="grupo-formulario">
              <label for="telefono">
                <i class="bi bi-telephone"></i>
                Teléfono
              </label>
              <input 
                id="telefono" 
                v-model="formData.telefono" 
                type="tel" 
                class="control-formulario" 
                placeholder="Número de teléfono"
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="email">
                <i class="bi bi-envelope"></i>
                Email
              </label>
              <input 
                id="email" 
                v-model="formData.email" 
                type="email" 
                class="control-formulario" 
                placeholder="correo@ejemplo.com"
              />
            </div>
  
            <div class="grupo-formulario ancho-completo">
              <label for="direccion">
                <i class="bi bi-geo-alt"></i>
                Dirección
              </label>
              <input 
                id="direccion" 
                v-model="formData.direccion" 
                class="control-formulario" 
                placeholder="Dirección completa"
              />
            </div>
          </div>
        </div>
  
        <!-- Información Migratoria -->
        <div class="seccion-formulario">
          <div class="encabezado-seccion">
            <i class="bi bi-globe2"></i>
            <h3>Información Migratoria</h3>
          </div>
          <div class="grid-formulario">
            <div class="grupo-formulario">
              <label for="pais">
                <i class="bi bi-flag"></i>
                País de Origen
              </label>
              <input 
                id="pais" 
                v-model="formData.pais" 
                class="control-formulario" 
                required 
                placeholder="País de origen"
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="llegada_a_canada">
                <i class="bi bi-airplane"></i>
                Llegada a Canadá
              </label>
              <input 
                id="llegada_a_canada" 
                v-model="formData.llegada_a_canada" 
                type="date" 
                class="control-formulario" 
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="punto_de_acceso">
                <i class="bi bi-geo"></i>
                Punto de Acceso
              </label>
              <select v-model="formData.punto_de_acceso" class="control-formulario" required>
                <option value="aeropuerto">Aeropuerto</option>
                <option value="terrestre">Terrestre</option>
                <option value="maritimo">Marítimo</option>
                <option value="otro">Otro</option>
              </select>
            </div>
  
            <div class="grupo-formulario">
              <label for="pasaporte">
                <i class="bi bi-passport"></i>
                Pasaporte
              </label>
              <input 
                id="pasaporte" 
                v-model="formData.pasaporte" 
                class="control-formulario" 
                placeholder="Número de pasaporte"
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="estatus">
                <i class="bi bi-check-circle"></i>
                Estatus
              </label>
              <input 
                id="estatus" 
                v-model="formData.estatus" 
                class="control-formulario" 
                required 
                placeholder="Estatus migratorio"
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="permiso_de_trabajo">
                <i class="bi bi-file-earmark-text"></i>
                Permiso de Trabajo
              </label>
              <input 
                id="permiso_de_trabajo" 
                v-model="formData.permiso_de_trabajo" 
                class="control-formulario" 
                placeholder="Número de permiso"
              />
            </div>
          </div>
        </div>
  
        <!-- Información Profesional -->
        <div class="seccion-formulario">
          <div class="encabezado-seccion">
            <i class="bi bi-briefcase"></i>
            <h3>Información Profesional</h3>
          </div>
          <div class="grid-formulario">
            <div class="grupo-formulario">
              <label for="profesion">
                <i class="bi bi-person-workspace"></i>
                Profesión
              </label>
              <input 
                id="profesion" 
                v-model="formData.profesion" 
                class="control-formulario" 
                placeholder="Profesión actual"
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="lenguaje">
                <i class="bi bi-translate"></i>
                Idioma Preferido
              </label>
              <input 
                id="lenguaje" 
                v-model="formData.lenguaje" 
                class="control-formulario" 
                placeholder="Idioma principal"
              />
            </div>
  
            <div class="grupo-formulario">
              <label for="iuc">
                <i class="bi bi-card-text"></i>
                IUC
              </label>
              <input 
                id="iuc" 
                v-model="formData.iuc" 
                class="control-formulario" 
                placeholder="Número de IUC"
              />
            </div>
          </div>
        </div>
  
        <!-- Familia -->
        <div class="seccion-formulario">
          <div class="encabezado-seccion">
            <i class="bi bi-people"></i>
            <h3>Familia</h3>
          </div>
          <div class="grid-formulario">
            <div v-for="(familiar, index) in formData.familia" :key="index" class="grupo-formulario ancho-completo">
              <div class="familiar-item">
                <input 
                  v-model="familiar.nombre" 
                  class="control-formulario" 
                  placeholder="Nombre del familiar"
                />
                <select v-model="familiar.parentesco" class="control-formulario">
                  <option value="">Seleccione un parentesco</option>
                  <optgroup label="Familia Directa">
                    <option value="padre">Padre</option>
                    <option value="madre">Madre</option>
                    <option value="hijo">Hijo/a</option>
                    <option value="hermano">Hermano/a</option>
                    <option value="abuelo">Abuelo/a</option>
                    <option value="sobrino">Sobrino/a</option>
                    <option value="nieto">Nieto/a</option>
                  </optgroup>
                  <optgroup label="Familia Política">
                    <option value="esposo">Esposo/a</option>
                    <option value="suegro">Suegro/a</option>
                    <option value="cuñado">Cuñado/a</option>
                  </optgroup>
                  <optgroup label="Otros Familiares">
                    <option value="tio">Tío/a</option>
                    <option value="primo">Primo/a</option>
                    <option value="otro">Otro</option>
                  </optgroup>
                </select>
                <button type="button" @click="removeFamiliar(index)" class="btn-eliminar">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
            <div class="grupo-formulario ancho-completo">
              <button type="button" @click="addFamiliar" class="btn-agregar">
                <i class="bi bi-plus"></i> Agregar Familiar
              </button>
            </div>
          </div>
        </div>
  
        <!-- Observaciones -->
        <div class="seccion-formulario">
          <div class="encabezado-seccion">
            <i class="bi bi-journal-text"></i>
            <h3>Observaciones</h3>
          </div>
          <div class="grid-formulario">
            <div class="grupo-formulario ancho-completo">
              <textarea 
                v-model="formData.observaciones" 
                class="control-formulario" 
                rows="3"
                placeholder="Observaciones adicionales..."
              ></textarea>
            </div>
          </div>
        </div>
  
        <div class="acciones-formulario">
          <button type="button" class="btn btn-secundario" @click="cancelar">
            <i class="bi bi-x"></i>
            Cancelar
          </button>
          <button 
            type="submit" 
            class="btn btn-primario" 
            :disabled="isLoading"
          >
            <i class="bi bi-check2"></i>
            {{ isLoading ? 'Guardando...' : 'Crear Cliente' }}
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue'
  
  export default {
    setup() {
      const formData = ref({
        nombre_de_cliente: '',
        fecha_de_nacimiento: '',
        genero: '',
        estado_civil: '',
        pais: '',
        llegada_a_canada: '',
        punto_de_acceso: '',
        pasaporte: '',
        estatus: '',
        direccion: '',
        telefono: '',
        email: '',
        profesion: '',
        lenguaje: '',
        permiso_de_trabajo: '',
        iuc: '',
        observaciones: '',
        familia: []
      })
      const isLoading = ref(false)
  
      const getCurrentDate = () => {
        const date = new Date()
        return date.toISOString().split('T')[0]
      }
  
      const resetForm = () => {
        Object.keys(formData.value).forEach(key => {
          if (key === 'familia') {
            formData.value[key] = []
          } else {
            formData.value[key] = ''
          }
        })
      }
  
      const addFamiliar = () => {
        formData.value.familia.push({ nombre: '', parentesco: '' })
      }
  
      const removeFamiliar = (index) => {
        formData.value.familia.splice(index, 1)
      }
  
      const cancelar = () => {
        window.location.href = '/client'
      }
  
      const handleSubmit = async () => {
        try {
          isLoading.value = true
          
          const dataToSend = {
            ...formData.value,
            fecha_de_nacimiento: formData.value.fecha_de_nacimiento 
              ? new Date(formData.value.fecha_de_nacimiento).toISOString().split('T')[0] 
              : null,
            llegada_a_canada: formData.value.llegada_a_canada 
              ? new Date(formData.value.llegada_a_canada).toISOString().split('T')[0] 
              : null,
          }
  
          const response = await fetch('/client', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(dataToSend)
          })
  
          if (!response.ok) {
            const errorData = await response.json()
            throw new Error(errorData.message || 'Error al crear el cliente')
          }
  
          const result = await response.json()
          alert('Cliente creado exitosamente')
          window.location.href = `/client/${result.id}/edit`
        } catch (error) {
          console.error('Error:', error)
          alert(error.message || 'Error al crear el cliente')
        } finally {
          isLoading.value = false
        }
      }
  
      return {
        formData,
        isLoading,
        handleSubmit,
        getCurrentDate,
        resetForm,
        addFamiliar,
        removeFamiliar,
        cancelar
      }
    }
  }
  </script>
  
  <style scoped>
  .crear-cliente-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    border-radius: 1rem;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
  }
  
  .titulo-principal {
    color: #ffffff;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2rem;
  }
  
  .formulario-cliente {
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
  
  .seccion-formulario {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
  }
  
  .seccion-formulario:hover {
    transform: translateY(-2px);
  }
  
  .encabezado-seccion {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f0f0;
  }
  
  .encabezado-seccion i {
    font-size: 1.5rem;
    color: #3498db;
  }
  
  .encabezado-seccion h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.25rem;
  }
  
  .grid-formulario {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
  }
  
  .grupo-formulario {
    margin-bottom: 1rem;
  }
  
  .grupo-formulario.ancho-completo {
    grid-column: 1 / -1;
  }
  
  .grupo-formulario label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
  }
  
  .grupo-formulario label i {
    color: #3498db;
  }
  
  .control-formulario {
    width: 100%;
    padding: 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
  }
  
  .control-formulario:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
  }
  
  .control-formulario::placeholder {
    color: #adb5bd;
  }
  
  .familiar-item {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
  }
  
  .btn-eliminar {
    background: #dc3545;
    color: white;
    border: none;
    padding: 0.5rem;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  
  .btn-eliminar:hover {
    background: #c82333;
  }
  
  .btn-agregar {
    background: #28a745;
    color: white;
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  
  .btn-agregar:hover {
    background: #218838;
  }
  
  .acciones-formulario {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
  }
  
  .btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
  }
  
  .btn i {
    font-size: 1.1rem;
  }
  
  .btn-primario {
    background: linear-gradient(135deg, #3498db, #2c3e50);
    color: white;
  }
  
  .btn-primario:hover {
    background: linear-gradient(135deg, #2980b9, #2c3e50);
  }
  
  .btn-secundario {
    background: #6c757d;
    color: white;
  }
  
  .btn-secundario:hover {
    background: #5a6268;
  }
  
  .btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
  }
  
  @media (max-width: 768px) {
    .crear-cliente-container {
      padding: 1rem;
    }
  
    .grid-formulario {
      grid-template-columns: 1fr;
    }
  
    .acciones-formulario {
      flex-direction: column;
    }
  
    .btn {
      width: 100%;
    }
  }
  </style>
  
  
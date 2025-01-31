<template>
  <div class="login-container">
    <div class="bg-animate"></div>
    <div id="particles-js" class="background-animation"></div>
    <div class="content">
      <h1 class="welcome-text">Bienvenido a ROA Services Juridiques</h1>
      <div class="logo-container">
        <img src="/images/logo.png" alt="Logo" class="logo">
      </div>
      <p class="tagline">Innovación y Excelencia en Servicios Migratorios</p>
      
      <form @submit.prevent="submitForm" class="login-form">
        <div class="form-group">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            name="email"
            required
            autofocus
            class="form-input"
          />
          <span v-if="errors.email" class="error-message">{{ errors.email[0] }}</span>
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Contraseña</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            name="password"
            required
            class="form-input"
          />
          <span v-if="errors.password" class="error-message">{{ errors.password[0] }}</span>
        </div>

        <div class="form-group remember-forgot">
          <div class="remember-me">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              name="remember"
              class="remember-checkbox"
            />
            <label for="remember" class="remember-label">Recuérdame</label>
          </div>
          <a :href="forgotPasswordUrl" class="forgot-password">
            ¿Olvidaste tu contraseña?
          </a>
        </div>

        <button
          type="submit"
          :disabled="processing"
          class="submit-button"
        >
          {{ processing ? 'Procesando...' : 'Iniciar Sesión' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const processing = ref(false);
const errors = ref({});

const form = reactive({
  email: '',
  password: '',
  remember: false
});

const forgotPasswordUrl = '/password/reset';

const submitForm = async () => {
  processing.value = true;
  errors.value = {};

  try {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

    const response = await axios.post('/login', form);
    
    // Manejar la respuesta del servidor
    if (response.data.redirect) {
      // Si el servidor proporciona una URL de redirección, usarla
      window.location.href = response.data.redirect;
    } else {
      // Si no hay redirección específica, ir a la página de inicio por defecto
      window.location.href = '/home';
    }
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
  } finally {
    processing.value = false;
  }
};

onMounted(() => {
  initAnimation();
  initParticles();
});

const initAnimation = () => {
  const content = document.querySelector('.content');
  content.classList.add('animate-in');
};

const initParticles = () => {
  if (window.particlesJS) {
    window.particlesJS('particles-js', {
      particles: {
        number: { value: 60, density: { enable: true, value_area: 800 } },
        color: { value: '#ffffff' },
        shape: { type: 'circle' },
        opacity: {
          value: 0.5,
          random: true,
          animation: { enable: true, speed: 1, minimumValue: 0.1, sync: false }
        },
        size: {
          value: 3,
          random: true,
          animation: { enable: true, speed: 2, minimumValue: 0.1, sync: false }
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: '#ffffff',
          opacity: 0.4,
          width: 1
        },
        move: {
          enable: true,
          speed: 2,
          direction: 'none',
          random: true,
          straight: false,
          out_mode: 'out',
          bounce: false,
        }
      },
      interactivity: {
        detect_on: 'canvas',
        events: {
          onhover: { enable: true, mode: 'repulse' },
          onclick: { enable: true, mode: 'push' },
          resize: true
        },
      },
      retina_detect: true
    });
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

.login-container {
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #6f42c1 0%, #4b367c 100%);
}

.bg-animate {
  position: absolute;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, #6f42c1, #4b367c);
  filter: blur(150px);
  animation: bgMove 15s ease infinite;
}

@keyframes bgMove {
  0%, 100% { transform: translate(0, 0); }
  25% { transform: translate(10%, 10%); }
  50% { transform: translate(-5%, -5%); }
  75% { transform: translate(-10%, 5%); }
}

.background-animation {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.content {
  background-color: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  padding: 3rem;
  border-radius: 20px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  text-align: center;
  z-index: 2;
  width: 90%;
  max-width: 500px;
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.8s ease-out;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.content.animate-in {
  opacity: 1;
  transform: translateY(0);
  animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.welcome-text {
  font-size: 2rem;
  color: #fff;
  margin-bottom: 1rem;
  font-weight: 600;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.tagline {
  font-size: 1rem;
  color: #fff;
  margin-bottom: 2rem;
  opacity: 0;
  animation: fadeIn 0.5s ease-out forwards;
  animation-delay: 0.3s;
}

@keyframes fadeIn {
  to { opacity: 1; }
}

.logo-container {
  margin-bottom: 1.5rem;
}

.logo {
  max-width: 150px;
  height: auto;
  display: block;
  margin: 0 auto;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

.login-form {
  text-align: left;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  color: #fff;
  font-size: 0.9rem;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 4px;
  background-color: rgba(255, 255, 255, 0.1);
  color: #fff;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: #fff;
  box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
}

.error-message {
  color: #ff6b6b;
  font-size: 0.8rem;
  margin-top: 0.25rem;
}

.remember-forgot {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
}

.remember-me {
  display: flex;
  align-items: center;
}

.remember-checkbox {
  margin-right: 0.5rem;
}

.remember-label {
  color: #fff;
}

.forgot-password {
  color: #fff;
  text-decoration: none;
  transition: opacity 0.3s ease;
}

.forgot-password:hover {
  opacity: 0.8;
}

.submit-button {
  width: 100%;
  padding: 0.75rem;
  background-color: rgba(255, 255, 255, 0.2);
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 1rem;
}

.submit-button:hover:not(:disabled) {
  background-color: rgba(255, 255, 255, 0.3);
}

.submit-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .content {
    padding: 2rem;
  }

  .welcome-text {
    font-size: 1.75rem;
  }

  .tagline {
    font-size: 0.9rem;
  }
}
</style>
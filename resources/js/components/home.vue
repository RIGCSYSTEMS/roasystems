<template>
  <div class="home-container">
    <div class="bg-animate"></div>
    <div id="particles-js" class="background-animation"></div>
    
    <!-- Nueva sección de usuario -->
    <div class="user-section">
      <div class="user-info">
        <span class="user-name">{{ user.name }}</span>
      </div>
      <div class="user-menu">
        <button @click="toggleUserMenu" class="user-menu-button">
          <span class="icon">▼</span>
        </button>
        <div v-if="showUserMenu" class="user-menu-dropdown">
          <a href="/profile" class="menu-item">
            <span class="icon">👤</span> Perfil
          </a>
          <a href="/settings" class="menu-item">
            <span class="icon">⚙️</span> Configuración
          </a>
          <form @submit.prevent="logout" class="menu-item">
            <button type="submit" class="logout-button">
              <span class="icon">🚪</span> Cerrar sesión
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="content">
      <h1 class="welcome-text">Bienvenido a ROA Services Juridiques</h1>
      <div class="logo-container">
        <img src="/images/logo.png" alt="Logo" class="logo">
      </div>
      <p class="tagline">Innovación y excelencia en servicios migratorios</p>
      <div class="dashboard-buttons">
        <a v-for="(button, index) in buttons" :key="index" :href="button.url" class="dashboard-button">
          <span class="dashboard-button-icon">
            <img v-if="button.icon.startsWith('images/')" :src="button.icon" :alt="button.name">
            <span v-else>{{ button.icon }}</span>
          </span>
          {{ button.name }}
        </a>
      </div>
      <div class="footer-buttons">
        <a href="#agenda" class="footer-button">
          <span class="footer-button-icon">📅</span>
          Agenda
        </a>
        <a href="#llamadas" class="footer-button">
          <span class="footer-button-icon">📞</span>
          Llamadas
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'HomeView',
  props: {
    authUser: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      user: {
        name: this.authUser.name,

      },
      showUserMenu: false,
      buttons: [
        { name: 'Panel de Control', icon: '⚙️', url: '/dashboard' },
  { name: 'Clientes', icon: '👥', url: '/searchClient' },
  { name: 'Expedientes', icon: '📁', url: '/searchExpedient' },
      ]
    }
  },
  mounted() {
    this.initAnimation();
    this.initParticles();
  },
  methods: {
    toggleUserMenu() {
      this.showUserMenu = !this.showUserMenu;
    },
    logout() {
      // Envía una solicitud POST al endpoint de cierre de sesión de Laravel
      axios.post('/logout')
        .then(() => {
          // Redirige al usuario a la página de inicio o de login
          window.location.href = '/login';
        })
        .catch(error => {
          console.error('Error al cerrar sesión:', error);
          // Maneja el error, tal vez mostrando un mensaje al usuario
        });
    },
    clearUserSession() {
      // Limpia cualquier dato de sesión almacenado localmente
      localStorage.removeItem('user');
      // Si estás usando Vuex para manejar el estado global, despacha una acción para limpiar el estado
      // this.$store.dispatch('clearUserSession');
    },
    initAnimation() {
      const content = document.querySelector('.content');
      content.classList.add('animate-in');
    },
    initParticles() {
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
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

.home-container {
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  height: 100vh;
  width: 100vw;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: auto; /* Cambiado de 'hidden' a 'auto' */
  background: linear-gradient(135deg, #6f42c1 0%, #4b367c 100%);
  box-sizing: border-box; /* Añadido */
  padding: 1rem; /* Añadido */
  margin: 0; /* Añadido */
}

@media (max-width: 768px) {
  .home-container {
    padding: 0.5rem;
  }
}

@media (max-width: 480px) {
  .home-container {
    padding: 0.25rem;
  }
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
  max-width: 800px;
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
  font-size: 2.5rem;
  color: #fff;
  margin-bottom: 1.5rem;
  font-weight: 600;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.tagline {
  font-size: 1.2rem;
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
  margin-bottom: 2rem;
}

.logo {
  max-width: 200px;
  height: auto;
  display: block;
  margin: 0 auto;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

.dashboard-buttons {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}

.dashboard-button {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: none;
  padding: 1rem;
  border-radius: 0.5rem;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  text-decoration: none;
  height: 100px;
}

.dashboard-button:hover {
  transform: scale(1.05);
  background-color: rgba(139, 92, 246, 0.5);
  box-shadow: 0 0 15px rgba(139, 92, 246, 0.5);
}

.dashboard-button-icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 60px;
}

.dashboard-button-icon img {
  width: 40px;
  height: 40px;
  object-fit: contain;
}

.footer-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 2rem;
}

.footer-button {
  background-color: #8b5cf6;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 9999px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  text-decoration: none;
}

.footer-button:hover {
  background-color: #7c3aed;
  transform: scale(1.05);
}

.footer-button-icon {
  margin-right: 0.5rem;
  display: inline-block;
  animation: moveUpDown 2s ease-in-out infinite;
}

@keyframes moveUpDown {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}

.user-section {
  position: absolute;
  top: 1rem;
  right: 1rem;
  display: flex;
  align-items: center;
  z-index: 10;
}

.user-info {
  display: flex;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  padding: 0.5rem 1rem;
  border-radius: 2rem;
  margin-right: 0.5rem;
}

.user-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  margin-right: 0.5rem;
}

.user-name {
  color: white;
  font-weight: 500;
}

.user-menu {
  position: relative;
}

.user-menu-button {
  background-color: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: none;
  color: white;
  padding: 0.5rem;
  border-radius: 50%;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.user-menu-button:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

.user-menu-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  width: 200px;
}

.menu-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #333;
  text-decoration: none;
  transition: background-color 0.3s ease;
}

.menu-item:hover {
  background-color: #f0f0f0;
}

.menu-item .icon {
  margin-right: 0.5rem;
}

.logout-button {
  width: 100%;
  text-align: left;
  border: none;
  background: none;
  cursor: pointer;
  font-size: 1rem;
}

@media (max-width: 768px) {
  
  .welcome-text {
    font-size: 2rem;
  }

  .content {
    padding: 2rem;
  }

  .dashboard-buttons {
    grid-template-columns: repeat(2, 1fr);
  }

  .dashboard-button {
    font-size: 0.8rem;
    height: 90px;
  }
  .user-section {
    top: 0.5rem;
    right: 0.5rem;
  }

  .user-info {
    padding: 0.25rem 0.5rem;
  }

  .user-avatar {
    width: 1.5rem;
    height: 1.5rem;
  }

  .user-name {
    font-size: 0.8rem;
  }
}
</style>

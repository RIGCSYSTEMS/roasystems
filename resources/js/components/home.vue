<template>
  <div class="home-container">
    <div class="bg-animate"></div>
    <div id="particles-js" class="background-animation"></div>
    <div class="content">
      <h1 class="welcome-text">Bienvenido a ROA Services Juridiques</h1>
      <div class="logo-container">
        <img src="/images/logo.png" alt="Logo" class="logo">
      </div>
      <p class="tagline">Innovación y Excelencia en Servicios Migratorios</p>
      <div class="dashboard-buttons">
        <a href="/dashboard/client" class="dashboard-button client-button">
          <span class="dashboard-button-icon">👤</span>
          Portal del Cliente
        </a>
        <a href="/dashboard/despacho" class="dashboard-button despacho-button">
          <span class="dashboard-button-icon">🏢</span>
          Área de Abogados
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeView',
  mounted() {
    this.initAnimation();
    this.initParticles();
  },
  methods: {
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
  max-width: 600px;
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
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-top: 2rem;
}

.dashboard-button {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.dashboard-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
  background: rgba(255, 255, 255, 0.3);
}

.client-button {
  background: rgba(255, 255, 255, 0.2);
}

.despacho-button {
  background: rgba(255, 255, 255, 0.2);
}

.dashboard-button-icon {
  margin-right: 0.5rem;
  font-size: 1.3rem;
}

@media (max-width: 768px) {
  .welcome-text {
    font-size: 2rem;
  }

  .content {
    padding: 2rem;
  }

  .dashboard-buttons {
    flex-direction: column;
  }

  .dashboard-button {
    width: 100%;
  }
}
</style>
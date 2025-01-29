@extends('layouts.app')

@section('content')
<div class="denied-container">
    <div class="denied-content">
        <div class="lock-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h1 class="denied-title">Acceso Denegado</h1>
        <p class="denied-message">Lo sentimos, no tienes permiso para acceder a esta página.</p>
        <a href="{{ route('home') }}" class="denied-button">
            <i class="fas fa-home"></i> Volver al Inicio
        </a>
    </div>
    <div class="denied-particles" id="particles-js"></div>
</div>
@endsection

@push('styles')
<style>
    .denied-container {
        min-height: 40vh; /* Cambiado de 100vh a 80vh para dejar espacio al footer */
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #6f42c1 0%, #4b367c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2rem auto; /* Añadido margen arriba y abajo */
        border-radius: 15px; /* Añadido bordes redondeados */
        max-width: 98%; /* Añadido para dejar un pequeño margen en los lados */
    }

    .denied-content {
        text-align: center;
        position: relative;
        z-index: 2;
        padding: 1.5rem; /* Reducido el padding */
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: slideIn 0.5s ease-out;
    }

    .lock-icon {
        font-size: 4rem; /* Reducido el tamaño del ícono */
        color: #fff;
        margin-bottom: 0.5rem; /* Reducido el margen */
        animation: lockShake 1.5s ease-in-out infinite;
    }

    .denied-title {
        color: #fff;
        font-size: 2rem; /* Reducido el tamaño del título */
        margin-bottom: 0.5rem; /* Reducido el margen */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .denied-message {
        color: #fff;
        font-size: 1rem; /* Reducido el tamaño del texto */
        margin-bottom: 1.5rem; /* Reducido el margen */
        opacity: 0;
        animation: fadeIn 0.5s ease-out forwards;
        animation-delay: 0.3s;
    }

    .denied-button {
        display: inline-block;
        padding: 0.75rem 1.5rem; /* Reducido el padding del botón */
        background: #fff;
        color: #6f42c1;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .denied-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        color: #4b367c;
    }

    .denied-particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    @keyframes lockShake {
        0%, 100% { transform: rotate(0); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
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

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    /* Animación del fondo */
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

    /* Media query para pantallas más pequeñas */
    @media (max-height: 600px) {
        .denied-container {
            min-height: 70vh; /* Aún más reducido para pantallas muy pequeñas */
        }
        
        .lock-icon {
            font-size: 3rem;
        }
        
        .denied-title {
            font-size: 1.75rem;
        }
        
        .denied-message {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        particlesJS('particles-js', {
            particles: {
                number: { value: 60, density: { enable: true, value_area: 800 } }, // Reducido el número de partículas
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

        // Añadir efecto de fondo animado
        const container = document.querySelector('.denied-container');
        const bgAnimate = document.createElement('div');
        bgAnimate.className = 'bg-animate';
        container.appendChild(bgAnimate);
    });
</script>
@endpush
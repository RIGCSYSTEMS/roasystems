<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burbujas Púrpura Interactivas</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #4a0e78;
            color: white;
            position: relative;
        }
        /* .logo-container {
            width: 200px;
            height: 200px;
            margin-bottom: 2rem;
            z-index: 10;
            position: relative;
        } */
        .logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* border-radius: 50%; */
            box-shadow: 0 0 20px rgba(255,255,255,0.5);
        }
        .logo-fade {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* border-radius: 50%; */
            background: radial-gradient( transparent 50%, rgba(74,14,120,0.8) 100%);
        }
        .buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            z-index: 10;
        }
        .button {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            font-weight: bold;
            color: #4a0e78;
            background-color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            text-decoration: none;
        }
        .button:hover {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 4s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <a class="logo-container">
            <img src="/images/logo.png" alt="Logo de la empresa" class="logo">
            <div class="logo-fade"></div>
        </a>
        <br>
        <div class="buttons">
            <a href="client" class="button">Clientes</a>
            <a href="resident" class="button">Residentes Temporales</a>
            <a href="call" class="button">Llamadas Entrantes</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.container');
            const buttons = document.querySelectorAll('.button');

            // Crear burbujas
            for (let i = 0; i < 20; i++) {
                createBubble();
            }

            // Animación de entrada para los botones
            buttons.forEach((button, index) => {
                setTimeout(() => {
                    button.style.opacity = '1';
                    button.style.transform = 'translateY(0)';
                }, index * 200);
            });

            function createBubble() {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');
                const size = Math.random() * 100 + 50;
                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${Math.random() * 100}%`;
                bubble.style.top = `${Math.random() * 100}%`;
                bubble.style.animationDuration = `${Math.random() * 2 + 4}s`;
                container.appendChild(bubble);
            }
        });
    </script>
</body>
</html>





















































{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio Interactiva</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
        }
        .logo {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }
        .buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }
        .button {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            font-weight: bold;
            color: #6a11cb;
            background-color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }
        .button:hover {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="logo" class="logo">TU LOGO AQUÍ</div>
        <div class="buttons">
            <button class="button">Descubre</button>
            <button class="button">Explora</button>
            <button class="button">Comienza</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const logo = document.getElementById('logo');
            const buttons = document.querySelectorAll('.button');

            // Animación de entrada para los botones
            buttons.forEach((button, index) => {
                setTimeout(() => {
                    button.style.opacity = '1';
                    button.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Efecto de rotación del logo
            document.addEventListener('mousemove', (e) => {
                const centerX = window.innerWidth / 2;
                const centerY = window.innerHeight / 2;
                const maxRotation = 10;

                const rotateY = ((e.clientX - centerX) / centerX) * maxRotation;
                const rotateX = ((e.clientY - centerY) / centerY) * -maxRotation;

                logo.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
        });
    </script>
</body>
</html> --}}















































{{-- @extends('layouts.app')

@section('title', 'ROASYSTEMS')

@section('content')
    <style>
    
    .center {
        text-align: center;
        margin-top: 20%;
        color: #ff3116;
    }
     </style>
    <div class="center">
        <h1>ROASYSTEMS ------- HOME</h1>   
    </div>
    
@endsection --}}

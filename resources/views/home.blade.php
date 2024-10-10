<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ROASYSTEMS</title>
    <style>
        /* Estilos existentes */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4a1d96, #7e22ce, #4338ca);
            color: white;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            /* border-radius: 1rem; */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo-container {
            display: inline-block;
            margin-bottom: 2rem;
            position: relative;
        }
        .logo {
            max-width: 100%;
            height: auto;
            display: block;
        }
        .logo-effect {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* border-radius: 50%; */
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.2);
            animation: pulso 2s infinite;
        }
        @keyframes pulso {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }
        /* Resto de los estilos existentes */
        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .button {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 100px;
        }
        .button:hover {
            transform: scale(1.05);
            background-color: rgba(139, 92, 246, 0.5);
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.5);
        }
        .button-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
        }
        @keyframes moveUpDown {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        @keyframes moveSideToSide {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }
        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
        }
        .button:nth-child(3n+1) .button-icon {
            animation: moveUpDown 2s ease-in-out infinite;
        }
        .button:nth-child(3n+2) .button-icon {
            animation: moveSideToSide 2s ease-in-out infinite;
        }
        .button:nth-child(3n) .button-icon {
            animation: rotate 2s ease-in-out infinite;
        }
        .button:nth-child(7) {
    grid-column: 2;
    grid-row: 3;
}
        .footer-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .button-icon img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}
.button-0 .button-icon img {
        width: 80px; 
        height: 80px;
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
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <div class="logo-effect"></div>
        </div>
        <div class="buttons-grid">
            @php
            $buttons = [
                ['name' => 'ASILO', 'icon' => 'images/asilo.png'],
                ['name' => 'APPEL', 'icon' => '🏠'],
                ['name' => 'RESIDENCIA PERMANENTE', 'icon' => 'images/card.png'],
                ['name' => 'ERAR', 'icon' => '🚫'],
                ['name' => 'APADRINAMIENTO', 'icon' => '👥'],
                ['name' => 'HUMANITARIAS', 'icon' => 'images/humanitario.png'],
                ['name' => 'RESIDENCIA TEMPORAL', 'icon' => 'images/visa.png'],
            ];
            @endphp


    @foreach ($buttons as $index => $button)
    <button class="button button-{{ $index }}">
                <div class="button-icon">
                    @if (strpos($button['icon'], 'images/') === 0)
                        <img src="{{ asset($button['icon']) }}" alt="{{ $button['name'] }}">
                    @else
                        {{ $button['icon'] }}
                    @endif
                </div>
                <div>{{ $button['name'] }}</div>
            </button>
            @endforeach
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

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 40,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 6,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "repulse"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 400,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });

            // Animación simple para los botones
            const buttons = document.querySelectorAll('.button');
            buttons.forEach(button => {
                button.addEventListener('mouseover', () => {
                    button.style.transform = 'scale(1.05) rotate(5deg)';
                });
                button.addEventListener('mouseout', () => {
                    button.style.transform = 'scale(1) rotate(0deg)';
                });
            });
        });
    </script>
</body>
</html>
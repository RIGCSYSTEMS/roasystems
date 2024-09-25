<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ROA SYSTEMS')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #F3E5F5;
        }
        header {
            background-color: #8E24AA;
            color: white;
            padding: 6px;
        }
        .container {
            width: 90%;
            max-width: 12000%;
            margin: 1px auto;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            margin-right: 1rem;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
        }
        nav ul li {
            margin-left: 1rem;
            margin-bottom: 0.5rem;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        nav ul li a:hover {
            color: #E1BEE7;
        }
        main {
            flex-grow: 1;
            padding: 2rem 0;
        }
        .content-area {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        footer {
            background-color: #4A148C;
            flex-direction: column;
            color: white;
            padding: 1rem 0;
        }
        .footer-content {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            flex-wrap: wrap;
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 0.6rem;
        }
        .footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.8rem;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .footer-links a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer-links a:hover {
            color: rgb(190, 141, 235);
        }
        @media (max-width: 768px) {
            .header-content, .footer-content {
                flex-direction: column;
                align-items: flex-start;
            }
            nav ul {
                margin-top: 1rem;
            }
            .footer-content > div {
                margin-bottom: 1rem;
            }
            .footer-links {
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="50" height="50">
                <h1>ROA SYSTEMS</h1>
            </div>
            <nav>
                <ul>
                    @php
                        $links = ['Inicio', 'Clientes', 'Residentes Temporales', 'Llamadas Entrantes'];
                    @endphp
                    @foreach($links as $link)
                        @php
                            if ($link == 'Inicio') {
                                $url = '/';
                            } elseif ($link == 'Clientes') {
                                $url = '/clientes';
                            } elseif ($link == 'Residentes Temporales') {
                                $url = '/residentes';
                            } elseif ($link == 'Llamadas Entrantes') {
                                $url = '/llamadas';
                            } else {
                                $url = '/' . strtolower(str_replace(' ', '-', $link));
                            }


                        @endphp
                    
                        <li><a href="{{ $url }}">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <div class="container footer-content">
            {{-- <div class="logo"> --}}
                {{-- <img src="{{ asset('images/logo-small.png') }}" alt="Logo pequeño" width="30" height="30"> --}}
                <span>&copy; {{ date('Y') }} Developed by RIGCSYSTEMS.COM</span>
                </span>
            </div>
            <div class="footer-links">
                <a href="https://www.canada.ca/">Formularie Web d'IRCC</a>
                <a href="https://www.canada.ca/">Medicin Designe</a>
                <a href="https://www.canada.ca/">Delais</a>
                <a href="https://www.canada.ca/">Preuve Electroniques</a>
                <a href="https://www.canada.ca/">Etat de la demande du client</a>
                <a href="https://www.canada.ca/">Mon Dossier</a>
                <a href="https://www.canada.ca/">Rapports</a>
                <a href="https://www.canada.ca/">Calcul de Taxes Quebec</a>
                <a href="https://www.canada.ca/">Calcul Inverse de Taxes Quebec</a>
            </div>
        </div>
    </footer>
</body>
</html>













































{{-- 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ROASYSTEMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header>
    </header>






    <footer>
    </footer>

</body>
</html> --}}

{{-- <div class ="max-w-4xl mx-auto px-4">
        <H1>Bienvenido a ROASYSTEMS</H1>

        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
            <span class="font-medium">Info alert!</span> Change a few things up and try submitting again.
          </div>
    </div> --}}
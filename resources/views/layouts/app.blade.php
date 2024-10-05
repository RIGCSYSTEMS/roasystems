<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ROA SYSTEMS')</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="180" height="80">
                <h1>ROA SYSTEMS</h1>
            </div>
            <nav>
                <ul>
                    @php
                        $links = ['Inicio', 'Clientes', 'Residentes Temporales', 'Llamadas Entrantes'];
                    @endphp
                    @foreach ($links as $link)
                        @php
                            if ($link == 'Inicio') {
                                $url = '/';
                            } elseif ($link == 'Clientes') {
                                $url = '/client';
                            } elseif ($link == 'Residentes Temporales') {
                                $url = '/resident';
                            } elseif ($link == 'Llamadas Entrantes') {
                                $url = '/call';
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
        {{-- <div class="content-area"> --}}
        {{-- @yield('content')
        </div> --}}

        <div class="card">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
        </div>
    </main>

    <footer>
        <div class="container footer-content">
            <span>&copy; {{ date('Y') }} Developed by RIGCSYSTEMS.COM</span>
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

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

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

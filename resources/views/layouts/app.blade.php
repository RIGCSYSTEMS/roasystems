<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ROA SYSTEMS')</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        @keyframes headerAnimation {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        
        header {
            background: linear-gradient(-30deg, #ffffff, #8a2be2, #ffffff, #9370db);
            background-size: 400% 400%;
            animation: headerAnimation 10s ease infinite;
        }
        
        footer {
            background-color: #7d0dee;
            color: #ffffff;
            font-size: 14px;
            height: 80px;
            font-weight: bold;
        }
        
        header .nav-link {
            color: #4a0e78;
            font-weight: bold;
        }
        
        footer .nav-link {
            color: #ffffff !important;
        }
        
        footer .navbar-nav .nav-link {
            color: #ffffff;
        }
        
        header .nav-link:hover, footer .nav-link:hover {
            color: #e0e0e0;
            transition: color 0.3s ease;
        }

        .nav-container {
            display: flex;
            justify-content: flex-end;
        }

        .nav-container .nav-link {
            padding: 0.5rem 1rem;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .logo-container, .copyright {
            padding-left: 0;
        }

        .navbar {
            padding-left: 0;
            padding-right: 0;
        }

        .main-content {
            flex: 1;
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="py-3 mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-3 d-flex align-items-center logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="180" height="80" class="img-fluid me-3">
                    <h1 class="h3 mb-0">ROA SYSTEMS</h1>
                </div>
                <div class="col-md-9">
                    <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
                        <ul class="navbar-nav">
                            @foreach (config('navigation.main') as $text => $url)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ $url }}">{{ $text }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content container-fluid my-4">
        <div class="card shadow">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="py-4 mt-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <span class="copyright">&copy; {{ date('Y') }} Developed by RIGCSYSTEMS.COM</span>
                </div>
                <div class="col-md-9">
                    <nav class="navbar navbar-expand-lg navbar-dark justify-content-end">
                        <ul class="navbar-nav">
                            @foreach (config('navigation.footer') as $text => $url)
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ $url }}">{{ $text }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
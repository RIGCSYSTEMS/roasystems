<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ROA SYSTEMS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/dataTables-config.js') }}"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
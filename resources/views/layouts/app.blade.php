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
    <script src="{{ asset('js/loading-indicator.js') }}"></script>
    <style>
        .v-line {
            border-left: thick solid #00ff77;
            height: 100%;
            left: 50%;
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('partials.loading-indicator')
    <header class="py-3 mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                {{-- <div class="col-md-3 d-flex align-items-center logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="180" height="80"
                        class="img-fluid me-3">
                    <h1 class="h3 mb-0">ROA SYSTEMS</h1>
                </div> --}}
                <div class="col-12">

                    <nav class="navbar navbar-expand-lg bg-body-tertiary"
                        style="background-color: transparent!important">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="{{url('/')}}">
                                <img src="{{ asset('images/logo.png') }}" width="30" height="30"
                                    class="d-inline-block align-top" alt="">
                                ROA SYSTEMS
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">



                                    @foreach (config('navigation.main') as $text => $url)
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ $url }}">{{ $text }}</a>
                                        </li>
                                    @endforeach

                                </ul>

                            </div>
                        </div>
                    </nav>



                    {{-- <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
                        <ul class="navbar-nav">
                            @foreach (config('navigation.main') as $text => $url)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ $url }}">{{ $text }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav> --}}
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

    <footer class="p-5 mt-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <span class="copyright">&copy; {{ date('Y') }} Developed by RIGCSYSTEMS.COM</span>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        @foreach (config('navigation.footer') as $text => $url)
                            <div class="col-md-3 my-2 v-line">
                                <a class="nav-link text-white" href="{{ $url }}">{{ $text }}</a>
                            </div>
                        @endforeach
                    </div>
                    {{-- <nav class="navbar navbar-expand-lg navbar-dark justify-content-end">
                        <ul class="navbar-nav">
                            @foreach (config('navigation.footer') as $text => $url)
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ $url }}">{{ $text }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav> --}}
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

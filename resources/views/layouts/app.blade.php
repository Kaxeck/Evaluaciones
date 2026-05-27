<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistema de Evaluacion')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/53c524d9a6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Estructura principal con sidebar fijo y contenido dinámico -->
    <div class="d-flex" style="min-height: 100vh;">
        
        <!-- Sidebar fijo con navegación -->
        <div id="sidebar" class="sidebar-fijo d-flex flex-column flex-shrink-0 p-3 bg-light shadow-sm">
            <a href="{{ route('inicio') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none px-2">
                <span class="brand-badge-sidebar me-2">
                    <i class="fa-solid fa-graduation-cap"></i>
                </span>
                <span class="fs-6 fw-bold brand-text-sidebar">Sistema de Evaluación</span>
            </a>
            <hr>
            <!-- Navegación del sidebar con íconos personalizados -->
            <ul class="nav nav-pills flex-column mb-auto sidebar-sections gap-1">
                <li class="nav-item">
                    <a href="{{ route('inicio') }}" class="nav-link {{ request()->routeIs('inicio') ? 'active' : 'link-dark' }}">
                        <span class="sidebar-icon-circle">
                            <i class="fa-solid fa-house"></i>
                        </span>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="{{ route('centros') }}" class="nav-link {{ request()->routeIs('centros') ? 'active' : 'link-dark' }}">
                        <span class="sidebar-icon-circle">
                            <img src="{{ asset('centros.png') }}" alt="Centros" class="img-fluid">
                        </span>
                        Centros
                    </a>
                </li>
                <li>
                    <a href="{{ route('alumnos') }}" class="nav-link {{ request()->routeIs('alumnos') ? 'active' : 'link-dark' }}">
                        <span class="sidebar-icon-circle">
                            <img src="{{ asset('alumnos.png') }}" alt="Alumnos" class="img-fluid">
                        </span>
                        Alumnos
                    </a>
                </li>
                <li>
                    <a href="{{ route('calificaciones') }}" class="nav-link {{ request()->routeIs('calificaciones') ? 'active' : 'link-dark' }}">
                        <span class="sidebar-icon-circle">
                            <img src="{{ asset('calificaciones.png') }}" alt="Calificaciones" class="img-fluid">
                        </span>
                        Calificaciones
                    </a>
                </li>
            </ul>
            <hr>
            <!-- Dropdown de usuario en el sidebar -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle px-2" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>Usuario</strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#">Nuevo proyecto...</a></li>
                    <li><a class="dropdown-item" href="#">Ajustes</a></li>
                    <li><a class="dropdown-item" href="#">Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Salir</a></li>
                </ul>
            </div>
        </div>
        <div class="sidebar-backdrop" data-sidebar-backdrop></div>
        <!-- Contenido principal con navbar y sección dinámica -->
        <div class="main-wrapper d-flex flex-column flex-fill">
            
            <nav class="navbar navbar-expand-lg navbar-dark app-navbar py-3 sticky-top">
                <div class="container-fluid px-lg-4 d-flex align-items-center justify-content-between gap-3">
                    <button class="btn btn-light sidebar-toggle-btn d-lg-none" type="button" aria-controls="sidebar" aria-expanded="false" data-sidebar-toggle>
                        <i class="fa-solid fa-bars"></i>
                        <span class="ms-2">Menú</span>
                    </button>
                    <form class="d-flex nav-search" role="search" style="width: 100%; max-width: 400px;" onsubmit="return false;">
                            <input id="global-search-input" name="q" class="form-control bg-white text-black me-2" type="search" placeholder="Buscar..." aria-label="Search" autocomplete="off" />
                            <button id="global-search-btn" class="btn btn-light fw-semibold px-3" type="button">Buscar</button>
                        </form>
                </div>
            </nav>

            <main class="container py-5 flex-fill">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/search.js') }}"></script>
</body>
</html>
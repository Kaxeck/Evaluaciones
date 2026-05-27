<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistema de Evaluacion')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/53c524d9a6.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand p-2" href="#"><i class="fa-solid fa-graduation-cap"></i></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" href="{{ route('inicio') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('centros') ? 'active' : '' }}" href="{{ route('centros') }}">Centros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('alumnos') ? 'active' : '' }}" href="{{ route('alumnos') }}">Alumnos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('calificaciones') ? 'active' : '' }}" href="{{ route('calificaciones') }}">Calificaciones</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2 bg-white text-black border-0" type="search" placeholder="Buscar" aria-label="Search" />
                    <button class="btn btn-outline-light" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>

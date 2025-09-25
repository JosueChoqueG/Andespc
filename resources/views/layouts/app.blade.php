<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Andes PC Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Andes PC Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Gestión
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('agencias.index') }}">Agencias</a></li>
                            <li><a class="dropdown-item" href="{{ route('oficinas.index') }}">Oficinas</a></li>
                            <li><a class="dropdown-item" href="{{ route('marcas.index') }}">Marcas</a></li>
                            <li><a class="dropdown-item" href="{{ route('modelos.index') }}">Modelos</a></li>
                            <li><a class="dropdown-item" href="{{ route('tipos.index') }}">Tipos de Equipo</a></li>
                            <li><a class="dropdown-item" href="{{ route('hardwares.index') }}">Hardware</a></li>
                            <li><a class="dropdown-item" href="{{ route('sistemas.index') }}">Sistemas Operativos</a></li>
                            <li><a class="dropdown-item" href="{{ route('responsables.index') }}">Responsables</a></li>
                            <li><a class="dropdown-item" href="{{ route('equipos.index') }}">Equipos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Salir</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>


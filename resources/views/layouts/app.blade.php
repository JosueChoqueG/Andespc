<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Andes Admin')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@include('auth.register')

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark futuristic-navbar shadow-sm">
        <div class="container-fluid px-4">

            <!-- LOGO / BRAND -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="bi bi-cpu-fill me-2 text-info"></i>
                Andes Admin
            </a>

            <!-- TOGGLER -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- NAV CONTENT -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- LEFT MENU -->
                <ul class="navbar-nav me-auto gap-2">
                    <!-- DROPDOWN -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-pc-display me-1"></i> Gestión PC
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark shadow-lg">
                            <li><a class="dropdown-item" href="{{ route('agencias.index') }}">Agencias</a></li>
                            <li><a class="dropdown-item" href="{{ route('oficinas.index') }}">Oficinas</a></li>
                            <li><a class="dropdown-item" href="{{ route('marcas.index') }}">Marcas</a></li>
                            <li><a class="dropdown-item" href="{{ route('modelos.index') }}">Modelos</a></li>
                            <li><a class="dropdown-item" href="{{ route('tipoequipos.index') }}">Tipos de Equipo</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('hardwares.index') }}">Hardware</a></li>
                            <li><a class="dropdown-item" href="{{ route('sistemaoperativos.index') }}">Sistemas
                                    Operativos</a></li>
                            <li><a class="dropdown-item" href="{{ route('responsables.index') }}">Responsables</a></li>
                            <li><a class="dropdown-item" href="{{ route('equipos.index') }}">Equipos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-printer me-1"></i> Gestión Impresoras
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark shadow-lg">
                            <li><a class="dropdown-item" href="{{ route('admin.impresoras.index') }}">Listado de
                                    impresoras</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- RIGHT USER SECTION -->
                <ul class="navbar-nav align-items-center gap-3">

                    <!-- REGISTER -->
                    <li class="nav-item">
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="bi bi-person-plus"></i>
                        </button>
                    </li>

                    <!-- USER INFO -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ?? asset('img/user-default.png') }}"
                                class="user-avatar me-2" alt="Usuario">
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>

</html>
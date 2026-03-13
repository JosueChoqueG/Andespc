<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Andes Admin')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@include('auth.register')
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="navbar navbar-expand-lg navbar-dark futuristic-navbar shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <button class="btn btn-link text-white d-lg-none me-2" id="sidebarToggle">
                <i class="bi bi-list fs-3"></i>
            </button>
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="bi bi-cpu-fill me-2 text-info"></i> Andes Admin
            </a>
            <div class="ms-auto d-flex align-items-center">
                <!-- REGISTER -->
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="bi bi-person-plus"></i>
                </button>
                <!-- Fin REGISTER --> 
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar ?? asset('img/user-default.png') }}" class="rounded-circle me-2" width="35">
                        <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <aside class="sidebar-soft" id="sidebarMenu">
            <div class="section-title">Bitácora de Incidencias</div>
            <a href="{{ route('admin.incidencias.listado') }}" class="nav-link-main {{ Request::routeIs('admin.incidencias.listado') ? 'active-section' : '' }} bg-transparent shadow-none border-0">
                <div class="icon-box-white"><i class="bi bi-journal-text text-danger"></i></div>
                <span class="text-secondary">Bitácora</span>
            </a>

            <div class="section-title">Inventario</div>
            @php 
                $isPcOpen = Request::routeIs([
                    'responsables.*', 'marcas.*', 'hardwares.*', 'agencias.*',
                    'oficinas.*', 'modelos.*', 'tipoequipos.*', 'sistemaoperativos.*', 'equipos.*'
                ]);
            @endphp

            <a class="nav-link-main {{ $isPcOpen ? 'active-section' : '' }} bg-transparent shadow-none border-0" data-bs-toggle="collapse" href="#pcCollapse" aria-expanded="{{ $isPcOpen ? 'true' : 'false' }}">
                <div class="icon-box-white"><i class="bi bi-pc-display text-primary"></i></div>
                <span class="flex-grow-1 text-secondary">Gestión PC</span>
                <i class="bi bi-chevron-down small opacity-50"></i>
            </a>
            <div class="collapse {{ $isPcOpen ? 'show' : '' }}" id="pcCollapse">
                <a href="{{ route('agencias.index') }}" class="nav-sub-item {{ Request::routeIs('agencias.index') ? 'active' : '' }}">Agencias</a>
                <a href="{{ route('oficinas.index') }}" class="nav-sub-item {{ Request::routeIs('oficinas.index') ? 'active' : '' }}">Oficinas</a>
                <a href="{{ route('marcas.index') }}" class="nav-sub-item {{ Request::routeIs('marcas.index') ? 'active' : '' }}">Marcas</a>
                <a href="{{ route('modelos.index') }}" class="nav-sub-item {{ Request::routeIs('modelos.index') ? 'active' : '' }}">Modelos</a>
                <a href="{{ route('tipoequipos.index') }}" class="nav-sub-item {{ Request::routeIs('tipoequipos.index') ? 'active' : '' }}">Tipos de Equipo</a>
                <a href="{{ route('hardwares.index') }}" class="nav-sub-item {{ Request::routeIs('hardwares.index') ? 'active' : '' }}">Hardware</a>
                <a href="{{ route('sistemaoperativos.index') }}" class="nav-sub-item {{ Request::routeIs('sistemaoperativos.index') ? 'active' : '' }}">Sistemas Operativos</a>
                <a href="{{ route('responsables.index') }}" class="nav-sub-item {{ Request::routeIs('responsables.index') ? 'active' : '' }}">Responsables</a>
                <a href="{{ route('equipos.index') }}" class="nav-sub-item {{ Request::routeIs('equipos.index') ? 'active' : '' }}">Equipos</a>
            </div>

            <a class="nav-link-main {{ Request::routeIs('admin.impresoras.index') ? 'active-section' : '' }} bg-transparent shadow-none border-0 mt-2" href="{{ route('admin.impresoras.index') }}">
                <div class="icon-box-white"><i class="bi bi-printer text-success"></i></div>
                <span class="text-secondary">Impresoras</span>
            </a>

            <div class="section-title">Conectividad</div>
            <a href="{{ route('admin.servicios-internet.index') }}" class="nav-link-main {{ Request::routeIs('admin.servicios-internet.index') ? 'active-section' : '' }} bg-transparent shadow-none border-0">
                <div class="icon-box-white"><i class="bi bi-diagram-3 text-primary"></i></div>
                <span class="text-secondary">Internet</span>
            </a>
            <a href="{{ route('sistemaoperativos.index') }}" class="nav-link-main {{ Request::routeIs('sistemaoperativos.index') ? 'active-section' : '' }} bg-transparent shadow-none border-0">
                <div class="icon-box-white"><i class="bi bi-window text-primary"></i></div>
                <span class="text-secondary">Sistemas Op.</span>
            </a>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

</body>
</html>
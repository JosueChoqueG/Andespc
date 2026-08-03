<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Andes Admin')</title>

    <!-- Google Fonts: Plus Jakarta Sans (Cuerpo) + Outfit (Títulos y marcas) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons & Animate.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Paleta Inspirada en los Andes */
            --inti-gold: #f4a261;
            --inti-gold-bright: #e9c46a;
            --terracota: #9f3f04ff;
            --terracota-dark: #0e2e6dff;
            --sky-blue: #2a9d8f;
            --andean-night: #1d2d44;
            --andean-dark-deep: #0d1b2a;
            --text-muted: #8d99ae;
            --sidebar-width: 270px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f2f4f8ff;
            color: #61647dff;
            overflow-x: hidden;
        }

        /* Navbar estilo Andes Futurista */
        .futuristic-navbar {
            background: linear-gradient(135deg, var(--andean-dark-deep) 0%, var(--andean-night) 100%);
            border-bottom: 2px solid rgba(244, 162, 97, 0.3);
            backdrop-filter: blur(10px);
            z-index: 1030;
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: linear-gradient(45deg, #ffffff, var(--inti-gold-bright));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Decoración Geométrica de la Navbar (Borde Aguayo) */
        .navbar-aguayo-border {
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, 
                var(--terracota) 0%, 
                var(--inti-gold) 25%, 
                var(--sky-blue) 50%, 
                var(--inti-gold) 75%, 
                var(--terracota) 100%);
        }

        /* Sidebar con detalles culturales y glassmorphism */
        .sidebar-soft {
            width: var(--sidebar-width);
            min-height: calc(100vh - 60px);
            background: #fffefeff;
            border-right: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1.5rem 1rem;
            z-index: 1020;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--terracota);
            font-weight: 700;
            margin: 1.5rem 0 0.6rem 0.8rem;
            display: flex;
            align-items: center;
        }

        .section-title::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(200, 90, 23, 0.2), transparent);
            margin-left: 8px;
        }

        /* Nav links & Animaciones */
        .nav-link-main {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            color: #4a5568;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .nav-link-main:hover {
            background-color: rgba(244, 162, 97, 0.1);
            color: var(--terracota-dark);
            transform: translateX(4px);
        }

        .nav-link-main.active-section {
            background: linear-gradient(135deg, rgba(200, 90, 23, 0.1) 0%, rgba(244, 162, 97, 0.15) 100%);
            color: var(--terracota-dark);
            font-weight: 600;
            border-left: 4px solid var(--terracota);
        }

        /* Icon Box */
        .icon-box-white {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.8rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }

        .nav-link-main:hover .icon-box-white,
        .nav-link-main.active-section .icon-box-white {
            background: #ffffff;
            transform: scale(1.1) rotate(3deg);
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        /* Sub-items del Menú */
        .nav-sub-item {
            display: block;
            padding: 0.55rem 1rem 0.55rem 3.2rem;
            font-size: 0.88rem;
            color: #6c757d;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-sub-item::before {
            content: '';
            position: absolute;
            left: 2.2rem;
            top: 50%;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background-color: #cbd5e1;
            transform: translateY(-50%);
            transition: all 0.2s ease;
        }

        .nav-sub-item:hover {
            color: var(--terracota);
            background-color: rgba(0,0,0,0.02);
            padding-left: 3.4rem;
        }

        .nav-sub-item:hover::before,
        .nav-sub-item.active::before {
            background-color: var(--terracota);
            transform: translateY(-50%) scale(1.4);
        }

        .nav-sub-item.active {
            color: var(--terracota-dark);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 2rem;
            transition: all 0.3s ease;
            min-height: calc(100vh - 60px);
        }

        /* Avatar del Usuario con aura solar */
        .user-avatar-frame {
            border: 2px solid var(--inti-gold);
            padding: 2px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .user-avatar-frame:hover {
            transform: scale(1.08);
            box-shadow: 0 0 12px rgba(244, 162, 97, 0.5);
        }

        /* Animación para el botón de Registro */
        .btn-register-inti {
            background: linear-gradient(135deg, var(--terracota) 0%, var(--inti-gold) 100%);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 0.45rem 0.9rem;
            box-shadow: 0 4px 12px rgba(200, 90, 23, 0.25);
            transition: all 0.3s ease;
        }

        .btn-register-inti:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(200, 90, 23, 0.4);
            color: white;
        }

        /* Responsive Mobile Drawer */
        @media (max-width: 991.98px) {
            .sidebar-soft {
                position: fixed;
                top: 0;
                left: calc(-1 * var(--sidebar-width));
                height: 100vh;
                padding-top: 5rem;
            }

            .sidebar-soft.show {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(13, 27, 42, 0.5);
                backdrop-filter: blur(4px);
                z-index: 1015;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Layout Header / Navbar -->
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark futuristic-navbar shadow-sm">
            <div class="container-fluid px-4">
                <button class="btn btn-link text-white d-lg-none me-2 p-0 border-0" id="sidebarToggle">
                    <i class="bi bi-list fs-2 text-warning"></i>
                </button>
                
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                    <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 p-2 rounded-3 border border-warning border-opacity-25">
                        <i class="bi bi-cpu-fill text-warning fs-5"></i>
                    </div>
                    <span class="fs-4">Andes<span class="fw-light text-warning">Admin</span></span>
                </a>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <!-- BOTÓN REGISTRO -->
                    <button class="btn btn-register-inti d-flex align-items-center gap-2 text-sm fw-medium" data-bs-toggle="modal" data-bs-target="#registerModal">
                        <i class="bi bi-person-plus-fill"></i>
                        <span class="d-none d-sm-inline">Nuevo Usuario</span>
                    </button>

                    <!-- PERFIL DE USUARIO -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar-frame d-flex align-items-center justify-content-center">
                                <img src="{{ auth()->user()->avatar ?? asset('img/user-default.png') }}" class="rounded-circle" width="32" height="32" alt="Avatar">
                            </div>
                            <span class="d-none d-sm-inline font-medium text-light">{{ auth()->user()->name ?? 'Usuario' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow-lg border-0 animate__animated animate__fadeInUp animate__faster">
                            <li class="px-3 py-2 border-bottom border-secondary border-opacity-25">
                                <small class="text-muted d-block">Sesión iniciada como</small>
                                <span class="fw-semibold text-warning">{{ auth()->user()->email ?? 'admin@andes.pe' }}</span>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 mt-1">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="navbar-aguayo-border"></div>
    </header>

    <!-- Modal de Registro Incluido -->
    @include('auth.register')

    <!-- Estructura Principal -->
    <div class="d-flex">
        <!-- Sidebar Soft -->
        <aside class="sidebar-soft" id="sidebarMenu">
            
            <div class="section-title">
                <span>Incidencias</span>
            </div>
            <a href="{{ route('admin.incidencias.listado') }}" class="nav-link-main {{ Request::routeIs('admin.incidencias.listado') ? 'active-section' : '' }}">
                <div class="icon-box-white"><i class="bi bi-journal-text text-danger fs-5"></i></div>
                <span>Bitácora</span>
            </a>

            <div class="section-title">
                <span>Inventarios</span>
            </div>
            @php 
                $isPcOpen = Request::routeIs([
                    'responsables.*', 'marcas.*', 'hardwares.*', 'agencias.*',
                    'oficinas.*', 'modelos.*', 'tipoequipos.*', 'sistemaoperativos.*'
                ]);
            @endphp

            <a class="nav-link-main {{ $isPcOpen ? 'active-section' : '' }}" data-bs-toggle="collapse" href="#pcCollapse" role="button" aria-expanded="{{ $isPcOpen ? 'true' : 'false' }}">
                <div class="icon-box-white"><i class="bi bi-sliders text-primary fs-5"></i></div>
                <span class="flex-grow-1">Gestión PC</span>
                <i class="bi bi-chevron-down small opacity-50 transition-transform"></i>
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
            </div>

            <a class="nav-link-main {{ Request::routeIs('equipos.index') ? 'active-section' : '' }}" href="{{ route('equipos.index') }}">
                <div class="icon-box-white"><i class="bi bi-pc-display-horizontal text-success fs-5"></i></div>
                <span>Computadoras</span>
            </a>
            
            <a class="nav-link-main {{ Request::routeIs('admin.impresoras.index') ? 'active-section' : '' }}" href="{{ route('admin.impresoras.index') }}">
                <div class="icon-box-white"><i class="bi bi-printer text-success fs-5"></i></div>
                <span>Impresoras</span>
            </a>
            
            <a class="nav-link-main {{ Request::routeIs('admin.termicas.*') ? 'active-section' : '' }}" href="{{ route('admin.termicas.index') }}">
                <div class="icon-box-white"><i class="bi bi-receipt text-warning fs-5"></i></div>
                <span>Impresoras Térmicas</span>
            </a>
            
            <a class="nav-link-main {{ Request::routeIs('admin.contabilletes.*') ? 'active-section' : '' }}" href="{{ route('admin.contabilletes.index') }}">
                <div class="icon-box-white"><i class="bi bi-cash-coin text-info fs-5"></i></div>
                <span>Contadoras Billetes</span>
            </a>

            <div class="section-title">
                <span>Conectividad</span>
            </div>
            <a href="{{ route('admin.servicios-internet.index') }}" class="nav-link-main {{ Request::routeIs('admin.servicios-internet.index') ? 'active-section' : '' }}">
                <div class="icon-box-white"><i class="bi bi-diagram-3 text-primary fs-5"></i></div>
                <span>Internet</span>
            </a>
            
        </aside>

        <!-- Main Content View Container -->
        <main class="main-content">
            <div class="animate__animated animate__fadeIn">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')

    <!-- Script de Interactividad (Toggle de Menú Móvil) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarMenu = document.getElementById('sidebarMenu');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebarMenu.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>

    <!-- SweetAlert Notificaciones -->
    @if(session('success') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "{{ session('success') ? '¡Operación Exitosa!' : '¡Atención!' }}",
                text: "{{ session('success') ?? session('error') }}",
                icon: "{{ session('success') ? 'success' : 'error' }}",
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary px-4 py-2 rounded-3'
                },
                buttonsStyling: false
            });
        });
    </script>
    @endif
</body>
</html>
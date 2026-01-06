<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Andes PC') }}</title>

    <!-- Preconnect to Bunny Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            color: #1b1b18;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero {
            text-align: center;
            max-width: 700px;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 2.8rem;
            font-weight: 600;
            color: #0d6efd;
        }

        .hero p {
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 2rem;
        }

        .btn-custom {
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.375rem;
        }

        .logo {
            font-size: 3rem;
            color: #0d6efd;
        }
    </style>
</head>

<body class="antialiased">
    <div class="container">
        <div class="hero">
            <!-- Logo o ícono -->
            <div class="mb-4">
                <i class="bi bi-pc-display logo"></i>
            </div>

            <!-- Título -->
            <h1 class="text-instrument">Andes PC Admin</h1>

            <!-- Descripción -->
            <p class="text-muted">
                Sistema de gestión de equipos, hardware y responsables.
                Accede al panel administrativo para gestionar dispositivos, oficinas y más.
            </p>
           
            <!-- Botones de acceso -->
            <div class="d-grid gap-3 d-sm-flex justify-content-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg btn-custom d-flex align-items-center justify-content-center">
                            <i class="bi bi-speedometer2 me-2"></i> Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg btn-custom d-flex align-items-center justify-content-center">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                        </a>
                       

                    @endauth
                @endif
            </div>
            
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
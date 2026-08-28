<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Andes PC') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: #06060e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ===== FONDO PRINCIPAL ===== */
        .main-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: radial-gradient(ellipse at center, #0a0a1a 0%, #06060e 100%);
        }

        /* ===== SERVIDORES (Racks) ===== */
        .server-rack {
            position: fixed;
            z-index: 0;
            opacity: 0.15;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 8px;
            background: rgba(0, 20, 40, 0.3);
            border: 1px solid rgba(0, 200, 255, 0.05);
            border-radius: 6px;
            backdrop-filter: blur(2px);
        }
        .server-unit {
            width: 60px;
            height: 12px;
            background: rgba(0, 200, 255, 0.05);
            border-radius: 3px;
            border-left: 2px solid rgba(0, 200, 255, 0.1);
            position: relative;
            transition: all 0.3s ease;
            animation: serverGlow 3s ease-in-out infinite;
        }
        .server-unit::after {
            content: '';
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #00ff88;
            animation: ledBlink 1.5s ease-in-out infinite alternate;
        }
        .server-unit:nth-child(2)::after { background: #00ccff; animation-delay: 0.3s; }
        .server-unit:nth-child(3)::after { background: #ff6b00; animation-delay: 0.6s; }
        .server-unit:nth-child(4)::after { background: #00ff88; animation-delay: 0.9s; }
        .server-unit:nth-child(5)::after { background: #cc00ff; animation-delay: 0.2s; }
        .server-unit:nth-child(6)::after { background: #00ccff; animation-delay: 0.5s; }
        .server-unit:nth-child(7)::after { background: #ff6b00; animation-delay: 0.8s; }
        .server-unit:nth-child(8)::after { background: #00ff88; animation-delay: 0.1s; }

        @keyframes serverGlow {
            0%, 100% { background: rgba(0, 200, 255, 0.03); }
            50% { background: rgba(0, 200, 255, 0.08); }
        }
        @keyframes ledBlink {
            0% { opacity: 0.2; box-shadow: 0 0 2px currentColor; }
            100% { opacity: 1; box-shadow: 0 0 15px currentColor, 0 0 30px currentColor; }
        }

        /* Rack 1 - Izquierda */
        .rack1 {
            left: 3%;
            top: 10%;
            transform: rotate(-2deg);
        }
        .rack1 .server-unit { width: 50px; height: 10px; }
        .rack1 .server-unit::after { right: 3px; width: 3px; height: 3px; }

        /* Rack 2 - Derecha */
        .rack2 {
            right: 3%;
            bottom: 10%;
            transform: rotate(2deg);
        }
        .rack2 .server-unit { width: 55px; height: 11px; }
        .rack2 .server-unit::after { right: 3px; width: 3px; height: 3px; }

        /* Rack 3 - Abajo izquierda */
        .rack3 {
            left: 8%;
            bottom: 15%;
            transform: rotate(-1deg);
        }
        .rack3 .server-unit { width: 45px; height: 9px; }
        .rack3 .server-unit::after { right: 2px; width: 3px; height: 3px; }

        /* Rack 4 - Arriba derecha */
        .rack4 {
            right: 8%;
            top: 15%;
            transform: rotate(1deg);
        }
        .rack4 .server-unit { width: 48px; height: 10px; }
        .rack4 .server-unit::after { right: 2px; width: 3px; height: 3px; }

        /* Rack 5 - Centro inferior */
        .rack5 {
            left: 45%;
            bottom: 5%;
            transform: rotate(0deg);
        }
        .rack5 .server-unit { width: 40px; height: 8px; }
        .rack5 .server-unit::after { right: 2px; width: 2px; height: 2px; }

        /* ===== CABLES DE FIBRA ÓPTICA ===== */
        .fiber-cable {
            position: fixed;
            z-index: 0;
            opacity: 0.25;
            border-radius: 2px;
        }
        .fiber-cable .light-particle {
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            box-shadow: 0 0 20px currentColor, 0 0 60px currentColor;
            animation: fiberTravel linear infinite;
        }
        @keyframes fiberTravel {
            0% { left: 0%; opacity: 0; }
            5% { opacity: 1; }
            95% { opacity: 1; }
            100% { left: 100%; opacity: 0; }
        }

        /* Cable 1 - Horizontal superior */
        .cable1 {
            top: 8%;
            left: 10%;
            width: 80%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00ccff 20%, #0066ff 50%, #00ccff 80%, transparent);
            background-size: 200% 100%;
            animation: cableFlow 4s linear infinite;
        }
        .cable1 .light-particle {
            top: -2px;
            color: #00ccff;
            animation-duration: 3s;
            animation-delay: 0s;
        }

        /* Cable 2 - Diagonal */
        .cable2 {
            top: 20%;
            left: 5%;
            width: 60%;
            height: 2px;
            transform: rotate(15deg);
            background: linear-gradient(90deg, transparent, #00ff88 20%, #00ccff 50%, #00ff88 80%, transparent);
            background-size: 200% 100%;
            animation: cableFlow 5s linear infinite reverse;
        }
        .cable2 .light-particle {
            top: -2px;
            color: #00ff88;
            animation-duration: 4s;
            animation-delay: 1s;
        }

        /* Cable 3 - Horizontal inferior */
        .cable3 {
            bottom: 12%;
            left: 5%;
            width: 90%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #cc00ff 20%, #00ccff 50%, #cc00ff 80%, transparent);
            background-size: 200% 100%;
            animation: cableFlow 6s linear infinite;
        }
        .cable3 .light-particle {
            top: -2px;
            color: #cc00ff;
            animation-duration: 3.5s;
            animation-delay: 2s;
        }

        /* Cable 4 - Diagonal inversa */
        .cable4 {
            bottom: 25%;
            right: 5%;
            width: 50%;
            height: 2px;
            transform: rotate(-20deg);
            background: linear-gradient(90deg, transparent, #00ff88 20%, #ff6b00 50%, #00ff88 80%, transparent);
            background-size: 200% 100%;
            animation: cableFlow 4.5s linear infinite;
        }
        .cable4 .light-particle {
            top: -2px;
            color: #ff6b00;
            animation-duration: 3.8s;
            animation-delay: 0.5s;
        }

        /* Cable 5 - Vertical */
        .cable5 {
            left: 50%;
            top: 5%;
            width: 2px;
            height: 40%;
            background: linear-gradient(180deg, transparent, #00ccff 20%, #0066ff 50%, #00ccff 80%, transparent);
            background-size: 100% 200%;
            animation: cableFlowVertical 5s linear infinite;
        }
        .cable5 .light-particle {
            top: 0;
            left: -2px;
            color: #00ccff;
            animation-name: fiberTravelVertical;
            animation-duration: 4s;
            animation-delay: 1.5s;
        }
        @keyframes cableFlow {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes cableFlowVertical {
            0% { background-position: 0 -200%; }
            100% { background-position: 0 200%; }
        }
        @keyframes fiberTravelVertical {
            0% { top: 0%; opacity: 0; }
            5% { opacity: 1; }
            95% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        /* ===== SEÑALES WiFi ===== */
        .wifi-signal {
            position: fixed;
            z-index: 0;
            opacity: 0.08;
        }
        .wifi-signal .wave {
            position: absolute;
            border: 2px solid;
            border-radius: 50%;
            animation: wifiWave 3s ease-out infinite;
        }
        @keyframes wifiWave {
            0% { width: 20px; height: 20px; opacity: 0.6; }
            100% { width: 250px; height: 250px; opacity: 0; }
        }

        /* WiFi 1 - Izquierda */
        .wifi1 {
            left: 15%;
            top: 35%;
        }
        .wifi1 .wave {
            border-color: #00ccff;
        }
        .wifi1 .wave:nth-child(1) { animation-delay: 0s; }
        .wifi1 .wave:nth-child(2) { animation-delay: 1s; }
        .wifi1 .wave:nth-child(3) { animation-delay: 2s; }
        .wifi1 .wave:nth-child(4) { animation-delay: 0.5s; }
        .wifi1 .wave:nth-child(5) { animation-delay: 1.5s; }
        .wifi1 i {
            font-size: 2.5rem;
            color: #00ccff;
            position: relative;
            z-index: 1;
            animation: wifiPulse 2s ease-in-out infinite;
        }

        /* WiFi 2 - Derecha */
        .wifi2 {
            right: 15%;
            bottom: 35%;
        }
        .wifi2 .wave {
            border-color: #00ff88;
        }
        .wifi2 .wave:nth-child(1) { animation-delay: 0.5s; }
        .wifi2 .wave:nth-child(2) { animation-delay: 1.5s; }
        .wifi2 .wave:nth-child(3) { animation-delay: 2.5s; }
        .wifi2 .wave:nth-child(4) { animation-delay: 1s; }
        .wifi2 .wave:nth-child(5) { animation-delay: 2s; }
        .wifi2 i {
            font-size: 2.5rem;
            color: #00ff88;
            position: relative;
            z-index: 1;
            animation: wifiPulse 2.5s ease-in-out infinite 0.5s;
        }

        /* WiFi 3 - Centro */
        .wifi3 {
            left: 45%;
            top: 55%;
        }
        .wifi3 .wave {
            border-color: #cc00ff;
        }
        .wifi3 .wave:nth-child(1) { animation-delay: 1s; }
        .wifi3 .wave:nth-child(2) { animation-delay: 2s; }
        .wifi3 .wave:nth-child(3) { animation-delay: 0s; }
        .wifi3 .wave:nth-child(4) { animation-delay: 1.5s; }
        .wifi3 .wave:nth-child(5) { animation-delay: 2.5s; }
        .wifi3 i {
            font-size: 2.5rem;
            color: #cc00ff;
            position: relative;
            z-index: 1;
            animation: wifiPulse 1.8s ease-in-out infinite 1s;
        }

        @keyframes wifiPulse {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.2); opacity: 1; }
        }

        /* ===== PARTÍCULAS DE LUZ FLOTANTES ===== */
        .float-particle {
            position: fixed;
            border-radius: 50%;
            z-index: 0;
            animation: floatParticle linear infinite;
            opacity: 0;
        }
        @keyframes floatParticle {
            0% { transform: translateY(100vh) translateX(0) scale(0); opacity: 0; }
            10% { opacity: 0.3; }
            50% { opacity: 0.6; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100vh) translateX(50px) scale(1); opacity: 0; }
        }

        /* ===== HERO ===== */
        .hero {
            text-align: center;
            max-width: 750px;
            padding: 3rem 2.5rem;
            background: rgba(6, 6, 14, 0.88);
            backdrop-filter: blur(25px);
            border-radius: 30px;
            border: 1px solid rgba(0, 200, 255, 0.08);
            box-shadow: 
                0 0 80px rgba(0, 200, 255, 0.03),
                inset 0 0 80px rgba(0, 200, 255, 0.02);
            position: relative;
            z-index: 1;
            animation: heroIn 1s ease-out;
        }
        @keyframes heroIn {
            0% { transform: scale(0.9) translateY(30px); opacity: 0; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        .hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #00ccff, #0066ff, #00ff88, #cc00ff, #00ccff);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 5s ease-in-out infinite;
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .logo {
            font-size: 4rem;
            background: linear-gradient(135deg, #00ccff, #0066ff, #00ff88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            animation: logoPulse 2s ease-in-out infinite;
        }
        @keyframes logoPulse {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 20px rgba(0, 204, 255, 0.1)); }
            50% { transform: scale(1.05); filter: drop-shadow(0 0 40px rgba(0, 204, 255, 0.3)); }
        }
        .tech-badges {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin: 1.5rem 0;
        }
        .tech-badge {
            background: rgba(0, 204, 255, 0.04);
            border: 1px solid rgba(0, 204, 255, 0.08);
            border-radius: 20px;
            padding: 0.4rem 1.2rem;
            font-size: 0.75rem;
            color: #88ccff;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
        }
        .tech-badge:hover {
            background: rgba(0, 204, 255, 0.08);
            border-color: rgba(0, 204, 255, 0.2);
            transform: translateY(-2px);
        }
        .tech-badge i { margin-right: 6px; }
        .hero p {
            color: #667799;
            font-size: 1.1rem;
            margin: 1rem 0 2rem;
            line-height: 1.8;
        }
        .hero p .highlight {
            color: #00ccff;
            font-weight: 500;
        }
        .hero p .highlight2 {
            color: #00ff88;
            font-weight: 500;
        }
        .btn-custom {
            border-radius: 50px;
            padding: 0.8rem 2.2rem;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        .btn-fiber {
            background: linear-gradient(135deg, #00ccff, #0066ff);
            border: none;
            color: #06060e;
        }
        .btn-fiber:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 50px rgba(0, 204, 255, 0.25);
            color: #06060e;
        }
        .btn-fiber-outline {
            background: transparent;
            border: 2px solid rgba(0, 204, 255, 0.3);
            color: #88ccff;
        }
        .btn-fiber-outline:hover {
            background: rgba(0, 204, 255, 0.05);
            border-color: #00ccff;
            transform: translateY(-3px);
            color: #00ccff;
        }
        .btn-fiber::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
            animation: btnShine 4s linear infinite;
        }
        @keyframes btnShine {
            0% { transform: translate(-30%, -30%) rotate(0deg); }
            100% { transform: translate(30%, 30%) rotate(360deg); }
        }
        .status-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #445566;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #00ff88;
            animation: ledBlink 1.5s ease-in-out infinite;
        }
        .status-dot:nth-child(2) {
            background: #00ccff;
            animation-delay: 0.3s;
        }
        .status-dot:nth-child(3) {
            background: #cc00ff;
            animation-delay: 0.6s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .rack1, .rack2, .rack3, .rack4, .rack5 { display: none; }
            .cable1, .cable2, .cable3, .cable4, .cable5 { opacity: 0.1; }
            .wifi1, .wifi2, .wifi3 { opacity: 0.05; }
            .hero { padding: 2rem 1.5rem; margin: 1rem; }
            .hero h1 { font-size: 2rem; }
            .tech-badges { gap: 6px; }
            .tech-badge { font-size: 0.65rem; padding: 0.3rem 0.8rem; }
        }
    </style>
</head>
<body>

    <!-- ===== FONDO ===== -->
    <div class="main-bg"></div>

    <!-- ===== SERVIDORES (Racks) ===== -->
    <div class="server-rack rack1">
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
    </div>
    <div class="server-rack rack2">
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
    </div>
    <div class="server-rack rack3">
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
    </div>
    <div class="server-rack rack4">
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
    </div>
    <div class="server-rack rack5">
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
        <div class="server-unit"></div>
    </div>

    <!-- ===== CABLES DE FIBRA ÓPTICA ===== -->
    <div class="fiber-cable cable1">
        <div class="light-particle"></div>
    </div>
    <div class="fiber-cable cable2">
        <div class="light-particle"></div>
    </div>
    <div class="fiber-cable cable3">
        <div class="light-particle"></div>
    </div>
    <div class="fiber-cable cable4">
        <div class="light-particle"></div>
    </div>
    <div class="fiber-cable cable5">
        <div class="light-particle"></div>
    </div>

    <!-- ===== SEÑALES WiFi ===== -->
    <div class="wifi-signal wifi1">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <i class="bi bi-wifi"></i>
    </div>
    <div class="wifi-signal wifi2">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <i class="bi bi-wifi"></i>
    </div>
    <div class="wifi-signal wifi3">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <i class="bi bi-wifi"></i>
    </div>

    <!-- ===== PARTÍCULAS FLOTANTES ===== -->
    <div class="float-particle" style="width: 3px; height: 3px; background: #00ccff; left: 10%; animation-duration: 12s; animation-delay: 0s;"></div>
    <div class="float-particle" style="width: 4px; height: 4px; background: #00ff88; left: 25%; animation-duration: 15s; animation-delay: 2s;"></div>
    <div class="float-particle" style="width: 2px; height: 2px; background: #cc00ff; left: 40%; animation-duration: 10s; animation-delay: 4s;"></div>
    <div class="float-particle" style="width: 5px; height: 5px; background: #ff6b00; left: 55%; animation-duration: 18s; animation-delay: 1s;"></div>
    <div class="float-particle" style="width: 3px; height: 3px; background: #00ccff; left: 70%; animation-duration: 14s; animation-delay: 3s;"></div>
    <div class="float-particle" style="width: 4px; height: 4px; background: #00ff88; left: 85%; animation-duration: 16s; animation-delay: 5s;"></div>
    <div class="float-particle" style="width: 2px; height: 2px; background: #cc00ff; left: 15%; animation-duration: 11s; animation-delay: 2.5s;"></div>
    <div class="float-particle" style="width: 3px; height: 3px; background: #ff6b00; left: 60%; animation-duration: 13s; animation-delay: 3.5s;"></div>
    <div class="float-particle" style="width: 4px; height: 4px; background: #00ccff; left: 35%; animation-duration: 17s; animation-delay: 1.5s;"></div>
    <div class="float-particle" style="width: 3px; height: 3px; background: #00ff88; left: 75%; animation-duration: 19s; animation-delay: 4.5s;"></div>

    <!-- ===== HERO ===== -->
    <div class="container">
        <div class="hero">
            <div class="mb-4">
                <i class="bi bi-fiber logo"></i>
            </div>
            <h1>Infraestructura TI Andes</h1>
            <div class="tech-badges">
                <span class="tech-badge"><i class="bi bi-speedometer2"></i> 100Gbps</span>
                <span class="tech-badge"><i class="bi bi-hdd-stack"></i> 50+ Servers</span>
                <span class="tech-badge"><i class="bi bi-ethernet"></i> Fiber Optic</span>
                <span class="tech-badge"><i class="bi bi-wifi"></i> WiFi 6E</span>
                <span class="tech-badge"><i class="bi bi-shield-check"></i> Encrypted</span>
                <span class="tech-badge"><i class="bi bi-cloud"></i> Cloud Ready</span>
            </div>
            <p>
                <i class="bi bi-arrow-right-circle text-info"></i>
                Infraestructura TI <span class="highlight">alto rendimiento</span>
                <i class="bi bi-arrow-left-circle text-info"></i>
                <br>
                <span style="font-size: 0.95rem; color: #556677;">
                    <i class="bi bi-dot"></i>
                    <span class="highlight2">Fibra óptica</span> · 
                    <span class="highlight">Data Center</span> · 
                    <span class="highlight2">Conectividad andina</span>
                    <i class="bi bi-dot"></i>
                </span>
            </p>
            <div class="d-grid gap-3 d-sm-flex justify-content-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-fiber btn-custom">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                @else
                    <button type="button" class="btn btn-fiber btn-custom" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Acceder al sistema
                    </button>
                @endauth
            </div>
            <div class="status-indicator">
                <span class="status-dot"></span>
                <span class="status-dot"></span>
                <span class="status-dot"></span>
                <span style="margin-left: 8px;">Sistema operativo · Todos los servicios activos</span>
            </div>
        </div>
    </div>

    <!-- ===== SCRIPTS ===== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('auth.login-modal')
    @if ($errors->any())
    <script>
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    </script>
    @endif
</body>
</html>
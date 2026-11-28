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
    /* Reemplaza todo el bloque <style> por este */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    font-family: 'Instrument Sans', sans-serif;
    background: #04040a;
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
    inset: 0;
    z-index: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(0, 240, 255, 0.12) 0%, transparent 40%),
        radial-gradient(circle at 80% 80%, rgba(255, 0, 128, 0.12) 0%, transparent 40%),
        radial-gradient(ellipse at center, #0c0d24 0%, #030308 100%);
}

/* ===== SERVIDORES (Racks) MÁS VISIBLES ===== */
.server-rack {
    position: fixed;
    z-index: 0;
    opacity: 0.55; /* Subido de 0.15 a 0.55 */
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 10px;
    background: rgba(10, 25, 50, 0.6);
    border: 1px solid rgba(0, 240, 255, 0.35);
    box-shadow: 0 0 20px rgba(0, 240, 255, 0.15);
    border-radius: 8px;
    backdrop-filter: blur(4px);
}
.server-unit {
    width: 65px;
    height: 12px;
    background: rgba(0, 240, 255, 0.12);
    border-radius: 3px;
    border-left: 3px solid #00f0ff;
    position: relative;
    animation: serverGlow 2.5s ease-in-out infinite;
}
.server-unit::after {
    content: '';
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #00ff66;
    box-shadow: 0 0 10px #00ff66;
    animation: ledBlink 1.2s ease-in-out infinite alternate;
}
.server-unit:nth-child(2)::after { background: #00f0ff; animation-delay: 0.3s; }
.server-unit:nth-child(3)::after { background: #ff007f; animation-delay: 0.6s; }
.server-unit:nth-child(4)::after { background: #00ff66; animation-delay: 0.9s; }
.server-unit:nth-child(5)::after { background: #b829ff; animation-delay: 0.2s; }
.server-unit:nth-child(6)::after { background: #00f0ff; animation-delay: 0.5s; }
.server-unit:nth-child(7)::after { background: #ff9900; animation-delay: 0.8s; }

@keyframes serverGlow {
    0%, 100% { background: rgba(0, 240, 255, 0.08); }
    50% { background: rgba(0, 240, 255, 0.22); }
}
@keyframes ledBlink {
    0% { opacity: 0.3; transform: translateY(-50%) scale(0.8); }
    100% { opacity: 1; transform: translateY(-50%) scale(1.2); box-shadow: 0 0 15px currentColor; }
}

.rack1 { left: 3%; top: 10%; transform: rotate(-2deg); }
.rack2 { right: 3%; bottom: 10%; transform: rotate(2deg); }
.rack3 { left: 8%; bottom: 12%; transform: rotate(-1deg); }
.rack4 { right: 8%; top: 12%; transform: rotate(1deg); }
.rack5 { left: 46%; bottom: 4%; }

/* ===== CABLES DE FIBRA ÓPTICA BRILLANTES ===== */
.fiber-cable {
    position: fixed;
    z-index: 0;
    opacity: 0.65; /* Mucho más llamativo */
    border-radius: 4px;
    box-shadow: 0 0 12px rgba(0, 240, 255, 0.3);
}
.fiber-cable .light-particle {
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 0 15px currentColor, 0 0 30px currentColor, 0 0 45px currentColor;
    animation: fiberTravel linear infinite;
}
@keyframes fiberTravel {
    0% { left: 0%; opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { left: 100%; opacity: 0; }
}

.cable1 {
    top: 8%; left: 10%; width: 80%; height: 3px;
    background: linear-gradient(90deg, transparent, #00f0ff, #ff007f, #00f0ff, transparent);
    background-size: 200% 100%;
    animation: cableFlow 3s linear infinite;
}
.cable1 .light-particle { top: -2.5px; color: #00f0ff; animation-duration: 2.5s; }

.cable2 {
    top: 22%; left: 5%; width: 60%; height: 3px; transform: rotate(15deg);
    background: linear-gradient(90deg, transparent, #00ff66, #00f0ff, #00ff66, transparent);
    background-size: 200% 100%;
    animation: cableFlow 4s linear infinite reverse;
}
.cable2 .light-particle { top: -2.5px; color: #00ff66; animation-duration: 3.2s; }

.cable3 {
    bottom: 14%; left: 5%; width: 90%; height: 3px;
    background: linear-gradient(90deg, transparent, #b829ff, #00f0ff, #ff007f, transparent);
    background-size: 200% 100%;
    animation: cableFlow 5s linear infinite;
}
.cable3 .light-particle { top: -2.5px; color: #ff007f; animation-duration: 3s; }

.cable4 {
    bottom: 25%; right: 5%; width: 50%; height: 3px; transform: rotate(-20deg);
    background: linear-gradient(90deg, transparent, #ff9900, #ff007f, #ff9900, transparent);
    background-size: 200% 100%;
    animation: cableFlow 3.5s linear infinite;
}
.cable4 .light-particle { top: -2.5px; color: #ff9900; animation-duration: 2.8s; }

.cable5 {
    left: 50%; top: 5%; width: 3px; height: 40%;
    background: linear-gradient(180deg, transparent, #00f0ff, #b829ff, transparent);
    background-size: 100% 200%;
    animation: cableFlowVertical 4s linear infinite;
}
.cable5 .light-particle {
    left: -2.5px; color: #00f0ff;
    animation-name: fiberTravelVertical; animation-duration: 3s;
}
@keyframes cableFlow { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
@keyframes cableFlowVertical { 0% { background-position: 0 -200%; } 100% { background-position: 0 200%; } }
@keyframes fiberTravelVertical { 0% { top: 0%; opacity: 0; } 10%, 90% { opacity: 1; } 100% { top: 100%; opacity: 0; } }

/* ===== SEÑALES WiFi ===== */
.wifi-signal { position: fixed; z-index: 0; opacity: 0.35; }
.wifi-signal .wave {
    position: absolute; border: 2px solid; border-radius: 50%;
    animation: wifiWave 3s ease-out infinite;
}
@keyframes wifiWave {
    0% { width: 20px; height: 20px; opacity: 0.9; }
    100% { width: 260px; height: 260px; opacity: 0; }
}
.wifi1 { left: 15%; top: 35%; }
.wifi1 .wave { border-color: #00f0ff; }
.wifi1 i { font-size: 2.5rem; color: #00f0ff; filter: drop-shadow(0 0 12px #00f0ff); }

.wifi2 { right: 15%; bottom: 35%; }
.wifi2 .wave { border-color: #00ff66; }
.wifi2 i { font-size: 2.5rem; color: #00ff66; filter: drop-shadow(0 0 12px #00ff66); }

.wifi3 { left: 45%; top: 55%; }
.wifi3 .wave { border-color: #ff007f; }
.wifi3 i { font-size: 2.5rem; color: #ff007f; filter: drop-shadow(0 0 12px #ff007f); }

.wifi-signal .wave:nth-child(2) { animation-delay: 0.8s; }
.wifi-signal .wave:nth-child(3) { animation-delay: 1.6s; }

/* ===== PARTÍCULAS FLOTANTES ===== */
.float-particle {
    position: fixed; border-radius: 50%; z-index: 0;
    box-shadow: 0 0 10px currentColor;
    animation: floatParticle linear infinite;
}
@keyframes floatParticle {
    0% { transform: translateY(100vh) scale(0); opacity: 0; }
    20%, 80% { opacity: 0.9; }
    100% { transform: translateY(-100vh) translateX(50px) scale(1.2); opacity: 0; }
}

/* ===== HERO CON BORDE NEÓN ===== */
.hero {
    text-align: center;
    max-width: 750px;
    padding: 3.2rem 2.5rem;
    background: rgba(10, 12, 28, 0.82);
    backdrop-filter: blur(20px);
    border-radius: 30px;
    border: 1px solid rgba(0, 240, 255, 0.35);
    box-shadow: 
        0 0 50px rgba(0, 240, 255, 0.18),
        0 0 100px rgba(184, 41, 255, 0.12),
        inset 0 0 30px rgba(0, 240, 255, 0.08);
    position: relative;
    z-index: 1;
    animation: heroIn 0.8s ease-out;
}
@keyframes heroIn {
    0% { transform: scale(0.92) translateY(25px); opacity: 0; }
    100% { transform: scale(1) translateY(0); opacity: 1; }
}
.hero h1 {
    font-size: 2.9rem;
    font-weight: 700;
    background: linear-gradient(135deg, #00f0ff, #00ff66, #ff007f, #b829ff, #00f0ff);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradientShift 4s ease-in-out infinite;
    filter: drop-shadow(0 2px 15px rgba(0, 240, 255, 0.25));
}
@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
.logo {
    font-size: 4.2rem;
    background: linear-gradient(135deg, #00f0ff, #00ff66);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
    filter: drop-shadow(0 0 25px rgba(0, 240, 255, 0.6));
    animation: logoPulse 2s ease-in-out infinite;
}
@keyframes logoPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.08); }
}
.tech-badges {
    display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin: 1.5rem 0;
}
.tech-badge {
    background: rgba(0, 240, 255, 0.1);
    border: 1px solid rgba(0, 240, 255, 0.35);
    border-radius: 20px;
    padding: 0.45rem 1.2rem;
    font-size: 0.8rem;
    color: #e0f7ff;
    font-weight: 600;
    box-shadow: 0 0 12px rgba(0, 240, 255, 0.12);
    transition: all 0.3s ease;
}
.tech-badge i { color: #00f0ff; margin-right: 6px; }
.tech-badge:hover {
    background: rgba(0, 240, 255, 0.22);
    border-color: #00f0ff;
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 240, 255, 0.35);
}
.hero p {
    color: #b8c7e0;
    font-size: 1.15rem;
    margin: 1rem 0 2rem;
    line-height: 1.8;
}
.hero p .highlight { color: #00f0ff; font-weight: 700; text-shadow: 0 0 10px rgba(0, 240, 255, 0.4); }
.hero p .highlight2 { color: #00ff66; font-weight: 700; text-shadow: 0 0 10px rgba(0, 255, 102, 0.4); }

.btn-custom {
    border-radius: 50px;
    padding: 0.9rem 2.5rem;
    font-weight: 700;
    font-size: 1.05rem;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.btn-fiber {
    background: linear-gradient(135deg, #00f0ff 0%, #0077ff 50%, #b829ff 100%);
    background-size: 200% auto;
    border: none;
    color: #ffffff;
    box-shadow: 0 0 25px rgba(0, 240, 255, 0.45);
}
.btn-fiber:hover {
    background-position: right center;
    transform: translateY(-3px) scale(1.04);
    box-shadow: 0 10px 40px rgba(184, 41, 255, 0.6);
    color: #ffffff;
}
.status-indicator {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin-top: 1.8rem; font-size: 0.85rem; color: #94a3b8; font-weight: 500;
}
.status-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #00ff66;
    box-shadow: 0 0 10px #00ff66;
    animation: ledBlink 1.2s ease-in-out infinite;
}
.status-dot:nth-child(2) { background: #00f0ff; box-shadow: 0 0 10px #00f0ff; animation-delay: 0.3s; }
.status-dot:nth-child(3) { background: #ff007f; box-shadow: 0 0 10px #ff007f; animation-delay: 0.6s; }
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
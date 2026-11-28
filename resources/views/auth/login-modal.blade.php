<!-- Modal Login — Paleta Clara Luminosa "Andean Daylight" -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 overflow-hidden login-modal-content">

            <!-- Fondo decorativo animado claro -->
            <div class="login-bg-decoration">
                <span class="orb orb-1"></span>
                <span class="orb orb-2"></span>
                <span class="orb orb-3"></span>
                <span class="grid-overlay"></span>
            </div>

            <!-- Header -->
            <div class="modal-header border-0 pb-0 position-relative login-header">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-3 pt-2 pb-3 ps-2" id="loginModalLabel">
                    <div class="icon-badge d-flex align-items-center justify-content-center rounded-3">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                    </div>
                    <div>
                        <span class="d-block login-title-gradient">Iniciar Sesión</span>
                        <small class="login-subtitle">Acceso al sistema cooperativo</small>
                    </div>
                </h5>
                <!-- Se quitó btn-close-white para que contraste en fondo claro -->
                <button type="button" class="btn-close me-2 mt-2 position-relative" data-bs-dismiss="modal" aria-label="Cerrar" style="z-index: 2;"></button>
            </div>

            <div class="modal-body px-4 py-4 position-relative">
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating mb-3 input-glow-group">
                        <input
                            type="text"
                            id="email"
                            name="email"
                            class="form-control rounded-3 login-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="usuario"
                            required
                            autofocus
                        >
                        <label for="email">
                            <i class="bi bi-person me-1 input-icon"></i> Usuario (@cooperativalosandes.com)
                        </label>
                        <span class="input-highlight"></span>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3 input-glow-group position-relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control rounded-3 login-input pe-5 @error('password') is-invalid @enderror"
                            placeholder="Contraseña"
                            required
                        >
                        <label for="password">
                            <i class="bi bi-lock me-1 input-icon"></i> Contraseña
                        </label>
                        <span class="input-highlight"></span>
                        <button type="button" class="btn-toggle-password" id="togglePassword" aria-label="Mostrar/ocultar contraseña" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                        <div class="form-check login-check">
                            <input type="checkbox" class="form-check-input shadow-sm" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Recordarme</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-decoration-none login-link fw-semibold">
                                ¿Olvidó su contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn w-100 rounded-3 py-2 fw-bold shadow-sm mb-2 login-submit position-relative overflow-hidden">
                        <span class="position-relative d-inline-flex align-items-center justify-content-center gap-2" style="z-index:2;">
                            <i class="bi bi-shield-lock"></i> Ingresar al Sistema
                        </span>
                        <span class="btn-shine"></span>
                    </button>

                    <p class="text-center small mb-0 mt-3 login-hint">
                        <i class="bi bi-info-circle-fill me-1"></i> Solo personal autorizado
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
/* =====================================================
   PALETA CLARA — "Andean Daylight"
   -----------------------------------------------------
   Nieve pura : #FFFFFF  (superficie principal)
   Hielo      : #F1F6FC  (degradado suave)
   Cian vivo  : #00A8E8  (acento primario)
   Índigo     : #4F46E5  (acento secundario)
   Esmeralda  : #00C875  (acento fibra/energía)
   Texto      : #0F172A  /  Muted: #475569
   ===================================================== */
:root {
    --day-snow:      #FFFFFF;
    --day-ice:       #EEF5FC;
    --day-cyan:      #00A8E8;
    --day-indigo:    #4F46E5;
    --day-emerald:   #00C875;
    --day-text:      #0F172A;
    --day-muted:     #475569;
    --day-border:    rgba(0, 168, 232, 0.35);
    --day-glow:      rgba(0, 212, 255, 0.45);
}

/* ====== CONTENEDOR CLARO CON HALO NEÓN ====== */
.login-modal-content {
    background: linear-gradient(155deg, var(--day-snow) 0%, var(--day-ice) 100%);
    position: relative;
    isolation: isolate;
    color: var(--day-text);
    border: 1.5px solid rgba(255, 255, 255, 0.9);
    /* Resplandor exterior que destaca sobre el fondo oscuro */
    box-shadow:
        0 25px 70px -10px rgba(0, 0, 0, 0.75),
        0 0 45px rgba(0, 204, 255, 0.35),
        0 0 80px rgba(79, 70, 229, 0.25);
}

/* ====== FONDO DECORATIVO ====== */
.login-bg-decoration {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
    border-radius: inherit;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(55px);
    animation: floatOrb 12s ease-in-out infinite;
}
.orb-1 {
    width: 210px; height: 210px;
    background: #38BDF8;
    top: -80px; right: -50px;
    opacity: 0.28;
}
.orb-2 {
    width: 230px; height: 230px;
    background: #818CF8;
    bottom: -90px; left: -60px;
    opacity: 0.25;
    animation-delay: -5s;
}
.orb-3 {
    width: 140px; height: 140px;
    background: #34D399;
    top: 45%; right: 5%;
    opacity: 0.22;
    animation-delay: -9s;
}

.grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(0, 168, 232, 0.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 168, 232, 0.08) 1px, transparent 1px);
    background-size: 30px 30px;
    mask-image: radial-gradient(ellipse at center, black 35%, transparent 80%);
    -webkit-mask-image: radial-gradient(ellipse at center, black 35%, transparent 80%);
}

@keyframes floatOrb {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33%      { transform: translate(18px, -18px) scale(1.06); }
    66%      { transform: translate(-15px, 14px) scale(0.95); }
}

/* ====== HEADER ====== */
.login-header {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(232, 244, 255, 0.9) 100%);
    position: relative;
    z-index: 1;
}
.login-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--day-cyan), var(--day-indigo), var(--day-emerald), transparent);
    opacity: 0.85;
}

.icon-badge {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, #00A8E8, #4F46E5);
    color: #FFFFFF;
    border: none;
    box-shadow: 0 8px 20px rgba(0, 168, 232, 0.35);
    animation: pulseBadge 3s ease-in-out infinite;
}
@keyframes pulseBadge {
    0%, 100% { box-shadow: 0 6px 18px rgba(0, 168, 232, 0.35); transform: scale(1); }
    50%      { box-shadow: 0 10px 26px rgba(79, 70, 229, 0.45); transform: scale(1.04); }
}

.login-title-gradient {
    background: linear-gradient(90deg, #0F172A 0%, #0284C7 55%, #4F46E5 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 1.15rem;
    letter-spacing: -0.2px;
}
.login-subtitle {
    color: var(--day-muted);
    font-size: 0.78rem;
    font-weight: 500;
}

/* ====== INPUTS CLAROS ====== */
.login-input {
    border: 1.5px solid #CBD5E1;
    background: rgba(255, 255, 255, 0.92);
    color: var(--day-text) !important;
    font-weight: 500;
    transition: all 0.25s ease;
    position: relative;
    z-index: 1;
}
.login-input::placeholder { color: transparent; }
.login-input:focus {
    border-color: var(--day-cyan);
    background: #FFFFFF;
    box-shadow:
        0 0 0 4px rgba(0, 168, 232, 0.16),
        0 6px 18px -4px rgba(0, 168, 232, 0.25);
    color: var(--day-text);
}
.login-input.is-invalid {
    border-color: #EF4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
}

/* Labels flotantes */
.form-floating > label {
    color: #64748B !important;
    font-weight: 500;
    transition: color 0.25s;
}
.input-glow-group:focus-within > label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: #0284C7 !important;
}

.input-glow-group { position: relative; }

.input-highlight {
    position: absolute;
    bottom: 6px; left: 12px; right: 12px;
    height: 2.5px;
    background: linear-gradient(90deg, var(--day-cyan), var(--day-indigo), var(--day-emerald));
    border-radius: 2px;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
    z-index: 2;
}
.input-glow-group:focus-within .input-highlight { transform: scaleX(1); }

.input-icon { color: #0284C7; transition: color 0.25s; }
.input-glow-group:focus-within .input-icon { color: var(--day-indigo); }

/* Toggle password */
.btn-toggle-password {
    position: absolute;
    top: 50%; right: 14px;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #64748B;
    z-index: 3;
    padding: 4px 6px;
    border-radius: 6px;
    transition: color 0.2s, background 0.2s;
    line-height: 1;
}
.btn-toggle-password:hover {
    color: #0284C7;
    background: rgba(0, 168, 232, 0.12);
}

/* ====== CHECKBOX ====== */
.login-check .form-check-label {
    color: var(--day-muted) !important;
    font-weight: 500;
    cursor: pointer;
}
.login-check .form-check-input {
    background-color: #FFFFFF;
    border: 1.5px solid #94A3B8;
    cursor: pointer;
    transition: all 0.2s;
}
.login-check .form-check-input:checked {
    background: linear-gradient(135deg, var(--day-cyan), var(--day-indigo));
    border-color: transparent;
    box-shadow: 0 0 10px rgba(0, 168, 232, 0.45);
}
.login-check .form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(0, 168, 232, 0.2);
    border-color: var(--day-cyan);
}

/* ====== LINK ====== */
.login-link {
    color: #0284C7;
    position: relative;
    transition: color 0.2s;
}
.login-link::after {
    content: '';
    position: absolute;
    left: 0; right: 0; bottom: -2px;
    height: 1.5px;
    background: linear-gradient(90deg, var(--day-cyan), var(--day-indigo));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s;
}
.login-link:hover { color: var(--day-indigo); }
.login-link:hover::after { transform: scaleX(1); }

/* ====== SUBMIT VIBRANTE ====== */
.login-submit {
    background: linear-gradient(135deg, #00A8E8 0%, #4F46E5 65%, #00C875 130%);
    background-size: 200% 200%;
    color: #FFFFFF !important;
    border: none;
    letter-spacing: 0.4px;
    font-size: 0.98rem;
    transition: background-position 0.6s ease, transform 0.2s ease, box-shadow 0.3s ease;
    box-shadow: 0 10px 25px -6px rgba(79, 70, 229, 0.55);
}
.login-submit:hover {
    background-position: 100% 0;
    transform: translateY(-2px);
    box-shadow:
        0 14px 30px -6px rgba(0, 168, 232, 0.65),
        0 0 20px rgba(79, 70, 229, 0.35);
}
.login-submit:active { transform: translateY(0); }

.btn-shine {
    position: absolute;
    top: 0; left: -60%;
    width: 40%; height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255,255,255,0.45), transparent);
    transform: skewX(-20deg);
    animation: shineMove 3.5s infinite;
    z-index: 1;
}
@keyframes shineMove {
    0%   { left: -60%; }
    60%  { left: 130%; }
    100% { left: 130%; }
}

/* ====== HINT ====== */
.login-hint { color: var(--day-muted); font-weight: 500; }
.login-hint i { color: #0284C7; }

/* ====== VALIDATION ====== */
.invalid-feedback {
    color: #DC2626;
    font-weight: 500;
    font-size: 0.78rem;
}

/* ====== ANIMACIÓN DE ENTRADA ====== */
.modal.fade .modal-dialog {
    transform: translateY(24px) scale(0.96);
    opacity: 0;
    transition: transform 0.4s cubic-bezier(0.34, 1.4, 0.64, 1), opacity 0.35s ease;
}
.modal.show .modal-dialog {
    transform: translateY(0) scale(1);
    opacity: 1;
}

/* ====== RESPONSIVE ====== */
@media (max-width: 576px) {
    .login-modal-content { border-radius: 22px !important; }
    .orb { filter: blur(45px); }
    .login-title-gradient { font-size: 1.05rem; }
}
</style>

<script>
(function () {
    const loginForm = document.querySelector('#loginModal form');
    if (loginForm) {
        loginForm.addEventListener('submit', function () {
            const emailInput = document.getElementById('email');
            if (emailInput && !emailInput.value.includes('@')) {
                emailInput.value = emailInput.value + '@cooperativalosandes.com';
            }
        });
    }

    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            const icon = toggleBtn.querySelector('i');
            icon.classList.toggle('bi-eye', !isPassword);
            icon.classList.toggle('bi-eye-slash', isPassword);
        });
    }

    const loginModal = document.getElementById('loginModal');
    if (loginModal) {
        loginModal.addEventListener('shown.bs.modal', function () {
            const email = document.getElementById('email');
            if (email) email.focus();
        });
    }
})();
</script>
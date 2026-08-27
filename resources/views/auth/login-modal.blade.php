<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, var(--andean-dark-deep, #0a0a1a) 0%, var(--andean-night, #06060e) 100%);">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 pt-2 pb-3 ps-2" id="loginModalLabel">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-white bg-opacity-10 text-info" style="width: 40px; height: 40px;">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                    </div>
                    Iniciar Sesión
                </h5>
                <button type="button" class="btn-close btn-close-white me-2 mt-2" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input
                            type="text"
                            id="email"
                            name="email"
                            class="form-control rounded-3 @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="usuario"
                            required
                            autofocus
                        >
                        <label for="email" class="text-muted"><i class="bi bi-person me-1"></i> Usuario (@cooperativalosandes.com)</label>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control rounded-3 @error('password') is-invalid @enderror"
                            placeholder="Contraseña"
                            required
                        >
                        <label for="password" class="text-muted"><i class="bi bi-lock me-1"></i> Contraseña</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input shadow-sm" name="remember" id="remember">
                            <label class="form-check-label text-muted small" for="remember">Recordarme</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-decoration-none text-primary fw-medium">
                                ¿Olvidó su contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn w-100 rounded-3 py-2 fw-semibold text-white shadow-sm mb-2" style="background: linear-gradient(135deg, var(--terracota, #00ccff) 0%, var(--inti-gold, #0066ff) 100%);">
                        <i class="bi bi-shield-lock me-2"></i> Ingresar al Sistema
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    document.querySelector('#loginModal form').addEventListener('submit', function () {
        let emailInput = document.getElementById('email');

        if (!emailInput.value.includes('@')) {
            emailInput.value = emailInput.value + '@cooperativalosandes.com';
        }
    });
</script>
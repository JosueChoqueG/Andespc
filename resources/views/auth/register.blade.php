<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, var(--andean-dark-deep, #0a0a1a) 0%, var(--andean-night, #06060e) 100%);">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 pt-2 pb-3 ps-2">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-white bg-opacity-10 text-warning" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-plus-fill fs-5"></i>
                    </div>
                    Registrar Nuevo Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white me-2 mt-2" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="modal-body px-4 py-4">

                    <!-- Nombre -->
                    <div class="form-floating mb-3">
                        <input type="text" name="name" id="reg_name"
                            class="form-control rounded-3 @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Nombre completo" required autofocus>
                        <label for="reg_name" class="text-muted"><i class="bi bi-person me-1"></i> Nombre completo</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" name="email" id="reg_email"
                            class="form-control rounded-3 @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Correo electrónico" required>
                        <label for="reg_email" class="text-muted"><i class="bi bi-envelope me-1"></i> Correo electrónico</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="reg_password"
                            class="form-control rounded-3 @error('password') is-invalid @enderror"
                            placeholder="Contraseña" required>
                        <label for="reg_password" class="text-muted"><i class="bi bi-lock me-1"></i> Contraseña</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-floating mb-1">
                        <input type="password" name="password_confirmation" id="reg_password_confirmation"
                            class="form-control rounded-3" placeholder="Confirmar contraseña" required>
                        <label for="reg_password_confirmation" class="text-muted"><i class="bi bi-shield-lock me-1"></i> Confirmar contraseña</label>
                    </div>

                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-3 px-4 fw-medium text-muted" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn rounded-3 px-4 fw-medium text-white shadow-sm" style="background: linear-gradient(135deg, var(--terracota, #e9c46a) 0%, var(--inti-gold, #f4a261) 100%);">
                        <i class="bi bi-check2-circle me-1"></i> Crear Usuario
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
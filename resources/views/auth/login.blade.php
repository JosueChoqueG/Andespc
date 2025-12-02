<x-guest-layout>

    <div class="container mt-5 d-flex justify-content-center">
        <div class="card shadow-lg p-4" style="max-width: 520px; width: 100%; border-radius: 18px;">

            <h2 class="text-center mb-4 fw-bold">Iniciar sesión</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <!-- Forgot password -->
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small d-block mb-3">
                        Forgot your password?
                    </a>
                @endif

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100">
                    Log in
                </button>

            </form>

        </div>
    </div>

</x-guest-layout>

@extends('layouts.app')

@section('title', 'Prijava - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>Prijava
                    </h3>
                    <p class="mb-0 mt-2">Dobrodošli nazad!</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Korisničko ime -->
                        <div class="mb-3">
                            <label for="korisnickoIme" class="form-label">
                                <i class="fas fa-user me-1"></i>Korisničko ime
                            </label>
                            <input type="text" 
                                   class="form-control @error('korisnickoIme') is-invalid @enderror" 
                                   id="korisnickoIme" 
                                   name="korisnickoIme" 
                                   value="{{ old('korisnickoIme') }}" 
                                   required 
                                   autofocus
                                   placeholder="Unesite korisničko ime">
                            @error('korisnickoIme')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Lozinka -->
                        <div class="mb-3">
                            <label for="lozinka" class="form-label">
                                <i class="fas fa-lock me-1"></i>Lozinka
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('lozinka') is-invalid @enderror" 
                                       id="lozinka" 
                                       name="lozinka" 
                                       required
                                       placeholder="Unesite lozinku">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('lozinka')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Zapamti me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="remember" 
                                   name="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Zapamti me
                            </label>
                        </div>

                        <!-- Submit button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Prijavi se
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <p class="mb-0">
                        Nemate nalog? 
                        <a href="{{ route('register') }}" class="text-decoration-none">
                            <strong>Registrujte se ovde</strong>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('lozinka');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush
@endsection

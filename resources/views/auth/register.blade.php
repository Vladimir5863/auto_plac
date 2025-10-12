@extends('layouts.app')

@section('title', 'Registracija - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Registracija
                    </h3>
                    <p class="mb-0 mt-2">Kreirajte svoj nalog</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <!-- Korisničko ime -->
                            <div class="col-md-6 mb-3">
                                <label for="korisnickoIme" class="form-label">
                                    <i class="fas fa-user me-1"></i>Korisničko ime *
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

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="eMail" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email *
                                </label>
                                <input type="email" 
                                       class="form-control @error('eMail') is-invalid @enderror" 
                                       id="eMail" 
                                       name="eMail" 
                                       value="{{ old('eMail') }}" 
                                       required
                                       placeholder="Unesite email adresu">
                                @error('eMail')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Broj telefona -->
                            <div class="col-md-6 mb-3">
                                <label for="brojTelefona" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Broj telefona *
                                </label>
                                <input type="tel" 
                                       class="form-control @error('brojTelefona') is-invalid @enderror" 
                                       id="brojTelefona" 
                                       name="brojTelefona" 
                                       value="{{ old('brojTelefona') }}" 
                                       required
                                       placeholder="+381 11 123 4567">
                                @error('brojTelefona')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tip korisnika -->
                            <div class="col-md-6 mb-3">
                                <label for="tipKorisnika" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>Tip korisnika *
                                </label>
                                <select class="form-select @error('tipKorisnika') is-invalid @enderror" 
                                        id="tipKorisnika" 
                                        name="tipKorisnika" 
                                        required>
                                    <option value="">Izaberite tip korisnika</option>
                                    <option value="korisnik" {{ old('tipKorisnika') == 'korisnik' ? 'selected' : '' }}>
                                        Korisnik
                                    </option>
                                    <option value="moderator" {{ old('tipKorisnika') == 'moderator' ? 'selected' : '' }}>
                                        Moderator
                                    </option>
                                    <option value="admin" {{ old('tipKorisnika') == 'admin' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                </select>
                                @error('tipKorisnika')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Lozinka -->
                            <div class="col-md-6 mb-3">
                                <label for="lozinka" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Lozinka *
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
                                <small class="form-text text-muted">
                                    Najmanje 8 karaktera, velika i mala slova, brojevi
                                </small>
                                @error('lozinka')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Potvrda lozinke -->
                            <div class="col-md-6 mb-3">
                                <label for="lozinka_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Potvrdite lozinku *
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('lozinka_confirmation') is-invalid @enderror" 
                                           id="lozinka_confirmation" 
                                           name="lozinka_confirmation" 
                                           required
                                           placeholder="Potvrdite lozinku">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePasswordConfirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('lozinka_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Registruj se
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <p class="mb-0">
                        Već imate nalog? 
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <strong>Prijavite se ovde</strong>
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
    function togglePasswordVisibility(inputId, buttonId) {
        document.getElementById(buttonId).addEventListener('click', function() {
            const password = document.getElementById(inputId);
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
    }

    // Initialize password toggles
    togglePasswordVisibility('lozinka', 'togglePassword');
    togglePasswordVisibility('lozinka_confirmation', 'togglePasswordConfirmation');
</script>
@endpush
@endsection

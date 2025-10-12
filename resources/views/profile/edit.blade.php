@extends('layouts.app')

@section('title', 'Uredi profil - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-purple text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Uredi profil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="korisnickoIme" class="form-label">Korisničko ime *</label>
                                <input type="text" id="korisnickoIme" name="korisnickoIme"
                                       class="form-control @error('korisnickoIme') is-invalid @enderror"
                                       value="{{ old('korisnickoIme', $user->korisnickoIme) }}" required>
                                @error('korisnickoIme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="eMail" class="form-label">Email *</label>
                                <input type="email" id="eMail" name="eMail"
                                       class="form-control @error('eMail') is-invalid @enderror"
                                       value="{{ old('eMail', $user->eMail) }}" required>
                                @error('eMail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="brojTelefona" class="form-label">Broj telefona *</label>
                                <input type="text" id="brojTelefona" name="brojTelefona"
                                       class="form-control @error('brojTelefona') is-invalid @enderror"
                                       value="{{ old('brojTelefona', $user->brojTelefona) }}" required>
                                @error('brojTelefona')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Nazad
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Sačuvaj izmene
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-key me-2"></i>Promena lozinke</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-primary">
                        Idi na promenu lozinke
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

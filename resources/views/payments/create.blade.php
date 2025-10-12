@extends('layouts.app')

@section('title', 'Nova uplata - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-purple text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Kreiraj novu uplatu
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.store') }}">
                        @csrf


                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="iznos" class="form-label">
                                    <i class="fas fa-euro-sign me-1"></i>Iznos (€)
                                </label>
                                <input type="number" step="0.01" min="0.01" class="form-control @error('iznos') is-invalid @enderror" id="iznos" name="iznos" value="{{ old('iznos') }}" placeholder="npr. 50.00" required>
                                @error('iznos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brojRacuna" class="form-label">
                                    <i class="fas fa-receipt me-1"></i>Broj računa sa kog se skida novac
                                </label>
                                <input type="text" class="form-control @error('brojRacuna') is-invalid @enderror" id="brojRacuna" name="brojRacuna" value="{{ old('brojRacuna') }}" placeholder="npr. RS35 260-123456789-12" required>
                                @error('brojRacuna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Otkaži
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i>Potvrdi uplatu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

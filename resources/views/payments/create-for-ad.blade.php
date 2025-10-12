@extends('layouts.app')

@section('title', 'Uplata za oglas - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-purple text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Uplata za oglas
                    </h5>
                    <span class="badge {{ $ad->statusOglasa === 'istaknutiOglas' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                        {{ $ad->statusOglasa === 'istaknutiOglas' ? 'Istaknuti oglas' : 'Standardni oglas' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-4 p-3 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}</strong>
                                <div class="text-muted small">Godina: {{ $ad->vozilo->godinaProizvodnje }} • Lokacija: {{ $ad->vozilo->lokacija }}</div>
                            </div>
                            <div class="fw-bold">€{{ number_format($ad->vozilo->cena, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('ads.payment.process', $ad->oglasID) }}">
                        @csrf

                        @php $price = 30.00; @endphp

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-star me-1"></i>Cena isticanja</label>
                                <input type="text" class="form-control" value="€{{ number_format($price, 2, ',', '.') }} (fiksno)" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="brojRacuna" class="form-label"><i class="fas fa-receipt me-1"></i>Broj računa / Referenca</label>
                                <input type="text" class="form-control @error('brojRacuna') is-invalid @enderror" id="brojRacuna" name="brojRacuna" value="{{ old('brojRacuna') }}" placeholder="npr. RS35 260-123456789-12" required>
                                @error('brojRacuna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('ads.show', $ad->oglasID) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Nazad na oglas
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i>Istakni
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

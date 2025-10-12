@extends('layouts.app')

@section('title', 'Kupljena vozila - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-bag me-2"></i>Kupljena vozila</h2>
        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-car me-1"></i>Sva vozila
        </a>
    </div>

    @if($purchases->count() > 0)
        <div class="row">
            @foreach($purchases as $p)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            @php $img = $p->oglas?->vozilo?->slike[0] ?? null; @endphp
                            @if($img)
                                @if(is_string($img) && str_starts_with($img, 'data:'))
                                    <img src="{!! $img !!}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="vozilo">
                                @else
                                    <img src="{{ Storage::url($img) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="vozilo">
                                @endif
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                    <i class="fas fa-car fa-3x text-muted"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 badge bg-secondary">Kupljeno</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $p->oglas?->vozilo?->marka }} {{ $p->oglas?->vozilo?->model }}</h5>
                            <div class="row g-2 mb-2">
                                <div class="col-6"><small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $p->oglas?->vozilo?->godinaProizvodnje }}</small></div>
                                <div class="col-6"><small class="text-muted"><i class="fas fa-gas-pump me-1"></i>{{ $p->oglas?->vozilo?->tipGoriva }}</small></div>
                                <div class="col-6"><small class="text-muted"><i class="fas fa-car me-1"></i>{{ $p->oglas?->vozilo?->tipKaroserije }}</small></div>
                                <div class="col-6"><small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $p->oglas?->vozilo?->lokacija }}</small></div>
                            </div>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">€{{ number_format($p->iznos, 0, ',', '.') }}</span>
                                <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($p->datumUplate)->format('d.m.Y') }}</small>
                            </div>
                            <div class="d-grid mt-2">
                                @if($p->oglas)
                                    <a href="{{ route('vehicles.show', $p->oglas->oglasID) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Pogledaj detalje
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $purchases->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h4>Nemate kupljenih vozila</h4>
            <p class="text-muted">Kada kupite vozilo, pojaviće se ovde.</p>
            <a class="btn btn-primary" href="{{ route('vehicles.index') }}"><i class="fas fa-car me-1"></i>Pregledaj vozila</a>
        </div>
    @endif
</div>
@endsection

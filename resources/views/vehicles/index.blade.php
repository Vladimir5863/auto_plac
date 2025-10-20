@extends('layouts.app')

@section('title', 'Auto Plac - Prodaja vozila')

@section('content')
<div class="container-fluid">
    <!-- Hero + Featured for Home Page -->
    @php $isHome = request()->is('/'); @endphp
    @if($isHome)
        <div class="row mb-5">
            <div class="col-12">
                <div class="rounded-4 shadow-lg p-5 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 60%, #0a58ca 100%);">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-8">
                            <h1 class="display-5 fw-bold mb-2">
                                <i class="fas fa-car me-3"></i>Dobrodošli na Auto Plac
                            </h1>
                            <p class="fs-5 mb-4">Pronađite vozilo svojih snova ili postavite oglas za prodaju.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('vehicles.search') }}" class="btn btn-dark btn-lg px-4">
                                    <i class="fas fa-search me-2"></i>Pretraži vozila
                                </a>
                                @auth
                                    <a href="{{ route('ads.create') }}" class="btn btn-outline-light btn-lg px-4">
                                        <i class="fas fa-plus me-2"></i>Dodaj oglas
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                                        <i class="fas fa-user-plus me-2"></i>Registruj se
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                                        <i class="fas fa-sign-in-alt me-2"></i>Prijavi se
                                    </a>
                                @endauth
                            </div>
                        </div>
                        <div class="col-lg-4 d-none d-lg-block">
                            <div class="bg-white bg-opacity-10 rounded-4 p-4 h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-gauge-high fa-5x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Featured Ads Section for Home Page -->
        @php
            $featuredAds = \App\Models\Oglas::with(['vozilo', 'korisnik'])
                ->where('statusOglasa', 'istaknutiOglas')
                ->orderBy('created_at', 'desc')
                ->get();
        @endphp
        
        @if($featuredAds->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-star text-warning me-2"></i>Istaknuti oglasi
                    </h2>
                    <div class="row">
                        @foreach($featuredAds as $featured)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <!-- Vehicle Image -->
                                    <div class="position-relative">
                                        @if($featured->vozilo->slike && count($featured->vozilo->slike) > 0)
                                            @php $fi = $featured->vozilo->slike[0]; @endphp
                                            @if(is_string($fi) && str_starts_with($fi, 'data:'))
                                                <img src="{!! $fi !!}" 
                                                     class="card-img-top" 
                                                     alt="{{ $featured->vozilo->marka }} {{ $featured->vozilo->model }}"
                                                     style="height: 200px; object-fit: cover;">
                                            @elseif(is_string($fi) && (str_starts_with($fi, 'http://') || str_starts_with($fi, 'https://') || str_starts_with($fi, '/storage/')))
                                                <img src="{{ $fi }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $featured->vozilo->marka }} {{ $featured->vozilo->model }}"
                                                     style="height: 200px; object-fit: cover;">
                                            @else
                                                <img src="{{ Storage::url($fi) }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $featured->vozilo->marka }} {{ $featured->vozilo->model }}"
                                                     style="height: 200px; object-fit: cover;">
                                            @endif
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="fas fa-car fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Top overlay: name (left), featured (center), price (right) -->
                                        <div class="position-absolute top-0 start-0 w-100 d-flex align-items-start p-2" style="z-index:3;">
                                            <span class="badge bg-dark bg-opacity-75 text-white text-truncate me-2 small" style="white-space: nowrap; max-width: 45%;">{{ $featured->vozilo->marka }} {{ $featured->vozilo->model }}</span>
                                            <span class="badge bg-warning text-dark mx-auto flex-shrink-0"><i class="fas fa-star me-1"></i>Istaknut</span>
                                            <span class="badge bg-primary ms-auto flex-shrink-0">€{{ number_format($featured->vozilo->cena, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body d-flex flex-column">
                                        
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $featured->vozilo->godinaProizvodnje }}
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-tachometer-alt me-1"></i>
                                                    {{ $featured->vozilo->kilometraza }}
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-gas-pump me-1"></i>
                                                    {{ $featured->vozilo->tipGoriva }}
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-car me-1"></i>
                                                    {{ $featured->vozilo->tipKaroserije }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ $featured->vozilo->lokacija }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $featured->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            
                                            <div class="d-grid mt-2">
                                                <a href="{{ route('vehicles.show', $featured->oglasID) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Pogledaj detalje
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif
    @if($vehicles->count() > 0)
        <!-- All Vehicles Section -->
        <div class="row">
            <div class="col-12">
                @if(!$isHome)
                    <h2 class="mb-4">Rezultati pretrage</h2>
                @else
                    <h2 class="mb-4">Sva vozila</h2>
                @endif
            </div>
        </div>
        
        <div class="row">
            @foreach($vehicles as $vehicle)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Vehicle Image -->
                        <div class="position-relative">
                            @if($vehicle->vozilo->slike && count($vehicle->vozilo->slike) > 0)
                                @php $vi = $vehicle->vozilo->slike[0]; @endphp
                                @if(is_string($vi) && str_starts_with($vi, 'data:'))
                                    <img src="{!! $vi !!}" 
                                         class="card-img-top" 
                                         alt="{{ $vehicle->vozilo->marka }} {{ $vehicle->vozilo->model }}"
                                         style="height: 200px; object-fit: cover;">
                                @elseif(is_string($vi) && (str_starts_with($vi, 'http://') || str_starts_with($vi, 'https://') || str_starts_with($vi, '/storage/')))
                                    <img src="{{ $vi }}" 
                                         class="card-img-top" 
                                         alt="{{ $vehicle->vozilo->marka }} {{ $vehicle->vozilo->model }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ Storage::url($vi) }}" 
                                         class="card-img-top" 
                                         alt="{{ $vehicle->vozilo->marka }} {{ $vehicle->vozilo->model }}"
                                         style="height: 200px; object-fit: cover;">
                                @endif
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-car fa-3x text-muted"></i>
                                </div>
                            @endif

                            <!-- Top overlay: name (left), featured (center if any), price (right) -->
                            <div class="position-absolute top-0 start-0 w-100 d-flex align-items-start p-2" style="z-index:3;">
                                <span class="badge bg-dark bg-opacity-75 text-white text-truncate me-2 small" style="white-space: nowrap; max-width: 45%;">{{ $vehicle->vozilo->marka }} {{ $vehicle->vozilo->model }}</span>
                                @if($vehicle->statusOglasa === 'istaknutiOglas')
                                    <span class="badge bg-warning text-dark mx-auto flex-shrink-0"><i class="fas a-star me-1"></i>Istaknut</span>
                                @endif
                                <span class="badge bg-primary ms-auto flex-shrink-0">€{{ number_format($vehicle->vozilo->cena, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column">
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $vehicle->vozilo->godinaProizvodnje }}
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-tachometer-alt me-1"></i>
                                        {{ $vehicle->vozilo->kilometraza }}
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-gas-pump me-1"></i>
                                        {{ $vehicle->vozilo->tipGoriva }}
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-car me-1"></i>
                                        {{ $vehicle->vozilo->tipKaroserije }}
                                    </small>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $vehicle->vozilo->lokacija }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $vehicle->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <div class="d-grid mt-2">
                                    <a href="{{ route('vehicles.show', $vehicle->oglasID) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Pogledaj detalje
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $vehicles->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4>Nema rezultata</h4>
            <p class="text-muted">Nijedno vozilo ne odgovara vašim kriterijumima pretrage.</p>
            <a href="{{ route('vehicles.search') }}" class="btn btn-primary">
                <i class="fas fa-undo me-2"></i>Resetuj filtere
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Add any additional JavaScript for the results page
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any interactive elements
        console.log('Vehicle search results loaded');
    });
</script>
@endpush
@endsection

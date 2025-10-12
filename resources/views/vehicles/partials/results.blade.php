@php
    // Expecting $vehicles (LengthAwarePaginator or Collection)
@endphp

@if($vehicles->count() > 0)
    <div class="row">
        @foreach($vehicles as $vehicle)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        @php $img = $vehicle->vozilo->slike[0] ?? null; @endphp
                        @if($img)
                            @if(is_string($img) && str_starts_with($img, 'data:'))
                                <img src="{!! $img !!}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $vehicle->vozilo->marka }} {{ $vehicle->vozilo->model }}">
                            @else
                                <img src="{{ Storage::url($img) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $vehicle->vozilo->marka }} {{ $vehicle->vozilo->model }}">
                            @endif
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-car fa-3x text-muted"></i>
                            </div>
                        @endif

                        @if($vehicle->statusOglasa === 'istaknutiOglas')
                            <span class="position-absolute top-0 end-0 badge bg-warning mt-2 me-2">
                                <i class="fas fa-star me-1"></i>Istaknut
                            </span>
                        @endif

                        <span class="position-absolute bottom-0 start-0 badge bg-primary fs-6 m-2">
                            €{{ number_format($vehicle->vozilo->cena, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">{{ $vehicle->vozilo->marka }} {{ $vehicle->vozilo->model }}</h5>
                        <div class="row g-2 text-muted small mb-2">
                            <div class="col-6"><i class="fas fa-calendar me-1"></i>{{ $vehicle->vozilo->godinaProizvodnje }}</div>
                            <div class="col-6"><i class="fas fa-tachometer-alt me-1"></i>{{ $vehicle->vozilo->kilometraza }}</div>
                            <div class="col-6"><i class="fas fa-gas-pump me-1"></i>{{ $vehicle->vozilo->tipGoriva }}</div>
                            <div class="col-6"><i class="fas fa-car me-1"></i>{{ $vehicle->vozilo->tipKaroserije }}</div>
                        </div>
                        <div class="mt-auto d-grid">
                            <a href="{{ route('vehicles.show', $vehicle->oglasID) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Pogledaj detalje
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(method_exists($vehicles, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{-- Keep query string on pagination via appends --}}
            {{ $vehicles->withQueryString()->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4>Nema rezultata</h4>
        <p class="text-muted">Pokušajte da izmenite filtere pretrage.</p>
    </div>
@endif

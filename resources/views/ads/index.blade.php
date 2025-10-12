@extends('layouts.app')

@section('title', 'Moji oglasi - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list me-2"></i>Moji oglasi</h2>
        <a href="{{ route('ads.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Dodaj novi oglas
        </a>
    </div>

    @if($ads->count() > 0)
        <div class="row">
            @foreach($ads as $ad)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                @php $img = $ad->vozilo->slike[0] ?? null; @endphp
                                @if($img)
                                    @if(is_string($img) && str_starts_with($img, 'data:'))
                                        <img src="{!! $img !!}"
                                             class="img-fluid rounded-start h-100"
                                             style="object-fit: cover;"
                                             alt="{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}">
                                    @else
                                        <img src="{{ Storage::url($img) }}"
                                             class="img-fluid rounded-start h-100"
                                             style="object-fit: cover;"
                                             alt="{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}">
                                    @endif
                                @else
                                    <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-car fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">
                                            {{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}
                                        </h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('ads.show', $ad->oglasID) }}">
                                                        <i class="fas fa-eye me-2"></i>Pogledaj
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('ads.edit', $ad->oglasID) }}">
                                                        <i class="fas fa-edit me-2"></i>Uredi
                                                    </a>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('ads.destroy', $ad->oglasID) }}" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Da li ste sigurni da želite da deaktivirate ovaj oglas?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash me-2"></i>Deaktiviraj
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $ad->vozilo->godinaProizvodnje }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-tachometer-alt me-1"></i>
                                                {{ $ad->vozilo->kilometraza }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-gas-pump me-1"></i>
                                                {{ $ad->vozilo->tipGoriva }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $ad->vozilo->lokacija }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-primary mb-0">
                                                €{{ number_format($ad->vozilo->cena, 0, ',', '.') }}
                                            </h6>
                                            <small class="text-muted">
                                                Kreiran {{ $ad->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div>
                                            @if($ad->statusOglasa === 'istaknutiOglas')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-star me-1"></i>Istaknut
                                                </span>
                                            @elseif($ad->statusOglasa === 'standardniOglas')
                                                <span class="badge bg-success">Aktivan</span>
                                            @elseif($ad->statusOglasa === 'deaktiviranOglas')
                                                <span class="badge bg-danger">Deaktiviran</span>
                                            @elseif($ad->statusOglasa === 'istekaoOglas')
                                                <span class="badge bg-secondary">Istekao</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($ad->datumIsteka)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Ističe {{ $ad->datumIsteka->format('d.m.Y') }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $ads->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-list fa-3x text-muted mb-3"></i>
            <h4>Nemate oglasa</h4>
            <p class="text-muted">Kreirajte svoj prvi oglas i počnite da prodajete vozila.</p>
            <a href="{{ route('ads.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Dodaj prvi oglas
            </a>
        </div>
    @endif
</div>
@endsection

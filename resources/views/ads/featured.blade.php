@extends('layouts.app')

@section('title', 'Istaknuti oglasi - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-star me-2"></i>Moji istaknuti oglasi</h2>
        <a href="{{ route('ads.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Kreiraj oglas
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
                                        <img src="{!! $img !!}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}">
                                    @else
                                        <img src="{{ Storage::url($img) }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}">
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
                                        <h5 class="card-title mb-0">{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}</h5>
                                        <span class="badge bg-warning"><i class="fas fa-star me-1"></i>Istaknut</span>
                                    </div>

                                    <div class="row g-2 mb-3 text-muted">
                                        <div class="col-6"><small><i class="fas fa-calendar me-1"></i>{{ $ad->vozilo->godinaProizvodnje }}</small></div>
                                        <div class="col-6"><small><i class="fas fa-tachometer-alt me-1"></i>{{ $ad->vozilo->kilometraza }}</small></div>
                                        <div class="col-6"><small><i class="fas fa-gas-pump me-1"></i>{{ $ad->vozilo->tipGoriva }}</small></div>
                                        <div class="col-6"><small><i class="fas fa-map-marker-alt me-1"></i>{{ $ad->vozilo->lokacija }}</small></div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-primary mb-0">€{{ number_format($ad->vozilo->cena, 0, ',', '.') }}</h6>
                                            <small class="text-muted">Kreiran {{ $ad->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('ads.show', $ad->oglasID) }}">
                                                <i class="fas fa-eye me-1"></i>Pogledaj
                                            </a>
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('ads.edit', $ad->oglasID) }}">
                                                <i class="fas fa-edit me-1"></i>Uredi
                                            </a>
                                        </div>
                                    </div>

                                    @if($ad->datumIsteka)
                                        <div class="mt-2">
                                            <small class="text-muted"><i class="fas fa-clock me-1"></i>Ističe {{ \Carbon\Carbon::parse($ad->datumIsteka)->format('d.m.Y') }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $ads->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-star fa-3x text-muted mb-3"></i>
            <h4>Nemate istaknutih oglasa</h4>
            <p class="text-muted">Istaknite oglas da bi se ovde prikazao.</p>
            <a href="{{ route('ads.index') }}" class="btn btn-primary">
                <i class="fas fa-list me-2"></i>Moji oglasi
            </a>
        </div>
    @endif
</div>
@endsection

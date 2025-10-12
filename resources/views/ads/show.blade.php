@extends('layouts.app')

@section('title', 'Pregled oglasa - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-purple text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-car me-2"></i>{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}
                    </h5>
                    <span class="badge {{ $ad->statusOglasa === 'istaknutiOglas' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                        {{ $ad->statusOglasa === 'istaknutiOglas' ? 'Istaknuti oglas' : 'Standardni oglas' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            @php $slike = $ad->vozilo->slike ?? []; @endphp
                            @if(is_array($slike) && count($slike) > 0)
                                <div id="adGallery" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($slike as $idx => $path)
                                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                                @if(is_string($path) && str_starts_with($path, 'data:'))
                                                    <img src="{!! $path !!}" class="d-block w-100 rounded" style="max-height:380px;object-fit:cover;" alt="{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}" />
                                                @else
                                                    <img src="{{ Storage::url($path) }}" class="d-block w-100 rounded" style="max-height:380px;object-fit:cover;" alt="{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}" />
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#adGallery" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Prethodna</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#adGallery" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Sledeća</span>
                                    </button>
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height:380px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">€{{ number_format($ad->vozilo->cena, 0, ',', '.') }}</h3>
                                <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ $ad->created_at?->diffForHumans() }}</small>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Marka:</strong> {{ $ad->vozilo->marka }}</li>
                                <li class="list-group-item"><strong>Model:</strong> {{ $ad->vozilo->model }}</li>
                                <li class="list-group-item"><strong>Godina:</strong> {{ $ad->vozilo->godinaProizvodnje }}</li>
                                <li class="list-group-item"><strong>Kilometraža:</strong> {{ $ad->vozilo->kilometraza }}</li>
                                <li class="list-group-item"><strong>Gorivo:</strong> {{ $ad->vozilo->tipGoriva }}</li>
                                <li class="list-group-item"><strong>Karoserija:</strong> {{ $ad->vozilo->tipKaroserije }}</li>
                                <li class="list-group-item"><strong>Menjač:</strong> {{ $ad->vozilo->tipMenjaca }}</li>
                                <li class="list-group-item"><strong>Snaga:</strong> {{ $ad->vozilo->snagaMotoraKW }} kW</li>
                                <li class="list-group-item"><strong>Kubikaža:</strong> {{ $ad->vozilo->kubikaza }} cc</li>
                                <li class="list-group-item"><strong>Klima:</strong> {{ $ad->vozilo->klima }}</li>
                                <li class="list-group-item"><strong>Euro norma:</strong> {{ $ad->vozilo->euroNorma }}</li>
                                <li class="list-group-item"><strong>Lokacija:</strong> {{ $ad->vozilo->lokacija }}</li>
                                <li class="list-group-item"><strong>Važi do:</strong> {{ \Carbon\Carbon::parse($ad->datumIsteka)->format('d.m.Y') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h5 class="mb-2">Opis</h5>
                        <p class="mb-0">{{ $ad->vozilo->opis }}</p>
                    </div>
                    <div class="mt-4">
                        <h5 class="mb-2">
                            <i class="fas fa-phone me-2"></i>Kontakt prodavca
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="small text-muted">Prodavac</div>
                                    <div class="fw-semibold">{{ $ad->korisnik->korisnickoIme ?? 'Prodavac' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="small text-muted">Telefon</div>
                                    <div class="fw-semibold">{{ $ad->korisnik->brojTelefona ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="small text-muted">Email</div>
                                    <div class="fw-semibold">{{ $ad->korisnik->eMail ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="small text-muted">Lokacija</div>
                                    <div class="fw-semibold">{{ $ad->vozilo->lokacija }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Nazad na moje oglase
                    </a>
                    @auth
                        @if(Auth::id() === $ad->korisnikID)
                            <div class="d-flex gap-2">
                                <a href="{{ route('ads.edit', $ad->oglasID) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i>Uredi
                                </a>
                                @if($ad->statusOglasa !== 'istaknutiOglas')
                                    <a href="{{ route('ads.payment.create', $ad->oglasID) }}" class="btn btn-primary">
                                        <i class="fas fa-star me-1"></i>Istakni oglas
                                    </a>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

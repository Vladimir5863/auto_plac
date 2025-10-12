@extends('layouts.app')

@section('title', $oglas->vozilo->marka . ' ' . $oglas->vozilo->model . ' - Auto Plac')

@section('content')
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Vehicle Images -->
            <div class="card mb-4">
                <div class="card-body">
                    @if($oglas->vozilo->slike && count($oglas->vozilo->slike) > 0)
                        <div id="vehicleCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($oglas->vozilo->slike as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        @if(is_string($image) && str_starts_with($image, 'data:'))
                                            <img src="{!! $image !!}" 
                                                 class="d-block w-100" 
                                                 alt="{{ $oglas->vozilo->marka }} {{ $oglas->vozilo->model }}"
                                                 style="height: 400px; object-fit: cover;">
                                        @else
                                            <img src="{{ Storage::url($image) }}" 
                                                 class="d-block w-100" 
                                                 alt="{{ $oglas->vozilo->marka }} {{ $oglas->vozilo->model }}"
                                                 style="height: 400px; object-fit: cover;">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if(count($oglas->vozilo->slike) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5 bg-light">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nema slika za ovo vozilo</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Detalji vozila
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Marka:</strong></td>
                                    <td>{{ $oglas->vozilo->marka }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Model:</strong></td>
                                    <td>{{ $oglas->vozilo->model }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Godina:</strong></td>
                                    <td>{{ $oglas->vozilo->godinaProizvodnje }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kilometraža:</strong></td>
                                    <td>{{ $oglas->vozilo->kilometraza }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tip goriva:</strong></td>
                                    <td>{{ $oglas->vozilo->tipGoriva }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tip karoserije:</strong></td>
                                    <td>{{ $oglas->vozilo->tipKaroserije }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Snaga motora:</strong></td>
                                    <td>{{ $oglas->vozilo->snagaMotoraKW }} KW</td>
                                </tr>
                                <tr>
                                    <td><strong>Kubikaža:</strong></td>
                                    <td>{{ $oglas->vozilo->kubikaza }} cc</td>
                                </tr>
                                <tr>
                                    <td><strong>Menjač:</strong></td>
                                    <td>{{ $oglas->vozilo->tipMenjaca }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Klima:</strong></td>
                                    <td>{{ $oglas->vozilo->klima }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Euro norma:</strong></td>
                                    <td>{{ $oglas->vozilo->euroNorma }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Oštećenje:</strong></td>
                                    <td>
                                        @if($oglas->vozilo->ostecenje)
                                            <span class="badge bg-danger">Da</span>
                                        @else
                                            <span class="badge bg-success">Ne</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-align-left me-2"></i>Opis vozila
                    </h4>
                </div>
                <div class="card-body">
                    <p>{{ $oglas->vozilo->opis }}</p>
                </div>
            </div>

            <!-- Seller Contact -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>Kontakt prodavca
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ime:</strong> {{ $oglas->korisnik->korisnickoIme }}</p>
                            <p><strong>Email:</strong> {{ $oglas->korisnik->eMail }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Telefon:</strong> {{ $oglas->korisnik->brojTelefona }}</p>
                            <p><strong>Lokacija:</strong> {{ $oglas->vozilo->lokacija }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Price Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h2 class="text-primary mb-3">€{{ number_format($oglas->vozilo->cena, 0, ',', '.') }}</h2>
                    @if($oglas->statusOglasa === 'istaknutiOglas')
                        <span class="badge bg-warning fs-6 mb-3">
                            <i class="fas fa-star me-1"></i>Istaknut oglas
                        </span>
                    @elseif($oglas->statusOglasa === 'prodatOglas')
                        <span class="badge bg-dark fs-6 mb-3">
                            <i class="fas fa-check me-1"></i>Prodato
                        </span>
                    @endif
                    <p class="text-muted mb-3">
                        <i class="fas fa-clock me-1"></i>
                        Oglas kreiran {{ $oglas->created_at->diffForHumans() }}
                    </p>
                    @if($oglas->datumIsteka)
                        <p class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Ističe {{ $oglas->datumIsteka->format('d.m.Y') }}
                        </p>
                    @endif

                    @auth
                        @php 
                            $canBuy = !in_array($oglas->statusOglasa, ['deaktiviranOglas','istekaoOglas','prodatOglas']) && (Auth::id() !== $oglas->korisnikID);
                            $boughtByUser = isset($oglas->uplate) && $oglas->uplate->contains(function($u){
                                return $u->tip === 'purchase' && $u->fromUserID === Auth::id();
                            });
                        @endphp
                        <div class="mt-3">
                            @if($canBuy)
                                <a href="{{ route('vehicles.purchase.create', $oglas->oglasID) }}" class="btn btn-success">
                                    <i class="fas fa-shopping-cart me-1"></i>Kupi vozilo
                                </a>
                            @else
                                @if($boughtByUser)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Kupljeno</span>
                                @else
                                    <span class="badge bg-secondary">Nije dostupno za kupovinu</span>
                                @endif
                            @endif
                        </div>
                    @endauth
                </div>
            </div>

            

            <!-- Related Vehicles -->
            @if($relatedVehicles->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-car me-2"></i>Slična vozila
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedVehicles as $related)
                            <div class="d-flex mb-3">
                                @if($related->vozilo->slike && count($related->vozilo->slike) > 0)
                                    @php $ri = $related->vozilo->slike[0]; @endphp
                                    @if(is_string($ri) && str_starts_with($ri, 'data:'))
                                        <img src="{!! $ri !!}" 
                                             class="rounded me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <img src="{{ Storage::url($ri) }}" 
                                             class="rounded me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @endif
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-car text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $related->vozilo->marka }} {{ $related->vozilo->model }}</h6>
                                    <p class="text-muted mb-1">{{ $related->vozilo->godinaProizvodnje }}</p>
                                    <p class="text-primary mb-0">€{{ number_format($related->vozilo->cena, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize carousel if there are multiple images
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('vehicleCarousel');
        if (carousel) {
            new bootstrap.Carousel(carousel);
        }
    });
</script>
@endpush
@endsection

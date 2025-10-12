@extends('layouts.app')

@section('title', 'Moj profil - Auto Plac')

@section('content')
<div class="container">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>{{ $user->korisnickoIme }}</h5>
                    <p class="text-muted">{{ $user->eMail }}</p>
                    <span class="badge bg-{{ $user->tipKorisnika === 'admin' ? 'danger' : 'success' }}">
                        {{ ucfirst($user->tipKorisnika) }}
                    </span>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i>Profil
                    </a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-edit me-2"></i>Uredi profil
                    </a>
                    <a href="{{ route('profile.change-password') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-lock me-2"></i>Promeni lozinku
                    </a>
                    <a href="{{ route('profile.my-ads') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-list me-2"></i>Moji oglasi
                    </a>
                    <a href="{{ route('profile.my-payments') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-credit-card me-2"></i>Moje uplate
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-lg-9">
            <!-- Statistics Cards -->
            <div class="row mb-4 justify-content-center">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $adsCount }}</h4>
                                    <p class="mb-0">Ukupno oglasa</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-list fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $activeAdsCount }}</h4>
                                    <p class="mb-0">Aktivni oglasi</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $featuredAdsCount }}</h4>
                                    <p class="mb-0">Istaknuti oglasi</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-star fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informacije o profilu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Korisničko ime:</strong></td>
                                    <td>{{ $user->korisnickoIme }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->eMail }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Broj telefona:</strong></td>
                                    <td>{{ $user->brojTelefona }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tip korisnika:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $user->tipKorisnika === 'admin' ? 'danger' : 'success' }}">
                                            {{ ucfirst($user->tipKorisnika) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Član od:</strong></td>
                                    <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Poslednja aktivnost:</strong></td>
                                    <td>{{ $user->updated_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            @if($recentPayments->count() > 0)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Nedavne uplate
                        </h5>
                        <a href="{{ route('profile.my-payments') }}" class="btn btn-sm btn-outline-primary">
                            Pogledaj sve
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Datum</th>
                                        <th>Oglas</th>
                                        <th>Iznos</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->datumUplate->format('d.m.Y') }}</td>
                                            <td>
                                                @if($payment->oglas)
                                                    {{ $payment->oglas->vozilo->marka }} {{ $payment->oglas->vozilo->model }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="text-success">€{{ number_format($payment->iznos, 2) }}</td>
                                            <td>
                                                <span class="badge bg-success">Uspešno</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

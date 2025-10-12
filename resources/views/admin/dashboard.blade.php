@extends('layouts.app')

@section('title', 'Admin Dashboard - Auto Plac')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Ukupno korisnika
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aktivni oglasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAds }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Ukupno prihoda
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format($totalPayments, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Mesečni prihod
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format($monthlyPayments, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Mesečni prihodi
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Tipovi oglasa
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="adsTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Nedavni oglasi
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($recentAds as $ad)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                @php
                                    $imgs = $ad->vozilo->slike ?? null;
                                    $first = is_array($imgs) ? ($imgs[0] ?? null) : $imgs;
                                    $src = null;
                                    if ($first) {
                                        if (preg_match('/^data:/i', $first)) {
                                            $src = $first; // already a data URL
                                        } elseif (preg_match('/^(https?:)?\/\//i', $first)) {
                                            $src = $first; // absolute URL
                                        } elseif (preg_match('/^(storage\/|public\/)/i', $first)) {
                                            $src = Storage::url($first); // stored via storage/public
                                        } else {
                                            $src = asset($first); // fallback to public asset path
                                        }
                                    }
                                @endphp
                                @if(!empty($src))
                                    <img src="{{ $src }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-car text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}</h6>
                                <small class="text-muted">{{ $ad->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-{{ $ad->statusOglasa === 'istaknutiOglas' ? 'warning' : 'success' }}">
                                    {{ $ad->statusOglasa === 'istaknutiOglas' ? 'Istaknut' : 'Aktivan' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-credit-card me-2"></i>Nedavne uplate
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($recentPayments as $payment)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-credit-card fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">€{{ number_format($payment->iznos, 2) }}</h6>
                                <small class="text-muted">{{ $payment->datumUplate->diffForHumans() }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-success">Uspešno</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Nedavni korisnici
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($recentUsers as $user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $user->korisnickoIme }}</h6>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-{{ $user->tipKorisnika === 'admin' ? 'danger' : 'success' }}">
                                    {{ ucfirst($user->tipKorisnika) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Brze akcije
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users') }}" class="btn btn-primary w-100">
                                <i class="fas fa-users me-2"></i>Upravljaj korisnicima
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.ads') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-2"></i>Upravljaj oglasima
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reports') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-chart-bar me-2"></i>Izveštaji
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reports.create') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-plus me-2"></i>Kreiraj izveštaj
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Revenue Chart (live data)
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    fetch('{{ route('admin.stats.monthly') }}', { headers: { 'Accept': 'application/json' }})
        .then(r => r.json())
        .then(({ labels, data }) => {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Prihod (€)',
                        data: data,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.15)',
                        fill: true,
                        tension: 0.25,
                        pointRadius: 3,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        })
        .catch(() => {
            // Fallback: empty chart if API fails
            new Chart(monthlyCtx, { type: 'line', data: { labels: [], datasets: [{ label: 'Prihod (€)', data: [] }] } });
        });

    // Ads Type Chart
    const adsTypeCtx = document.getElementById('adsTypeChart').getContext('2d');
    new Chart(adsTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Standardni', 'Istaknuti'],
            datasets: [{
                data: [{{ $totalAds - $featuredAds }}, {{ $featuredAds }}],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endsection

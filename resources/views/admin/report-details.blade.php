@extends('layouts.app')

@section('title', 'Detalji izveštaja - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Detalji izveštaja</h2>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Nazad</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="small text-white-50">Period</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($report->datumOd)->format('d.m.Y') }} – {{ \Carbon\Carbon::parse($report->datumDo)->format('d.m.Y') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="small text-white-50">Broj uplata</div>
                    <div class="fs-4">{{ $summary['countAll'] ?? $report->brojUplata }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="small text-white-50">Ukupno</div>
                    <div class="fs-4">€{{ number_format($summary['sumAll'] ?? 0, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="small text-muted">Istaknuti / Kupovine</div>
                    <div class="fw-semibold">€{{ number_format($summary['sumFeatured'] ?? 0, 2, ',', '.') }} / €{{ number_format($summary['sumPurchase'] ?? 0, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-list me-2"></i>Uplate u periodu</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Datum</th>
                                    <th>Tip</th>
                                    <th>Od korisnika</th>
                                    <th>Za oglas</th>
                                    <th class="text-end">Iznos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $p)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($p->datumUplate)->format('d.m.Y') }}</td>
                                        <td>
                                            @if($p->tip === 'featured')
                                                <span class="badge bg-warning text-dark">Istaknuti</span>
                                            @elseif($p->tip === 'purchase')
                                                <span class="badge bg-info">Kupovina</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $p->tip }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->korisnickoIme ?? '-' }}</td>
                                        <td>
                                            @if(!empty($p->ad_id))
                                                <a href="{{ route('vehicles.show', $p->ad_id) }}">#{{ $p->ad_id }}@if(!empty($p->vozilo_marka)) - {{ $p->vozilo_marka }} {{ $p->vozilo_model }}@endif</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">€{{ number_format($p->iznos, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Nema uplata u periodu.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Oglasi u izveštaju</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Vozilo</th>
                                    <th>Korisnik</th>
                                    <th>Tip</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($report->oglasi as $ad)
                                    <tr>
                                        <td><a href="{{ route('vehicles.show', $ad->oglasID) }}">#{{ $ad->oglasID }}</a></td>
                                        <td><a href="{{ route('vehicles.show', $ad->oglasID) }}">{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}</a></td>
                                        <td>{{ $ad->korisnik->korisnickoIme ?? '-' }}</td>
                                        <td>
                                            @php $t = $ad->pivot->tip ?? null; @endphp
                                            @if($t === 'featured')
                                                <span class="badge bg-warning text-dark me-2">Istaknuti</span>
                                            @elseif($t === 'purchase')
                                                <span class="badge bg-info me-2">Kupovina</span>
                                            @else
                                                <span class="badge bg-secondary me-2">-</span>
                                            @endif
                                            @switch($ad->statusOglasa)
                                                @case('istaknutiOglas') <span class="badge bg-warning text-dark me-2">Istaknuti</span> @break
                                                @case('standardniOglas') <span class="badge bg-secondary me-2">Standardni</span> @break
                                                @case('deaktiviranOglas') <span class="badge bg-dark me-2">Deaktiviran</span> @break
                                                @case('istekaoOglas') <span class="badge bg-light text-dark me-2">Istekao</span> @break
                                                @case('prodatOglas') <span class="badge bg-success me-2">Prodat</span> @break
                                            @endswitch
                                            @if(!is_null($ad->cenaIstaknutogOglasa))
                                                <span class="text-muted small">Cena istakn.: €{{ number_format($ad->cenaIstaknutogOglasa, 2, ',', '.') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Nema povezanih oglasa.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detalji uplate - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-receipt me-2"></i>Detalji uplate</h2>
        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Nazad na uplate</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Uplata #{{ $payment->uplataID }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Datum</div>
                        <div class="col-sm-8">{{ \Carbon\Carbon::parse($payment->datumUplate)->format('d.m.Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Tip</div>
                        <div class="col-sm-8">
                            @if($payment->tip === 'featured')
                                <span class="badge bg-warning text-dark">Istaknuti</span>
                            @elseif($payment->tip === 'purchase')
                                <span class="badge bg-info">Kupovina</span>
                            @else
                                <span class="badge bg-secondary">{{ $payment->tip }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Iznos</div>
                        <div class="col-sm-8 fw-semibold">€{{ number_format($payment->iznos, 2, ',', '.') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Od korisnika</div>
                        <div class="col-sm-8">{{ $payment->fromUser?->korisnickoIme ?? '-' }} (ID: {{ $payment->fromUserID }})</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Ka korisniku</div>
                        <div class="col-sm-8">{{ $payment->toUser?->korisnickoIme ?? '-' }} @if($payment->toUserID) (ID: {{ $payment->toUserID }}) @endif</div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-4 text-muted">Za oglas</div>
                        <div class="col-sm-8">
                            @if($payment->oglas)
                                <a href="{{ route('vehicles.show', $payment->oglas->oglasID) }}">#{{ $payment->oglas->oglasID }} - {{ $payment->oglas->vozilo->marka }} {{ $payment->oglas->vozilo->model }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header"><h5 class="mb-0">Kratak pregled</h5></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><span class="text-muted">Uplata ID:</span> #{{ $payment->uplataID }}</li>
                        <li class="mb-2"><span class="text-muted">Tip:</span> {{ $payment->tip }}</li>
                        <li class="mb-2"><span class="text-muted">Iznos:</span> €{{ number_format($payment->iznos, 2, ',', '.') }}</li>
                        <li class="mb-2"><span class="text-muted">Datum:</span> {{ \Carbon\Carbon::parse($payment->datumUplate)->format('d.m.Y H:i') }}</li>
                        <li class="mb-2"><span class="text-muted">Od:</span> {{ $payment->fromUser?->korisnickoIme ?? '-' }}</li>
                        <li class="mb-0"><span class="text-muted">Ka:</span> {{ $payment->toUser?->korisnickoIme ?? '-' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

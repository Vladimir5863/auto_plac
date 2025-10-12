@extends('layouts.app')

@section('title', 'Uplate - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-credit-card me-2"></i>Uplate</h2>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-white-50">Ukupno uplaćeno</div>
                        <div class="fs-4">€{{ number_format($totalAmount, 2, ',', '.') }}</div>
                    </div>
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-white-50">Ovaj mesec</div>
                        <div class="fs-4">€{{ number_format($monthlyAmount, 2, ',', '.') }}</div>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted">Broj uplata</div>
                        <div class="fs-4">{{ $payments->total() }}</div>
                    </div>
                    <i class="fas fa-receipt fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Datum</th>
                        <th>Korisnik</th>
                        <th>Oglas</th>
                        <th>Iznos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->datumUplate)->format('d.m.Y') }}</td>
                            <td>{{ $payment->korisnik->korisnickoIme ?? 'N/A' }}</td>
                            <td>
                                @if($payment->oglas && $payment->oglas->vozilo)
                                    <div class="d-flex align-items-center">
                                        @php $img = $payment->oglas->vozilo->slike[0] ?? null; @endphp
                                        @if($img)
                                            @if(is_string($img) && str_starts_with($img, 'data:'))
                                                <img src="{!! $img !!}" class="rounded me-2" style="width:48px;height:48px;object-fit:cover;" alt="slika">
                                            @else
                                                <img src="{{ Storage::url($img) }}" class="rounded me-2" style="width:48px;height:48px;object-fit:cover;" alt="slika">
                                            @endif
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                                <i class="fas fa-car text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $payment->oglas->vozilo->marka }} {{ $payment->oglas->vozilo->model }}</div>
                                            <div class="text-muted small">#{{ $payment->oglas->oglasID }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-success">€{{ number_format($payment->iznos, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection

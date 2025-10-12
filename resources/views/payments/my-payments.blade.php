@extends('layouts.app')

@section('title', 'Moje uplate - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-credit-card me-2"></i>Moje uplate</h2>a>
    </div>

    <div class="badge bg-primary fs-6 mb-3">Ukupno: €{{ number_format($totalAmount, 2, ',', '.') }}</div>

    @if($payments->count() > 0)
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Datum</th>
                            <th>Oglas</th>
                            <th>Iznos</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payment->datumUplate)->format('d.m.Y') }}</td>
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
                                        <span class="text-muted">Top-up novčanika</span>
                                    @endif
                                </td>
                                <td class="text-success">€{{ number_format($payment->iznos, 2, ',', '.') }}</td>
                                <td>
                                    @if($payment->oglas)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('ads.show', $payment->oglas->oglasID) }}">
                                            <i class="fas fa-eye me-1"></i>Oglas
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
            <h4>Nemate uplata</h4>
            <p class="text-muted">Kada dopunite novčanik ili istaknete oglas, uplata će se pojaviti ovde.</p>
        </div>
    @endif
</div>
@endsection

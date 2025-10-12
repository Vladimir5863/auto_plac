@extends('layouts.app')

@section('title', 'Admin - Oglasi - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h2 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Oglasi</h2>
        <div class="d-flex align-items-center gap-2">
            @php $status = request('status', 'all'); @endphp
            <div class="btn-group" role="group" aria-label="Filter statusa">
                <a href="{{ route('admin.ads', array_merge(request()->except('page'), ['status' => 'all'])) }}" class="btn btn-sm {{ $status==='all' ? 'btn-primary' : 'btn-outline-primary' }}">Svi</a>
                <a href="{{ route('admin.ads', array_merge(request()->except('page'), ['status' => 'standardniOglas'])) }}" class="btn btn-sm {{ $status==='standardniOglas' ? 'btn-primary' : 'btn-outline-primary' }}">Aktivni</a>
                <a href="{{ route('admin.ads', array_merge(request()->except('page'), ['status' => 'istaknutiOglas'])) }}" class="btn btn-sm {{ $status==='istaknutiOglas' ? 'btn-primary' : 'btn-outline-primary' }}">Istaknuti</a>
                <a href="{{ route('admin.ads', array_merge(request()->except('page'), ['status' => 'deaktiviranOglas'])) }}" class="btn btn-sm {{ $status==='deaktiviranOglas' ? 'btn-primary' : 'btn-outline-primary' }}">Deaktivirani</a>
                <a href="{{ route('admin.ads', array_merge(request()->except('page'), ['status' => 'istekaoOglas'])) }}" class="btn btn-sm {{ $status==='istekaoOglas' ? 'btn-primary' : 'btn-outline-primary' }}">Istekli</a>
                <a href="{{ route('admin.ads', array_merge(request()->except('page'), ['status' => 'prodatOglas'])) }}" class="btn btn-sm {{ $status==='prodatOglas' ? 'btn-primary' : 'btn-outline-primary' }}">Prodati</a>
            </div>
            <form method="GET" class="d-flex align-items-center gap-2" action="{{ route('admin.ads') }}">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-filter me-1"></i>Filtriraj</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Slika</th>
                        <th>Vozilo</th>
                        <th>Korisnik</th>
                        <th>Status</th>
                        <th>Cena</th>
                        <th>Kreiran</th>
                        <th class="actions">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ads as $ad)
                        <tr>
                            <td>{{ $ad->oglasID }}</td>
                            <td>
                                @php $img = $ad->vozilo->slike[0] ?? null; @endphp
                                @if($img)
                                    @if(is_string($img) && str_starts_with($img, 'data:'))
                                        <img src="{!! $img !!}" class="rounded" style="width:60px;height:60px;object-fit:cover;">
                                    @else
                                        <img src="{{ Storage::url($img) }}" class="rounded" style="width:60px;height:60px;object-fit:cover;">
                                    @endif
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                        <i class="fas fa-car text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $ad->vozilo->marka }} {{ $ad->vozilo->model }}</div>
                                <div class="text-muted small">{{ $ad->vozilo->godinaProizvodnje }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $ad->korisnik->korisnickoIme ?? 'N/A' }}</div>
                                <div class="text-muted small">#{{ $ad->korisnikID }}</div>
                            </td>
                            <td>
                                @if($ad->statusOglasa === 'istaknutiOglas')
                                    <span class="badge bg-warning"><i class="fas fa-star me-1"></i>Istaknut</span>
                                @elseif($ad->statusOglasa === 'standardniOglas')
                                    <span class="badge bg-success">Aktivan</span>
                                @elseif($ad->statusOglasa === 'deaktiviranOglas')
                                    <span class="badge bg-danger">Deaktiviran</span>
                                @elseif($ad->statusOglasa === 'istekaoOglas')
                                    <span class="badge bg-secondary">Istekao</span>
                                @elseif($ad->statusOglasa === 'prodatOglas')
                                    <span class="badge bg-dark">Prodati</span>
                                @endif
                            </td>
                            <td>€{{ number_format($ad->vozilo->cena, 0, ',', '.') }}</td>
                            <td>{{ $ad->created_at->format('d.m.Y') }}</td>
                            <td class="actions d-flex gap-2 align-items-center">
                                <a href="{{ route('vehicles.show', $ad->oglasID) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Pogledaj
                                </a>
                                @if($ad->statusOglasa !== 'deaktiviranOglas')
                                    <form method="POST" action="{{ route('admin.ads.delete', $ad->oglasID) }}" onsubmit="return confirm('Deaktivirati oglas?');" class="m-0 d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Deaktiviraj
                                        </button>
                                    </form>
                                @endif
                                @if(in_array($ad->statusOglasa, ['deaktiviranOglas','istekaoOglas']))
                                    <form method="POST" action="{{ route('admin.ads.activate', $ad->oglasID) }}" onsubmit="return confirm('Aktivirati oglas kao standardni?');" class="m-0 d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-undo me-1"></i>Aktiviraj
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $ads->withQueryString()->links() }}
    </div>
</div>
@endsection

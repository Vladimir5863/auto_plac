@extends('layouts.app')

@section('title', 'Admin - Izveštaji - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h2 class="mb-0"><i class="fas fa-chart-line me-2"></i>Izveštaji</h2>
        <div class="d-flex align-items-center gap-2">
            <form method="GET" action="{{ route('admin.reports') }}" class="d-flex align-items-center gap-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-filter me-1"></i>Filtriraj</button>
            </form>
            <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Novi izveštaj
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Period</th>
                        <th>Broj uplata</th>
                        <th>Broj oglasa u izveštaju</th>
                        <th>Kreiran</th>
                        <th class="actions">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td>{{ method_exists($r, 'getKey') ? $r->getKey() : ($r->izvestajID ?? $r->id) }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->datumOd)->format('d.m.Y') }} – {{ \Carbon\Carbon::parse($r->datumDo)->format('d.m.Y') }}</td>
                            <td>{{ $r->brojUplata }}</td>
                            <td>{{ $r->oglasi_count }}</td>
                            <td>{{ $r->created_at->format('d.m.Y') }}</td>
                            <td class="actions">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.reports.show', method_exists($r, 'getKey') ? $r->getKey() : ($r->izvestajID ?? $r->id)) }}">
                                    <i class="fas fa-eye me-1"></i>Detalji
                                </a>
                                <form action="{{ route('admin.reports.delete', method_exists($r, 'getKey') ? $r->getKey() : ($r->izvestajID ?? $r->id)) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Obrisati izveštaj? Ova akcija je trajna.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i>Obriši
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nema izveštaja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection

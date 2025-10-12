@extends('layouts.app')

@section('title', 'Admin - Korisnici - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h2 class="mb-0"><i class="fas fa-users me-2"></i>Korisnici</h2>
        <div class="d-flex align-items-center gap-2">
            <div class="btn-group" role="group" aria-label="Filter statusa">
                @php $status = $status ?? request('status', 'all'); @endphp
                <a href="{{ route('admin.users', ['status' => 'all']) }}" class="btn btn-sm {{ $status==='all' ? 'btn-primary' : 'btn-outline-primary' }}">Svi</a>
                <a href="{{ route('admin.users', ['status' => 'active']) }}" class="btn btn-sm {{ $status==='active' ? 'btn-primary' : 'btn-outline-primary' }}">Aktivni</a>
                <a href="{{ route('admin.users', ['status' => 'banned']) }}" class="btn btn-sm {{ $status==='banned' ? 'btn-primary' : 'btn-outline-primary' }}">Banovani</a>
            </div>
            <form method="GET" class="d-flex" action="{{ route('admin.users') }}">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm me-2" placeholder="Pretraži korisnike...">
                <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Korisničko ime</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Tip</th>
                        <th>Oglasa</th>
                        <th>Uplata</th>
                        <th>Deleted at</th>
                        <th>Kreiran</th>
                        <th class="actions">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->korisnickoIme }}</td>
                            <td>{{ $user->eMail }}</td>
                            <td>{{ $user->brojTelefona }}</td>
                            <td>
                                <span class="badge bg-{{ $user->tipKorisnika === 'admin' ? 'danger' : 'success' }}">
                                    {{ ucfirst($user->tipKorisnika) }}
                                </span>
                            </td>
                            <td>{{ $user->oglasi_count }}</td>
                            <td>{{ $user->uplate_count }}</td>
                            <td>{{ $user->deleted_at ? \Carbon\Carbon::parse($user->deleted_at)->format('d.m.Y H:i') : '-' }}</td>
                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            <td class="actions d-flex gap-2 align-items-center">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Pogledaj
                                </a>
                                @if(!$user->deleted_at)
                                    @if($user->tipKorisnika !== 'admin')
                                        <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" onsubmit="return confirm('Banovati korisnika i deaktivirati sve njegove oglase?');" class="m-0 d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-ban me-1"></i>Ban
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <form method="POST" action="{{ route('admin.users.unban', $user->id) }}" onsubmit="return confirm('Unbanovati korisnika i vratiti njegove oglase?');" class="m-0 d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-undo me-1"></i>Unban
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
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection

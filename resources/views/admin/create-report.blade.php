@extends('layouts.app')

@section('title', 'Novi izveštaj - Auto Plac')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-file-alt me-2"></i>Kreiraj izveštaj o uplatama</h2>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Nazad na izveštaje
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Parametri izveštaja</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reports.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="datumOd" class="form-label">Datum od</label>
                                <input type="date" id="datumOd" name="datumOd" class="form-control @error('datumOd') is-invalid @enderror" value="{{ old('datumOd') }}" required>
                                @error('datumOd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="datumDo" class="form-label">Datum do</label>
                                <input type="date" id="datumDo" name="datumDo" class="form-control @error('datumDo') is-invalid @enderror" value="{{ old('datumDo') }}" required>
                                @error('datumDo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label"><i class="fas fa-filter me-1"></i>Tipovi transakcija</label>
                            @php $typesOld = (array) old('types', ['featured','purchase']); @endphp
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="type_featured" name="types[]" value="featured" {{ in_array('featured', $typesOld) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_featured">Istaknuti</label>
                                </div>
                                                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="type_purchase" name="types[]" value="purchase" {{ in_array('purchase', $typesOld) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_purchase">Kupovina</label>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Izveštaj će obuhvatiti uplate u izabranom periodu i po izabranim tipovima transakcija (istaknuti oglasi i kupovine).
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-outline-secondary">Očisti</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i>Kreiraj izveštaj
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

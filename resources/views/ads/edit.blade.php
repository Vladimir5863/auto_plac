@extends('layouts.app')

@section('title', 'Uredi oglas - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-purple text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Uredi oglas
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('ads.update', $ad->oglasID) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Osnovne informacije -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Osnovne informacije
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="marka" class="form-label">Marka vozila *</label>
                                <select name="marka" id="marka" class="form-select @error('marka') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Izaberite marku</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand }}" {{ old('marka', $ad->vozilo->marka) == $brand ? 'selected' : '' }}>
                                            {{ $brand }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('marka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="model" class="form-label">Model vozila *</label>
                                <select name="model" id="model" class="form-select @error('model') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Učitavanje...</option>
                                </select>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="godinaProizvodnje" class="form-label">Godina proizvodnje *</label>
                                <input type="number" name="godinaProizvodnje" id="godinaProizvodnje"
                                       class="form-control @error('godinaProizvodnje') is-invalid @enderror"
                                       value="{{ old('godinaProizvodnje', $ad->vozilo->godinaProizvodnje) }}"
                                       min="1900" max="{{ date('Y') + 1 }}" required>
                                @error('godinaProizvodnje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cena" class="form-label">Cena (€) *</label>
                                <input type="number" name="cena" id="cena"
                                       class="form-control @error('cena') is-invalid @enderror"
                                       value="{{ old('cena', $ad->vozilo->cena) }}" min="0" step="0.01" required>
                                @error('cena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Detalji vozila -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-car me-2"></i>Detalji vozila
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipGoriva" class="form-label">Tip goriva *</label>
                                <select name="tipGoriva" id="tipGoriva" class="form-select @error('tipGoriva') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Izaberite tip goriva</option>
                                    @foreach($fuelTypes as $fuel)
                                        <option value="{{ $fuel }}" {{ old('tipGoriva', $ad->vozilo->tipGoriva) == $fuel ? 'selected' : '' }}>
                                            {{ $fuel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipGoriva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kilometraza" class="form-label">Kilometraža *</label>
                                <input type="text" name="kilometraza" id="kilometraza"
                                       class="form-control @error('kilometraza') is-invalid @enderror"
                                       value="{{ old('kilometraza', $ad->vozilo->kilometraza) }}"
                                       placeholder="npr. 150000 km" required>
                                @error('kilometraza')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipKaroserije" class="form-label">Tip karoserije *</label>
                                <select name="tipKaroserije" id="tipKaroserije" class="form-select @error('tipKaroserije') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Izaberite tip karoserije</option>
                                    @foreach($bodyTypes as $body)
                                        <option value="{{ $body }}" {{ old('tipKaroserije', $ad->vozilo->tipKaroserije) == $body ? 'selected' : '' }}>
                                            {{ $body }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipKaroserije')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stanje" class="form-label">Stanje vozila *</label>
                                <select name="stanje" id="stanje" class="form-select @error('stanje') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Izaberite stanje</option>
                                    @foreach($conditions as $condition)
                                        <option value="{{ $condition }}" {{ old('stanje', $ad->vozilo->stanje) == $condition ? 'selected' : '' }}>
                                            {{ $condition }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('stanje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="snagaMotoraKW" class="form-label">Snaga motora (KW) *</label>
                                <input type="number" name="snagaMotoraKW" id="snagaMotoraKW"
                                       class="form-control @error('snagaMotoraKW') is-invalid @enderror"
                                       value="{{ old('snagaMotoraKW', $ad->vozilo->snagaMotoraKW) }}" min="0" step="0.1" required>
                                @error('snagaMotoraKW')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kubikaza" class="form-label">Kubikaža (cc) *</label>
                                <input type="number" name="kubikaza" id="kubikaza"
                                       class="form-control @error('kubikaza') is-invalid @enderror"
                                       value="{{ old('kubikaza', $ad->vozilo->kubikaza) }}" min="0" required>
                                @error('kubikaza')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dodatne karakteristike -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cogs me-2"></i>Dodatne karakteristike
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipMenjaca" class="form-label">Tip menjača *</label>
                                <select name="tipMenjaca" id="tipMenjaca" class="form-select @error('tipMenjaca') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Izaberite tip menjača</option>
                                    @foreach($transmissionTypes as $transmission)
                                        <option value="{{ $transmission }}" {{ old('tipMenjaca', $ad->vozilo->tipMenjaca) == $transmission ? 'selected' : '' }}>
                                            {{ $transmission }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipMenjaca')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="klima" class="form-label">Klima *</label>
                                <select name="klima" id="klima" class="form-select @error('klima') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Izaberite</option>
                                    @php $k=old('klima', $ad->vozilo->klima); @endphp
                                    <option value="Da" {{ $k=='Da' ? 'selected' : '' }}>Da</option>
                                    <option value="Ne" {{ $k=='Ne' ? 'selected' : '' }}>Ne</option>
                                </select>
                                @error('klima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="euroNorma" class="form-label">Euro norma *</label>
                                <select name="euroNorma" id="euroNorma" class="form-select @error('euroNorma') is-invalid @enderror" required>
                                    <option value="" disabled hidden>Izaberite Euro normu</option>
                                    @foreach($euroStandards as $euro)
                                        <option value="{{ $euro }}" {{ old('euroNorma', $ad->vozilo->euroNorma) == $euro ? 'selected' : '' }}>
                                            {{ $euro }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('euroNorma')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ostecenje" class="form-label">Oštećenje *</label>
                                <select name="ostecenje" id="ostecenje" class="form-select @error('ostecenje') is-invalid @enderror" required>
                                    @php $o=old('ostecenje', $ad->vozilo->ostecenje ? '1' : '0'); @endphp
                                    <option value="0" {{ $o=='0' ? 'selected' : '' }}>Ne</option>
                                    <option value="1" {{ $o=='1' ? 'selected' : '' }}>Da</option>
                                </select>
                                @error('ostecenje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Lokacija i opis -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Lokacija i opis
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lokacija" class="form-label">Lokacija *</label>
                                <input type="text" name="lokacija" id="lokacija"
                                       class="form-control @error('lokacija') is-invalid @enderror"
                                       value="{{ old('lokacija', $ad->vozilo->lokacija) }}"
                                       placeholder="npr. Beograd, Novi Sad" required>
                                @error('lokacija')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Datum isteka je automatski postavljen prilikom kreiranja -->

                            <div class="col-12 mb-3">
                                <label for="opis" class="form-label">Opis vozila *</label>
                                <textarea name="opis" id="opis" rows="4"
                                          class="form-control @error('opis') is-invalid @enderror"
                                          placeholder="Detaljno opišite vozilo..." required>{{ old('opis', $ad->vozilo->opis) }}</textarea>
                                @error('opis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Slike -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-images me-2"></i>Slike vozila
                                </h5>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="images" class="form-label">Dodaj nove slike (opciono)</label>
                                <input type="file" name="images[]" id="images"
                                       class="form-control @error('images.*') is-invalid @enderror"
                                       multiple accept="image/*">
                                <div class="form-text">Možete dodati više slika odjednom. Maksimalna veličina: 2MB po slici.</div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @php $existing = $ad->vozilo->slike ?? []; @endphp
                            @if(is_array($existing) && count($existing))
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach($existing as $path)
                                            <img src="{{ is_string($path) && str_starts_with($path, 'data:') ? $path : Storage::url($path) }}" class="rounded" style="height:100px;object-fit:cover;" alt="slika vozila">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Status oglasa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-star me-2"></i>Status oglasa
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="statusOglasa" class="form-label">Tip oglasa *</label>
                                <select name="statusOglasa" id="statusOglasa" class="form-select @error('statusOglasa') is-invalid @enderror" required>
                                    @foreach($adStatuses as $status)
                                        <option value="{{ $status }}" {{ old('statusOglasa', $ad->statusOglasa) == $status ? 'selected' : '' }}>
                                            {{ $status === 'standardniOglas' ? 'Standardni oglas' : 'Istaknuti oglas (€30)' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('statusOglasa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Nazad
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Sačuvaj izmene
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function loadModelsForBrand(brand, preselect) {
        const modelSelect = document.getElementById('model');
        if (!brand) {
            modelSelect.innerHTML = '<option value="" selected disabled hidden>Prvo izaberite marku</option>';
            modelSelect.disabled = true;
            return;
        }
        modelSelect.disabled = true;
        modelSelect.innerHTML = '<option value="" selected disabled hidden>Učitavanje...</option>';
        try {
            const endpoint = '{{ url('/vehicles/models') }}';
            const res = await fetch(`${endpoint}/${encodeURIComponent(brand)}`);
            const models = await res.json();
            if (Array.isArray(models) && models.length > 0) {
                let options = '<option value="" disabled hidden>Izaberite model</option>';
                models.forEach(m => {
                    const sel = preselect && preselect === m ? 'selected' : '';
                    options += `<option value="${m}" ${sel}>${m}</option>`;
                });
                modelSelect.innerHTML = options;
            } else {
                modelSelect.innerHTML = '<option value="" selected disabled hidden>Nema dostupnih modela</option>';
            }
            modelSelect.disabled = false;
        } catch (e) {
            modelSelect.innerHTML = '<option value="" selected disabled hidden>Nije moguće učitati modele</option>';
            modelSelect.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const brandSelect = document.getElementById('marka');
        const currentBrand = '{{ addslashes(old('marka', $ad->vozilo->marka)) }}';
        const currentModel = '{{ addslashes(old('model', $ad->vozilo->model)) }}';
        loadModelsForBrand(currentBrand, currentModel);
        if (brandSelect) {
            brandSelect.addEventListener('change', function() {
                loadModelsForBrand(this.value, null);
            });
        }
    });
</script>
@endpush
@endsection

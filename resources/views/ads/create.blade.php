@extends('layouts.app')

@section('title', 'Dodaj novi oglas - Auto Plac')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Dodaj novi oglas
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Osnovne informacije
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="marka" class="form-label">Marka vozila *</label>
                                <select name="marka" id="marka" class="form-select @error('marka') is-invalid @enderror" required>
                                    @php $oldBrand = old('marka'); @endphp
                                    <option value="" {{ $oldBrand ? '' : 'selected' }} disabled hidden>Izaberite marku</option>
                                    @php
                                        $fallbackBrands = [
                                            'Audi','BMW','Mercedes-Benz','Volkswagen','Opel','Ford','Toyota','Honda','Nissan','Renault',
                                            'Peugeot','Citroën','Škoda','SEAT','Hyundai','Kia','Mazda','Subaru','Volvo','Fiat',
                                            'Alfa Romeo','Jeep','Land Rover','Porsche','Tesla','Dacia','Mitsubishi','Mini','Suzuki'
                                        ];
                                        $brandList = (isset($brands) && count($brands)) ? $brands : $fallbackBrands;
                                    @endphp
                                    @foreach($brandList as $brand)
                                        <option value="{{ $brand }}" {{ old('marka') == $brand ? 'selected' : '' }}>
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
                                <select name="model" id="model" class="form-select @error('model') is-invalid @enderror" required disabled>
                                    <option value="" selected disabled hidden>Prvo izaberite marku</option>
                                </select>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="godinaProizvodnje" class="form-label">Godina proizvodnje *</label>
                                <input type="number" name="godinaProizvodnje" id="godinaProizvodnje" 
                                       class="form-control @error('godinaProizvodnje') is-invalid @enderror" 
                                       value="{{ old('godinaProizvodnje') }}" 
                                       min="1900" max="{{ date('Y') + 1 }}" required>
                                @error('godinaProizvodnje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cena" class="form-label">Cena (€) *</label>
                                <input type="number" name="cena" id="cena" 
                                       class="form-control @error('cena') is-invalid @enderror" 
                                       value="{{ old('cena') }}" min="0" step="0.01" required>
                                @error('cena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Vehicle Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-car me-2"></i>Detalji vozila
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipGoriva" class="form-label">Tip goriva *</label>
                                <select name="tipGoriva" id="tipGoriva" 
                                        class="form-select @error('tipGoriva') is-invalid @enderror" required>
                                    <option value="">Izaberite tip goriva</option>
                                    @foreach($fuelTypes as $fuel)
                                        <option value="{{ $fuel }}" {{ old('tipGoriva') == $fuel ? 'selected' : '' }}>
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
                                       value="{{ old('kilometraza') }}" 
                                       placeholder="npr. 150000 km" required>
                                @error('kilometraza')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipKaroserije" class="form-label">Tip karoserije *</label>
                                <select name="tipKaroserije" id="tipKaroserije" 
                                        class="form-select @error('tipKaroserije') is-invalid @enderror" required>
                                    <option value="">Izaberite tip karoserije</option>
                                    @foreach($bodyTypes as $body)
                                        <option value="{{ $body }}" {{ old('tipKaroserije') == $body ? 'selected' : '' }}>
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
                                <select name="stanje" id="stanje" 
                                        class="form-select @error('stanje') is-invalid @enderror" required>
                                    <option value="">Izaberite stanje</option>
                                    @foreach($conditions as $condition)
                                        <option value="{{ $condition }}" {{ old('stanje') == $condition ? 'selected' : '' }}>
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
                                       value="{{ old('snagaMotoraKW') }}" min="0" step="0.1" required>
                                @error('snagaMotoraKW')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kubikaza" class="form-label">Kubikaža (cc) *</label>
                                <input type="number" name="kubikaza" id="kubikaza" 
                                       class="form-control @error('kubikaza') is-invalid @enderror" 
                                       value="{{ old('kubikaza') }}" min="0" required>
                                @error('kubikaza')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cogs me-2"></i>Dodatne karakteristike
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipMenjaca" class="form-label">Tip menjača *</label>
                                <select name="tipMenjaca" id="tipMenjaca" 
                                        class="form-select @error('tipMenjaca') is-invalid @enderror" required>
                                    <option value="">Izaberite tip menjača</option>
                                    @foreach($transmissionTypes as $transmission)
                                        <option value="{{ $transmission }}" {{ old('tipMenjaca') == $transmission ? 'selected' : '' }}>
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
                                    <option value="">Izaberite</option>
                                    <option value="Da" {{ old('klima') == 'Da' ? 'selected' : '' }}>Da</option>
                                    <option value="Ne" {{ old('klima') == 'Ne' ? 'selected' : '' }}>Ne</option>
                                </select>
                                @error('klima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="euroNorma" class="form-label">Euro norma *</label>
                                <select name="euroNorma" id="euroNorma" 
                                        class="form-select @error('euroNorma') is-invalid @enderror" required>
                                    <option value="">Izaberite Euro normu</option>
                                    @foreach($euroStandards as $euro)
                                        <option value="{{ $euro }}" {{ old('euroNorma') == $euro ? 'selected' : '' }}>
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
                                <select name="ostecenje" id="ostecenje" 
                                        class="form-select @error('ostecenje') is-invalid @enderror" required>
                                    <option value="">Izaberite opciju</option>
                                    <option value="0" {{ old('ostecenje') == '0' ? 'selected' : '' }}>Ne</option>
                                    <option value="1" {{ old('ostecenje') == '1' ? 'selected' : '' }}>Da</option>
                                </select>
                                @error('ostecenje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location and Description -->
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
                                       value="{{ old('lokacija') }}" 
                                       placeholder="npr. Beograd, Novi Sad" required>
                                @error('lokacija')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Datum isteka se postavlja automatski (30 dana) -->

                            <div class="col-12 mb-3">
                                <label for="opis" class="form-label">Opis vozila *</label>
                                <textarea name="opis" id="opis" rows="4" 
                                          class="form-control @error('opis') is-invalid @enderror" 
                                          placeholder="Detaljno opišite vozilo..." required>{{ old('opis') }}</textarea>
                                @error('opis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-images me-2"></i>Slike vozila
                                </h5>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="images" class="form-label">Dodaj slike</label>
                                <input type="file" name="images[]" id="images" 
                                       class="form-control @error('images.*') is-invalid @enderror" 
                                       multiple accept="image/*">
                                <div class="form-text">Možete dodati više slika odjednom. Maksimalna veličina: 2MB po slici.</div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ad Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-star me-2"></i>Status oglasa
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="statusOglasa" class="form-label">Tip oglasa *</label>
                                <select name="statusOglasa" id="statusOglasa" 
                                        class="form-select @error('statusOglasa') is-invalid @enderror" required>
                                    <option value="">Izaberite tip oglasa</option>
                                    @foreach($adStatuses as $status)
                                        <option value="{{ $status }}" {{ old('statusOglasa') == $status ? 'selected' : '' }}>
                                            {{ $status === 'standardniOglas' ? 'Standardni oglas' : 'Istaknuti oglas (€30)' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('statusOglasa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Nazad
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Kreiraj oglas
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
    // Helpers
    function setMinExpireDate() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const dateInput = document.getElementById('datumIsteka');
        if (dateInput) {
            dateInput.min = tomorrow.toISOString().split('T')[0];
        }
    }

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
            if (!res.ok) throw new Error('Greška pri učitavanju modela');
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
            console.error(e);
        }
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        setMinExpireDate();

        const brandSelect = document.getElementById('marka');
        const oldBrand = '{{ addslashes(old('marka')) }}';
        const oldModel = '{{ addslashes(old('model')) }}';

        // Load models if old brand exists (validation error return)
        if (oldBrand) {
            loadModelsForBrand(oldBrand, oldModel);
        }

        // Attach listener to brand change
        if (brandSelect) {
            ['change','input'].forEach(evt => {
                brandSelect.addEventListener(evt, function() {
                    loadModelsForBrand(this.value, null);
                });
            });
        }
    });
</script>
@endpush
@endsection

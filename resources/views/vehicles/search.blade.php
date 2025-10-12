@extends('layouts.app')

@section('title', 'Pretraga vozila - Auto Plac')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Search Filters Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-filter me-2"></i>Filteri pretrage</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('vehicles.index') }}" id="searchForm">
                        <!-- Basic Filters -->
                        <div class="mb-3">
                            <label class="form-label">Marka</label>
                            <select name="marka" class="form-select" id="brandSelect">
                                <option value="">Sve marke</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}" {{ request('marka') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <select name="model" class="form-select" id="modelSelect">
                                <option value="">Svi modeli</option>
                                @foreach($models as $model)
                                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                                        {{ $model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tip goriva</label>
                            <select name="tipGoriva" class="form-select">
                                <option value="">Svi tipovi</option>
                                @foreach($fuelTypes as $fuel)
                                    <option value="{{ $fuel }}" {{ request('tipGoriva') == $fuel ? 'selected' : '' }}>
                                        {{ $fuel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tip karoserije</label>
                            <select name="tipKaroserije" class="form-select">
                                <option value="">Svi tipovi</option>
                                @foreach($bodyTypes as $body)
                                    <option value="{{ $body }}" {{ request('tipKaroserije') == $body ? 'selected' : '' }}>
                                        {{ $body }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokacija</label>
                            <input type="text" name="lokacija" class="form-control" 
                                   value="{{ request('lokacija') }}" placeholder="Unesite grad">
                        </div>

                        <!-- Year Range -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Godina od</label>
                                <input type="number" name="godinaOd" class="form-control" 
                                       value="{{ request('godinaOd') }}" min="1900" max="{{ date('Y') + 1 }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Godina do</label>
                                <input type="number" name="godinaDo" class="form-control" 
                                       value="{{ request('godinaDo') }}" min="1900" max="{{ date('Y') + 1 }}">
                            </div>
                        </div>

                        <!-- Condition -->
                        <div class="mb-3">
                            <label class="form-label">Stanje</label>
                            <select name="stanje" class="form-select">
                                <option value="">Sva stanja</option>
                                <option value="Novo" {{ request('stanje') == 'Novo' ? 'selected' : '' }}>Novo</option>
                                <option value="Polovno" {{ request('stanje') == 'Polovno' ? 'selected' : '' }}>Polovno</option>
                            </select>
                        </div>

                        <!-- Advanced Filters -->
                        <div class="accordion" id="advancedFilters">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdvanced">
                                        Napredni filteri
                                    </button>
                                </h2>
                                <div id="collapseAdvanced" class="accordion-collapse collapse" data-bs-parent="#advancedFilters">
                                    <div class="accordion-body">
                                        <!-- Price Range -->
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label class="form-label">Cena od (€)</label>
                                                <input type="number" name="cenaOd" class="form-control" 
                                                       value="{{ request('cenaOd') }}" min="0">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Cena do (€)</label>
                                                <input type="number" name="cenaDo" class="form-control" 
                                                       value="{{ request('cenaDo') }}" min="0">
                                            </div>
                                        </div>

                                        <!-- Engine Displacement -->
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label class="form-label">Kubikaža od (cc)</label>
                                                <input type="number" name="kubikazaOd" class="form-control" 
                                                       value="{{ request('kubikazaOd') }}" min="0">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Kubikaža do (cc)</label>
                                                <input type="number" name="kubikazaDo" class="form-control" 
                                                       value="{{ request('kubikazaDo') }}" min="0">
                                            </div>
                                        </div>

                                        <!-- Mileage -->
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label class="form-label">Kilometraža od (km)</label>
                                                <input type="number" name="kilometrazaOd" class="form-control" 
                                                       value="{{ request('kilometrazaOd') }}" min="0">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Kilometraža do (km)</label>
                                                <input type="number" name="kilometrazaDo" class="form-control" 
                                                       value="{{ request('kilometrazaDo') }}" min="0">
                                            </div>
                                        </div>

                                        <!-- Euro Standard -->
                                        <div class="mb-3">
                                            <label class="form-label">Euro norma</label>
                                            <select name="euroNorma" class="form-select">
                                                <option value="">Sve norme</option>
                                                <option value="Euro 1" {{ request('euroNorma') == 'Euro 1' ? 'selected' : '' }}>Euro 1</option>
                                                <option value="Euro 2" {{ request('euroNorma') == 'Euro 2' ? 'selected' : '' }}>Euro 2</option>
                                                <option value="Euro 3" {{ request('euroNorma') == 'Euro 3' ? 'selected' : '' }}>Euro 3</option>
                                                <option value="Euro 4" {{ request('euroNorma') == 'Euro 4' ? 'selected' : '' }}>Euro 4</option>
                                                <option value="Euro 5" {{ request('euroNorma') == 'Euro 5' ? 'selected' : '' }}>Euro 5</option>
                                                <option value="Euro 6" {{ request('euroNorma') == 'Euro 6' ? 'selected' : '' }}>Euro 6</option>
                                            </select>
                                        </div>

                                        <!-- Transmission -->
                                        <div class="mb-3">
                                            <label class="form-label">Menjač</label>
                                            <select name="tipMenjaca" class="form-select">
                                                <option value="">Svi tipovi</option>
                                                <option value="Manuelni" {{ request('tipMenjaca') == 'Manuelni' ? 'selected' : '' }}>Manuelni</option>
                                                <option value="Automatski" {{ request('tipMenjaca') == 'Automatski' ? 'selected' : '' }}>Automatski</option>
                                                <option value="Poluautomatski" {{ request('tipMenjaca') == 'Poluautomatski' ? 'selected' : '' }}>Poluautomatski</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="mb-3">
                            <label class="form-label">Sortiranje</label>
                            <select name="sort" class="form-select">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Najnoviji</option>
                                <option value="cena" {{ request('sort') == 'cena' ? 'selected' : '' }}>Cena (rastuće)</option>
                                <option value="cena_desc" {{ request('sort') == 'cena_desc' ? 'selected' : '' }}>Cena (opadajuće)</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Pretraži
                            </button>
                            <a href="{{ route('vehicles.search') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i>Resetuj filtere
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Rezultati pretrage</h2>
                <div class="btn-group">
                    <button class="btn btn-outline-secondary active" id="gridView">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="btn btn-outline-secondary" id="listView">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <!-- Results will be loaded here -->
            <div id="searchResults">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Pretražite vozila</h4>
                    <p class="text-muted">Koristite filtere sa leve strane da pronađete vozilo koje tražite</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // AJAX search functionality
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = new URL('{{ route("vehicles.index") }}');
        
        // Add form data to URL
        for (let [key, value] of formData.entries()) {
            if (value) {
                url.searchParams.append(key, value);
            }
        }
        
        // Fetch results
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                document.getElementById('searchResults').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // Brand-Model dependency
    document.getElementById('brandSelect').addEventListener('change', function() {
        const brand = this.value;
        const modelSelect = document.getElementById('modelSelect');
        
        if (brand) {
            fetch(`/vehicles/models/${brand}`)
                .then(response => response.json())
                .then(models => {
                    modelSelect.innerHTML = '<option value="">Svi modeli</option>';
                    models.forEach(model => {
                        modelSelect.innerHTML += `<option value="${model}">${model}</option>`;
                    });
                });
        } else {
            modelSelect.innerHTML = '<option value="">Svi modeli</option>';
        }
    });

    // View toggle
    document.getElementById('gridView').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('listView').classList.remove('active');
        // Add grid view logic
    });

    document.getElementById('listView').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('gridView').classList.remove('active');
        // Add list view logic
    });
</script>
@endpush
@endsection

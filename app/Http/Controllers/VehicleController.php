<?php

namespace App\Http\Controllers;

use App\Models\Vozilo;
use App\Models\Oglas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Show the vehicle search form
     */
    public function search()
    {
        // Use the same option sets as in AdController::create for consistency
        $brands = [
            'Audi','BMW','Mercedes-Benz','Volkswagen','Opel','Ford','Toyota','Honda','Nissan','Renault',
            'Peugeot','Citroën','Škoda','SEAT','Hyundai','Kia','Mazda','Subaru','Volvo','Fiat',
            'Alfa Romeo','Jeep','Land Rover','Porsche','Tesla','Dacia','Mitsubishi','Mini','Suzuki'
        ];
        // Populate initial models list from DB (optional); main list loads dynamically by brand
        $models = Vozilo::select('model')->distinct()->orderBy('model')->pluck('model');
        $fuelTypes = ['Benzin', 'Dizel', 'Hibrid', 'Električno', 'Gas'];
        $bodyTypes = ['Limuzina', 'Hatchback', 'SUV', 'Karavan', 'Kupe', 'Kabriolet', 'Pickup'];
        
        return view('vehicles.search', compact('brands', 'models', 'fuelTypes', 'bodyTypes'));
    }

    /**
     * Show vehicles on home page and search results
     */
    public function index(Request $request)
    {
        // Always show results list for /vehicles; homepage handled separately

        $query = Oglas::with(['vozilo', 'korisnik'])
            ->whereNotIn('statusOglasa', ['deaktiviranOglas', 'istekaoOglas', 'prodatOglas']);

        // Basic filters
        if ($request->filled('marka')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('marka', $request->marka);
            });
        }

        if ($request->filled('model')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('model', $request->model);
            });
        }

        if ($request->filled('tipGoriva')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('tipGoriva', $request->tipGoriva);
            });
        }

        if ($request->filled('tipKaroserije')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('tipKaroserije', $request->tipKaroserije);
            });
        }

        if ($request->filled('lokacija')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('lokacija', 'like', '%' . $request->lokacija . '%');
            });
        }

        // Year filters
        if ($request->filled('godinaOd')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('godinaProizvodnje', '>=', $request->godinaOd);
            });
        }

        if ($request->filled('godinaDo')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('godinaProizvodnje', '<=', $request->godinaDo);
            });
        }

        // New/Used filter
        if ($request->filled('stanje')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('stanje', $request->stanje);
            });
        }

        // Advanced filters
        if ($request->filled('cenaOd')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('cena', '>=', $request->cenaOd);
            });
        }

        if ($request->filled('cenaDo')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('cena', '<=', $request->cenaDo);
            });
        }

        if ($request->filled('kubikazaOd')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('kubikaza', '>=', $request->kubikazaOd);
            });
        }

        if ($request->filled('kubikazaDo')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('kubikaza', '<=', $request->kubikazaDo);
            });
        }

        if ($request->filled('kilometrazaOd')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->whereRaw('CAST(REPLACE(kilometraza, " km", "") AS UNSIGNED) >= ?', [$request->kilometrazaOd]);
            });
        }

        if ($request->filled('kilometrazaDo')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->whereRaw('CAST(REPLACE(kilometraza, " km", "") AS UNSIGNED) <= ?', [$request->kilometrazaDo]);
            });
        }

        if ($request->filled('euroNorma')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('euroNorma', $request->euroNorma);
            });
        }

        if ($request->filled('tipMenjaca')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('tipMenjaca', $request->tipMenjaca);
            });
        }

        // Additional filters to match creation fields
        if ($request->filled('snagaKwOd')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('snagaMotoraKW', '>=', $request->snagaKwOd);
            });
        }

        if ($request->filled('snagaKwDo')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('snagaMotoraKW', '<=', $request->snagaKwDo);
            });
        }

        if ($request->filled('klima')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('klima', $request->klima);
            });
        }

        if ($request->filled('ostecenje')) {
            $query->whereHas('vozilo', function($q) use ($request) {
                $q->where('ostecenje', (bool) intval($request->ostecenje));
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        if (in_array($sortBy, ['cena', 'cena_desc'])) {
            $dir = $sortBy === 'cena_desc' ? 'desc' : 'asc';
            $query->join('vozilo', 'oglas.voziloID', '=', 'vozilo.voziloID')
                  ->orderBy('vozilo.cena', $dir);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // On homepage, avoid showing featured ads again in the general list
        if ($request->routeIs('home')) {
            $query->where('statusOglasa', '!=', 'istaknutiOglas');
        }

        $vehicles = $query->paginate(12);

        // Get filter options for the view - use the same sets as creation form
        $brands = [
            'Audi','BMW','Mercedes-Benz','Volkswagen','Opel','Ford','Toyota','Honda','Nissan','Renault',
            'Peugeot','Citroën','Škoda','SEAT','Hyundai','Kia','Mazda','Subaru','Volvo','Fiat',
            'Alfa Romeo','Jeep','Land Rover','Porsche','Tesla','Dacia','Mitsubishi','Mini','Suzuki'
        ];
        $models = Vozilo::select('model')->distinct()->orderBy('model')->pluck('model');
        $fuelTypes = ['Benzin', 'Dizel', 'Hibrid', 'Električno', 'Gas'];
        $bodyTypes = ['Limuzina', 'Hatchback', 'SUV', 'Karavan', 'Kupe', 'Kabriolet', 'Pickup'];

        if ($request->ajax()) {
            return view('vehicles.partials.results', compact('vehicles'));
        }

        // Non-AJAX: homepage shows homepage view; /vehicles shows search + results
        if ($request->routeIs('home')) {
            return view('vehicles.index', compact('vehicles', 'brands', 'models', 'fuelTypes', 'bodyTypes'));
        }

        return view('vehicles.search', compact('vehicles', 'brands', 'models', 'fuelTypes', 'bodyTypes'));
    }

    /**
     * Show detailed vehicle page
     */
    public function show($id)
    {
        // Load ad regardless of status; restrict visibility for non-admins below
        $oglas = Oglas::with(['vozilo', 'korisnik', 'uplate'])
            ->where('oglasID', $id)
            ->firstOrFail();

        // If ad is deactivated or expired, only admins can view it
        if (in_array($oglas->statusOglasa, ['deaktiviranOglas', 'istekaoOglas'])) {
            if (!(Auth::check() && Auth::user()->tipKorisnika === 'admin')) {
                abort(404);
            }
        }

        // Get related vehicles (same brand/model)
        $relatedVehicles = Oglas::with(['vozilo', 'korisnik'])
            ->where('oglasID', '!=', $id)
            ->whereNotIn('statusOglasa', ['deaktiviranOglas', 'istekaoOglas'])
            ->whereHas('vozilo', function($q) use ($oglas) {
                $q->where('marka', $oglas->vozilo->marka)
                  ->where('model', $oglas->vozilo->model);
            })
            ->limit(4)
            ->get();

        return view('vehicles.show', compact('oglas', 'relatedVehicles'));
    }

    /**
     * Get models for a specific brand (AJAX)
     */
    public function getModels($brand)
    {
        $models = Vozilo::where('marka', $brand)
            ->select('model')
            ->distinct()
            ->orderBy('model')
            ->pluck('model')
            ->toArray();

        // Static fallback if DB has no entries for the selected brand
        if (empty($models)) {
            $fallback = [
                'Audi' => ['A1','A3','A4','A6','Q3','Q5','Q7'],
                'BMW' => ['1 Series','3 Series','5 Series','X1','X3','X5'],
                'Mercedes-Benz' => ['A-Class','C-Class','E-Class','GLA','GLC','GLE'],
                'Volkswagen' => ['Polo','Golf','Passat','Tiguan','Touareg'],
                'Opel' => ['Corsa','Astra','Insignia','Mokka','Crossland'],
                'Ford' => ['Fiesta','Focus','Mondeo','Kuga','Puma'],
                'Toyota' => ['Yaris','Corolla','Camry','RAV4','C-HR'],
                'Honda' => ['Jazz','Civic','Accord','HR-V','CR-V'],
                'Nissan' => ['Micra','Qashqai','Juke','X-Trail'],
                'Renault' => ['Clio','Megane','Captur','Kadjar'],
                'Peugeot' => ['208','308','3008','5008'],
                'Citroën' => ['C3','C4','C5 Aircross'],
                'Škoda' => ['Fabia','Octavia','Superb','Karoq','Kodiaq'],
                'SEAT' => ['Ibiza','Leon','Arona','Ateca'],
                'Hyundai' => ['i20','i30','Tucson','Santa Fe'],
                'Kia' => ['Rio','Ceed','Sportage','Sorento'],
                'Mazda' => ['Mazda2','Mazda3','CX-3','CX-5'],
                'Subaru' => ['Impreza','Forester','Outback'],
                'Volvo' => ['S60','S90','XC40','XC60','XC90'],
                'Fiat' => ['Panda','500','Tipo'],
                'Alfa Romeo' => ['Giulietta','Giulia','Stelvio'],
                'Jeep' => ['Renegade','Compass','Cherokee'],
                'Land Rover' => ['Range Rover Evoque','Discovery Sport','Defender'],
                'Porsche' => ['Macan','Cayenne','Panamera','911'],
                'Tesla' => ['Model 3','Model S','Model X','Model Y'],
                'Dacia' => ['Sandero','Logan','Duster'],
                'Mitsubishi' => ['ASX','Outlander','Lancer'],
                'Mini' => ['Hatch','Clubman','Countryman'],
                'Suzuki' => ['Swift','Vitara','S-Cross'],
            ];

            $models = $fallback[$brand] ?? [];
        }

        return response()->json($models);
    }
}
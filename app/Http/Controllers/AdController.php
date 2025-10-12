<?php

namespace App\Http\Controllers;

use App\Models\Oglas;
use App\Models\Vozilo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of user's ads
     */
    public function index()
    {
        $ads = Oglas::with(['vozilo'])
            ->where('korisnikID', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new ad
     */
    public function create()
    {
        $brands = [
            'Audi','BMW','Mercedes-Benz','Volkswagen','Opel','Ford','Toyota','Honda','Nissan','Renault',
            'Peugeot','Citroën','Škoda','SEAT','Hyundai','Kia','Mazda','Subaru','Volvo','Fiat',
            'Alfa Romeo','Jeep','Land Rover','Porsche','Tesla','Dacia','Mitsubishi','Mini','Suzuki'
        ];
        $fuelTypes = ['Benzin', 'Dizel', 'Hibrid', 'Električno', 'Gas'];
        $bodyTypes = ['Limuzina', 'Hatchback', 'SUV', 'Karavan', 'Kupe', 'Kabriolet', 'Pickup'];
        $transmissionTypes = ['Manuelni', 'Automatski', 'Poluautomatski'];
        $euroStandards = ['Euro 1', 'Euro 2', 'Euro 3', 'Euro 4', 'Euro 5', 'Euro 6'];
        $conditions = ['Novo', 'Polovno'];
        $adStatuses = ['standardniOglas', 'istaknutiOglas'];

        return view('ads.create', compact('brands', 'fuelTypes', 'bodyTypes', 'transmissionTypes', 'euroStandards', 'conditions', 'adStatuses'));
    }

    /**
     * Store a newly created ad
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'marka' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'godinaProizvodnje' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cena' => 'required|numeric|min:0',
            'tipGoriva' => 'required|string',
            'kilometraza' => 'required|string',
            'tipKaroserije' => 'required|string',
            'snagaMotoraKW' => 'required|numeric|min:0',
            'stanje' => 'required|string',
            'opis' => 'required|string|min:10',
            'lokacija' => 'required|string|max:255',
            'klima' => 'required|string',
            'tipMenjaca' => 'required|string',
            'ostecenje' => 'required|boolean',
            'euroNorma' => 'required|string',
            'kubikaza' => 'required|integer|min:0',
            'statusOglasa' => 'required|string',
            // datumIsteka se postavlja automatski
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'marka.required' => 'Marka vozila je obavezna.',
            'model.required' => 'Model vozila je obavezan.',
            'godinaProizvodnje.required' => 'Godina proizvodnje je obavezna.',
            'cena.required' => 'Cena je obavezna.',
            'tipGoriva.required' => 'Tip goriva je obavezan.',
            'kilometraza.required' => 'Kilometraža je obavezna.',
            'tipKaroserije.required' => 'Tip karoserije je obavezan.',
            'opis.required' => 'Opis je obavezan.',
            'opis.min' => 'Opis mora imati najmanje 10 karaktera.',
            'lokacija.required' => 'Lokacija je obavezna.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Wallet check for featured ad on create
        if ($request->statusOglasa === 'istaknutiOglas') {
            $required = 150.00;
            if (Auth::user()->trenutniNovac < $required) {
                return redirect()->back()
                    ->withErrors(['statusOglasa' => 'Nemate dovoljno sredstava u novčaniku za istaknuti oglas (potrebno €150).'])
                    ->withInput();
            }
        }

        // Handle image uploads: store as base64 strings in DB
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $mime = $image->getMimeType();
                    $data = base64_encode(file_get_contents($image->getRealPath()));
                    $imagePaths[] = "data:" . $mime . ";base64," . $data;
                }
            }
        }

        // Create vehicle
        $vozilo = Vozilo::create([
            'marka' => $request->marka,
            'model' => $request->model,
            'godinaProizvodnje' => $request->godinaProizvodnje,
            'cena' => $request->cena,
            'tipGoriva' => $request->tipGoriva,
            'kilometraza' => $request->kilometraza,
            'tipKaroserije' => $request->tipKaroserije,
            'snagaMotoraKW' => $request->snagaMotoraKW,
            'stanje' => $request->stanje,
            'opis' => $request->opis,
            'slike' => $imagePaths,
            'lokacija' => $request->lokacija,
            'klima' => $request->klima,
            'tipMenjaca' => $request->tipMenjaca,
            'ostecenje' => $request->ostecenje,
            'euroNorma' => $request->euroNorma,
            'kubikaza' => $request->kubikaza,
        ]);

        // Create ad
        $cenaIstaknutogOglasa = $request->statusOglasa === 'istaknutiOglas' ? 150.00 : 0.00;
        $datumIsteka = now()->addDays(30);
        
        $oglas = Oglas::create([
            'datumKreiranja' => now(),
            'datumIsteka' => $datumIsteka,
            'cenaIstaknutogOglasa' => $cenaIstaknutogOglasa,
            'voziloID' => $vozilo->voziloID,
            'korisnikID' => Auth::id(),
            'statusOglasa' => $request->statusOglasa,
        ]);

        return redirect()->route('ads.show', $oglas->oglasID)
            ->with('success', 'Oglas je uspešno kreiran!');
    }

    /**
     * Display the specified ad
     */
    public function show($id)
    {
        $ad = Oglas::with(['vozilo', 'korisnik', 'uplate'])
            ->where('oglasID', $id)
            ->firstOrFail();

        // Check if user can view this ad
        $isAdmin = Auth::user() && (Auth::user()->tipKorisnika === 'admin');
        if ($ad->korisnikID !== Auth::id() && !$isAdmin) {
            if ($ad->statusOglasa === 'deaktiviranOglas') {
                abort(404);
            }
        }

        return view('ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified ad
     */
    public function edit($id)
    {
        $ad = Oglas::with(['vozilo'])
            ->where('oglasID', $id)
            ->where('korisnikID', Auth::id())
            ->firstOrFail();

        $brands = [
            'Audi','BMW','Mercedes-Benz','Volkswagen','Opel','Ford','Toyota','Honda','Nissan','Renault',
            'Peugeot','Citroën','Škoda','SEAT','Hyundai','Kia','Mazda','Subaru','Volvo','Fiat',
            'Alfa Romeo','Jeep','Land Rover','Porsche','Tesla','Dacia','Mitsubishi','Mini','Suzuki'
        ];
        $fuelTypes = ['Benzin', 'Dizel', 'Hibrid', 'Električno', 'Gas'];
        $bodyTypes = ['Limuzina', 'Hatchback', 'SUV', 'Karavan', 'Kupe', 'Kabriolet', 'Pickup'];
        $transmissionTypes = ['Manuelni', 'Automatski', 'Poluautomatski'];
        $euroStandards = ['Euro 1', 'Euro 2', 'Euro 3', 'Euro 4', 'Euro 5', 'Euro 6'];
        $conditions = ['Novo', 'Polovno'];
        $adStatuses = ['standardniOglas', 'istaknutiOglas'];

        return view('ads.edit', compact('ad', 'brands', 'fuelTypes', 'bodyTypes', 'transmissionTypes', 'euroStandards', 'conditions', 'adStatuses'));
    }

    /**
     * Update the specified ad
     */
    public function update(Request $request, $id)
    {
        $ad = Oglas::with(['vozilo'])
            ->where('oglasID', $id)
            ->where('korisnikID', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'marka' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'godinaProizvodnje' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cena' => 'required|numeric|min:0',
            'tipGoriva' => 'required|string',
            'kilometraza' => 'required|string',
            'tipKaroserije' => 'required|string',
            'snagaMotoraKW' => 'required|numeric|min:0',
            'stanje' => 'required|string',
            'opis' => 'required|string|min:10',
            'lokacija' => 'required|string|max:255',
            'klima' => 'required|string',
            'tipMenjaca' => 'required|string',
            'ostecenje' => 'required|boolean',
            'euroNorma' => 'required|string',
            'kubikaza' => 'required|integer|min:0',
            'statusOglasa' => 'required|string',
            // datumIsteka se postavlja automatski prilikom kreiranja; može se produžiti logikom po potrebi
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image uploads: store as base64 strings in DB
        $imagePaths = $ad->vozilo->slike ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $mime = $image->getMimeType();
                    $data = base64_encode(file_get_contents($image->getRealPath()));
                    $imagePaths[] = "data:" . $mime . ";base64," . $data;
                }
            }
        }

        // Update vehicle
        $ad->vozilo->update([
            'marka' => $request->marka,
            'model' => $request->model,
            'godinaProizvodnje' => $request->godinaProizvodnje,
            'cena' => $request->cena,
            'tipGoriva' => $request->tipGoriva,
            'kilometraza' => $request->kilometraza,
            'tipKaroserije' => $request->tipKaroserije,
            'snagaMotoraKW' => $request->snagaMotoraKW,
            'stanje' => $request->stanje,
            'opis' => $request->opis,
            'slike' => $imagePaths,
            'lokacija' => $request->lokacija,
            'klima' => $request->klima,
            'tipMenjaca' => $request->tipMenjaca,
            'ostecenje' => $request->ostecenje,
            'euroNorma' => $request->euroNorma,
            'kubikaza' => $request->kubikaza,
        ]);

        // Update ad
        $cenaIstaknutogOglasa = $request->statusOglasa === 'istaknutiOglas' ? 150.00 : 0.00;
        
        $ad->update([
            'cenaIstaknutogOglasa' => $cenaIstaknutogOglasa,
            'statusOglasa' => $request->statusOglasa,
        ]);

        return redirect()->route('ads.show', $ad->oglasID)
            ->with('success', 'Oglas je uspešno ažuriran!');
    }

    /**
     * Remove the specified ad
     */
    public function destroy($id)
    {
        $ad = Oglas::where('oglasID', $id)
            ->where('korisnikID', Auth::id())
            ->firstOrFail();

        $ad->update(['statusOglasa' => 'deaktiviranOglas']);

        return redirect()->route('ads.index')
            ->with('success', 'Oglas je uspešno deaktiviran!');
    }

    /**
     * Show featured ads
     */
    public function featured()
    {
        $ads = Oglas::with(['vozilo', 'korisnik'])
            ->where('statusOglasa', 'istaknutiOglas')
            ->where('korisnikID', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('ads.featured', compact('ads'));
    }
}
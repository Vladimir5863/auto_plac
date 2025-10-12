<?php

namespace App\Http\Controllers;

use App\Models\Uplata;
use App\Models\Oglas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of payments
     */
    public function index()
    {
        $user = Auth::user();
        $query = Uplata::with(['korisnik', 'oglas.vozilo'])
            ->orderBy('datumUplate', 'desc');

        if (!($user && $user->tipKorisnika === 'admin')) {
            $query->where('fromUserID', $user->id);
        }

        $payments = $query->paginate(15);

        $totalQuery = Uplata::query();
        $monthlyQuery = Uplata::whereMonth('datumUplate', now()->month)
            ->whereYear('datumUplate', now()->year);
        if (!($user && $user->tipKorisnika === 'admin')) {
            $totalQuery->where('fromUserID', $user->id);
            $monthlyQuery->where('fromUserID', $user->id);
        }
        $totalAmount = $totalQuery->sum('iznos');
        $monthlyAmount = $monthlyQuery->sum('iznos');

        return view('payments.index', compact('payments', 'totalAmount', 'monthlyAmount'));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        $userAds = Auth::user()->oglasi()
            ->with(['vozilo'])
            ->where('statusOglasa', '!=', 'deaktiviranOglas')
            ->get();

        return view('payments.create', compact('userAds'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        // Top-up korisničkog novčanika (forma može sadržati referencu, ali iznos je već u polju)
        $validator = Validator::make($request->all(), [
            'iznos' => 'required|numeric|min:0.01',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Uvećaj novčanik i evidentiraj uplatu (wallet top-up)
        $user = Auth::user();
        $user->increment('trenutniNovac', $request->iznos);

        Uplata::create([
            'fromUserID' => $user->id,
            'toUserID' => null,
            'toOglasID' => null,
            'datumUplate' => now(),
            'iznos' => $request->iznos,
            'tip' => 'wallet',
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Sredstva su dodata na vaš novčanik.');
    }

    /**
     * Display the specified payment
     */
    public function show($id)
    {
        $payment = Uplata::with(['korisnik', 'oglas.vozilo'])
            ->where('uplataID', $id)
            ->firstOrFail();

        // Check if user can view this payment
        $user = Auth::user();
        $isAdmin = $user && $user->tipKorisnika === 'admin';
        if (!$isAdmin && $payment->fromUserID !== $user->id) abort(403, 'Nemate dozvolu da vidite ovu uplatu.');

        return view('payments.show', compact('payment'));
    }

    /**
     * Show payment form for specific ad
     */
    public function createForAd($adId)
    {
        $ad = Oglas::with(['vozilo'])
            ->where('oglasID', $adId)
            ->where('korisnikID', Auth::id())
            ->firstOrFail();

        return view('payments.create-for-ad', compact('ad'));
    }

    /**
     * Process payment for specific ad
     */
    public function processForAd(Request $request, $adId)
    {
        $ad = Oglas::where('oglasID', $adId)
            ->where('korisnikID', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'iznos' => 'required|numeric|min:0.01',
            'brojRacuna' => 'required|string|max:255',
        ], [
            'iznos.required' => 'Iznos je obavezan.',
        ]);

        // Fiksna cena isticanja
        $required = 30.00;
        $user = Auth::user();

        $payment = Uplata::create([
            'fromUserID' => $user->id,
            'toUserID' => null,
            'toOglasID' => $adId,
            'datumUplate' => now(),
            'iznos' => $required,
            'tip' => 'featured',
        ]);

        // Istakni oglas
        $ad->update([
            'statusOglasa' => 'istaknutiOglas',
            'cenaIstaknutogOglasa' => $required,
        ]);

        return redirect()->route('ads.show', $adId)
            ->with('success', 'Oglas je uspešno istaknut. Iznos je skinut sa vašeg novčanika.');
    }

    /**
     * Purchase a vehicle (ad)
     */
    public function purchase(Request $request, $adId)
    {
        $buyer = Auth::user();
        $ad = Oglas::with(['vozilo'])
            ->where('oglasID', $adId)
            ->firstOrFail();

        // Validacije
        if (in_array($ad->statusOglasa, ['deaktiviranOglas', 'istekaoOglas', 'prodatOglas'])) {
            return redirect()->back()->with('error', 'Oglas nije dostupan za kupovinu.');
        }
        if ($ad->korisnikID === $buyer->id) {
            return redirect()->back()->with('error', 'Ne možete kupiti sopstveni oglas.');
        }

        // Validacija broja računa; ne čuvamo ga u bazi
        $request->validate([
            'brojRacuna' => 'required|string|max:255',
        ]);

        $price = (float) $ad->vozilo->cena;

        // Evidentiraj uplatu (bez promena "novčanika")
        Uplata::create([
            'fromUserID' => $buyer->id,
            'toUserID' => $ad->korisnikID,
            'toOglasID' => $ad->oglasID,
            'datumUplate' => now(),
            'iznos' => $price,
            'tip' => 'purchase',
        ]);

        // Obeleži oglas kao prodat
        $ad->update(['statusOglasa' => 'prodatOglas']);

        return redirect()->route('vehicles.show', $ad->oglasID)
            ->with('success', 'Uspešno ste kupili vozilo.');
    }

    /**
     * Show purchase form (account number + fixed price)
     */
    public function createPurchase($adId)
    {
        $ad = Oglas::with(['vozilo', 'korisnik'])
            ->where('oglasID', $adId)
            ->firstOrFail();

        if (in_array($ad->statusOglasa, ['deaktiviranOglas', 'istekaoOglas', 'prodatOglas'])) {
            abort(404);
        }

        return view('payments.purchase', compact('ad'));
    }

    /**
     * Show user's payments
     */
    public function myPayments()
    {
        $payments = Auth::user()->uplate()
            ->with(['oglas.vozilo'])
            ->orderBy('datumUplate', 'desc')
            ->paginate(10);

        $totalAmount = Auth::user()->uplate()->sum('iznos');

        return view('payments.my-payments', compact('payments', 'totalAmount'));
    }
}
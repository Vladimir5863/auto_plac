<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user profile
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's ads count
        $adsCount = $user->oglasi()->count();
        $activeAdsCount = $user->oglasi()->where('statusOglasa', '!=', 'deaktiviranOglas')->count();
        $featuredAdsCount = $user->oglasi()->where('statusOglasa', 'istaknutiOglas')->count();
        
        // Wallet and recent payments
        $walletBalance = $user->trenutniNovac ?? 0;
        $recentPayments = $user->uplate()->latest()->limit(5)->get();
        
        return view('profile.index', compact('user', 'adsCount', 'activeAdsCount', 'featuredAdsCount', 'walletBalance', 'recentPayments'));
    }

    /**
     * Show the form for editing user profile
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'korisnickoIme' => 'required|string|max:255|unique:users,korisnickoIme,' . $user->id,
            'eMail' => 'required|string|email|max:255|unique:users,eMail,' . $user->id,
            'brojTelefona' => 'required|string|max:20',
        ], [
            'korisnickoIme.required' => 'Korisničko ime je obavezno.',
            'korisnickoIme.unique' => 'Korisničko ime već postoji.',
            'eMail.required' => 'Email je obavezan.',
            'eMail.email' => 'Email mora biti u ispravnom formatu.',
            'eMail.unique' => 'Email već postoji.',
            'brojTelefona.required' => 'Broj telefona je obavezan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'korisnickoIme' => $request->korisnickoIme,
            'eMail' => $request->eMail,
            'brojTelefona' => $request->brojTelefona,
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Profil je uspešno ažuriran!');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Trenutna lozinka je obavezna.',
            'password.required' => 'Nova lozinka je obavezna.',
            'password.min' => 'Nova lozinka mora imati najmanje 8 karaktera.',
            'password.confirmed' => 'Potvrda lozinke se ne poklapa.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        if (!Hash::check($request->current_password, $user->lozinka)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Trenutna lozinka nije ispravna.']);
        }

        $user->update([
            'lozinka' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Lozinka je uspešno promenjena!');
    }

    /**
     * Show user's ads
     */
    public function myAds()
    {
        $ads = Auth::user()->oglasi()
            ->with(['vozilo'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.my-ads', compact('ads'));
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

        return view('profile.my-payments', compact('payments', 'totalAmount'));
    }

    /**
     * Show vehicles purchased by the authenticated user
     */
    public function purchases()
    {
        $user = Auth::user();
        $purchases = \App\Models\Uplata::with(['oglas.vozilo', 'oglas.korisnik'])
            ->where('fromUserID', $user->id)
            ->where('tip', 'purchase')
            ->orderBy('datumUplate', 'desc')
            ->paginate(10);

        return view('profile.purchased', compact('purchases'));
    }
}
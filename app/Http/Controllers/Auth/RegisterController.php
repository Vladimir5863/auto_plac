<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'korisnickoIme' => 'required|string|max:255|unique:users,korisnickoIme',
            'eMail' => 'required|string|email|max:255|unique:users,eMail',
            'brojTelefona' => 'required|string|max:20',
            'lozinka' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'korisnickoIme.required' => 'Korisničko ime je obavezno.',
            'korisnickoIme.unique' => 'Korisničko ime već postoji.',
            'eMail.required' => 'Email je obavezan.',
            'eMail.email' => 'Email mora biti u ispravnom formatu.',
            'eMail.unique' => 'Email već postoji.',
            'brojTelefona.required' => 'Broj telefona je obavezan.',
            'lozinka.required' => 'Lozinka je obavezna.',
            'lozinka.confirmed' => 'Potvrda lozinke se ne poklapa.',
            'lozinka.min' => 'Lozinka mora imati najmanje 8 karaktera.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('lozinka', 'lozinka_confirmation'));
        }

        $user = User::create([
            'korisnickoIme' => $request->korisnickoIme,
            'eMail' => $request->eMail,
            'brojTelefona' => $request->brojTelefona,
            'lozinka' => Hash::make($request->lozinka),
            'tipKorisnika' => 'korisnik',
        ]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Uspešno ste registrovani! Dobrodošli!');
    }
}
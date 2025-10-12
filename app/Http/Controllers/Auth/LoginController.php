<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'korisnickoIme' => 'required|string',
            'lozinka' => 'required|string|min:6',
        ], [
            'korisnickoIme.required' => 'Korisničko ime je obavezno.',
            'lozinka.required' => 'Lozinka je obavezna.',
            'lozinka.min' => 'Lozinka mora imati najmanje 6 karaktera.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('lozinka'));
        }

        // Normalize inputs
        $request->merge([
            'korisnickoIme' => trim((string)$request->korisnickoIme),
            'lozinka' => (string)$request->lozinka,
        ]);
        $credentials = $request->only('korisnickoIme', 'lozinka');
        
        // If user is soft-deleted (banned), show explicit message
        $bannedUser = User::withTrashed()->where('korisnickoIme', $request->korisnickoIme)->first();
        if ($bannedUser && $bannedUser->trashed()) {
            return redirect()->back()
                ->withErrors(['korisnickoIme' => 'Vaš nalog je banovan. Obratite se podršci.'])
                ->withInput($request->except('lozinka'));
        }
        $remember = $request->boolean('remember');

        // Manual auth against 'lozinka' column; support username or email
        $user = User::where('korisnickoIme', $request->korisnickoIme)
            ->orWhere('eMail', $request->korisnickoIme)
            ->first();
        if ($user && Hash::check($request->lozinka, $user->lozinka)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('home'))
                ->with('success', 'Uspešno ste se prijavili!');
        }

        throw ValidationException::withMessages([
            'korisnickoIme' => ['Podaci za prijavu nisu ispravni.'],
        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Uspešno ste se odjavili!');
    }
}
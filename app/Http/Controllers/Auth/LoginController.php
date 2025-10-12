<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $credentials = $request->only('korisnickoIme', 'lozinka');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
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
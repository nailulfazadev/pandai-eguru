<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the register form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle registration attempt.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20', // This acts as the password
        ]);

        $plan = $request->input('plan', 'trial');
        $tier = 'trial';
        $endsAt = now()->addDays(7);

        if ($plan === 'early') {
            $tier = 'early';
            $endsAt = now()->addDays(30);
        }

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->phone),
            'subscription_tier' => $tier,
            'subscription_ends_at' => $endsAt,
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Anda mendapatkan Trial 7 Hari untuk mencoba Modul Ajar.');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string', // password in this context is user's phone number
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau Nomor Handphone salah.',
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

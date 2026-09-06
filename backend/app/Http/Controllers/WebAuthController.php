<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isOwner() || $user->isManager()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isCashier()) {
                return redirect()->route('pos.index');
            } elseif ($user->isBarista()) {
                return redirect()->route('kds.index');
            }
            return redirect()->route('landing');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$field => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.']);
            }

            if ($user->isOwner() || $user->isManager()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isCashier()) {
                return redirect()->intended(route('pos.index'));
            } elseif ($user->isBarista()) {
                return redirect()->intended(route('kds.index'));
            }

            return redirect()->intended(route('landing'));
        }

        return back()->withErrors([
            'email' => 'Email/No. HP atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth', ['mode' => 'login']);
    }

    public function registerForm()
    {
        return view('auth', ['mode' => 'register']);
    }

    public function login(Request $r)
    {
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($c + ['is_active' => 1], $r->boolean('remember'))) {
            $r->session()->regenerate();

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Email or password is wrong, or the account is switched off.'])->onlyInput('email');
    }

    public function register(Request $r)
    {
        $d = $r->validate([
            'name' => 'required|max:80', 'business_name' => 'nullable|max:80', 'phone' => 'required|max:20',
            'email' => 'required|email|unique:users', 'password' => 'required|min:8|confirmed',
        ]);
        Auth::login(User::create($d + ['role' => 'owner']));

        return redirect('/dashboard');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/login');
    }
}

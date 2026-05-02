<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Helper to detect if request is from mobile
     */
    private function isMobile(Request $request)
    {
        return preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/i', $request->header('User-Agent'));
    }

    /**
     * Show login form
     */
    public function showLoginForm(Request $request)
    {
        $viewPath = $this->isMobile($request) ? 'mobile.auth.login' : 'web.auth.login';
        return view($viewPath);
    }

    /**
     * Handle login logic
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegisterForm(Request $request)
    {
        $viewPath = $this->isMobile($request) ? 'mobile.auth.register' : 'web.auth.register';
        return view($viewPath);
    }

    /**
     * Handle register logic
     */
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

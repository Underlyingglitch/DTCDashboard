<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function login_post(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::validate($request->only('email', 'password'))) {
            // Check if the user is active
            $user = User::where('email', $request->email)->first();
            if ($user->active) {
                // Log the user in and remember them
                Auth::login($user, true);
                return redirect()->intended(route('dashboard'));
            } else {
                return back()
                    ->withInput($request->except('password'))
                    ->withErrors(['details' => 'Uw account is nog niet geactiveerd.']);
            }
        }

        return back()
            ->withInput($request->except('password'))
            ->withErrors(['details' => 'Ongeldige inloggegevens!']);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function register_post(Request $request)
    {
        $request->merge(['email' => strtolower($request->email)]);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        User::create($request->only('name', 'email', 'password'));

        return redirect()->route('auth.register')->with('success', 'Account aangemaakt! Zodra uw account is geactiveerd ontvangt u bericht per email.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}

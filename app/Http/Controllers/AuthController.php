<?php

namespace App\Http\Controllers;

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

        if (!Auth::attempt($request->only('email', 'password'), true)) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['details' => 'Ongeldige inloggegevens!']);
        }

        return redirect()->intended('/');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function register_post(Request $request)
    {
        // TODO implement logic
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }
}

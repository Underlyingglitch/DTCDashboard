<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
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
                if ($user->is_jury) $user->assignRole('jury');
                else $user->removeRole('jury');
                if ($user->is_trainer) $user->assignRole('trainer');
                else $user->removeRole('trainer');
                // Log the user in and remember them
                Auth::login($user, true);
                $user = Auth::user();
                $user->locked = false;
                $user->save();
                $redirect = Device::where('ip', $request->ip())->first() ? 'jurytafel.index' : 'dashboard';
                return redirect()->intended(route($redirect));
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

    public function login_as(Request $request)
    {
        $device = Device::where('ip', $request->ip())->first();
        $user = User::find($device->authenticated_user_id);
        if (!$user) return redirect()->route('auth.login');
        $user->locked = false;
        $user->save();
        Auth::login($user, false);
        return redirect()->route('jurytafel.index');
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

        $user = User::create($request->only('name', 'email', 'password'));
        if ($user->is_jury) {
            $user->assignRole('jury');
            $user->active = true;
        }
        if ($user->is_trainer) {
            $user->assignRole('trainer');
            $user->active = true;
        }

        if (!$user->active) User::where('email', 'rickokkersen@gmail.com')->first()->notify(new \App\Notifications\AccountWaitingActivation());

        return $user->active ? redirect()->route('auth.login')->with('success', 'Uw account is geactiveerd! U kunt nu inloggen') : redirect()->route('auth.register')->with('success', 'We konden niet verifieren of u een jurylid of trainer bent. Uw account is aangemaakt, maar moet nog geactiveerd worden door een beheerder. U ontvangt een email zodra uw account is geactiveerd.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }

    public function lock()
    {
        Auth::user()->locked = true;
        Auth::user()->save();
        return view('auth.locked');
    }

    public function unlock(Request $request)
    {
        $this->validate($request, [
            'password' => 'required'
        ]);
        $user = Auth::user();
        if (Auth::validate(['email' => $user->email, 'password' => $request->password])) {
            Auth::user()->locked = false;
            Auth::user()->save();
            $redirect = Device::where('ip', $request->ip())->first() ? 'jurytafel.index' : 'dashboard';
            return redirect()->route($redirect);
        }

        return back()
            ->withErrors(['details' => 'Ongeldige inloggegevens!']);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JuryTafel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is not authenticated
        if (!Auth::check()) {
            $request->session()->forget('device_id');
            return redirect()->route('auth.local');
        } else {
            if ($request->user()->hasRole('admin')) return $next($request);
            // if (!$request->user()->hasRole('jury')) return $next($request); // NOTE - temporary override
            $user_id = $request->user()->id;
            $device = \App\Models\Device::where('authenticated_user_id', $user_id)->where('type', 'jury')->first();

            // if (!$device || empty($device->authenticated_user_id)) {
            //     Auth::logout();
            //     return redirect()->route('auth.local');
            // }
            // Auth::loginUsingId($device->authenticated_user_id);
        }

        $device->loaded_page = '/' . ($request->path() == 'auth/local' ? 'jurytafel' : $request->path());
        $device->last_seen = now();
        $device->save();

        return $next($request);
    }
}

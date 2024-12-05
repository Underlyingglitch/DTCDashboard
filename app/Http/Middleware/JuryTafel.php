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
        if ($request->user()->hasRole('admin')) return $next($request);
        $device_id = $request->session()->get('device_id');
        $device = \App\Models\Device::where('device_id', $device_id)->where('type', 'jury')->first();
        if (!$device || empty($device->authenticated_user_id)) {
            Auth::logout();
            return redirect()->route('auth.local');
        }
        $device->loaded_page = '/' . $request->path();
        $device->last_seen = now();
        $device->save();
        return $next($request);
    }
}

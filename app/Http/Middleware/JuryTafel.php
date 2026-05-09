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
     * This middleware tracks the loaded page for authenticated users and devices.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a user is authenticated
        if (Auth::check()) {
            if ($request->user()->hasRole('admin')) return $next($request);

            // For jury users, track the page they're viewing
            $user_id = $request->user()->id;
            $device = \App\Models\Device::where('authenticated_user_id', $user_id)
                ->where('type', 'jury')
                ->first();

            if ($device) {
                $device->update([
                    'loaded_page' => '/' . $request->path(),
                    'last_seen' => now(),
                ]);
            }
        }
        // If device authentication is used, the device middleware handles tracking

        return $next($request);
    }
}

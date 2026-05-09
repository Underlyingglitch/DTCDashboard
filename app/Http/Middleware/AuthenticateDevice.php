<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;

class AuthenticateDevice
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if a device is already authenticated
        if (Auth::guard('device')->check()) {
            return $next($request);
        }

        // Try to auto-authenticate based on device identifier
        // This could be from a session, cookie, or MQTT identifier
        $deviceIdentifier = $request->get('device_id')
            ?? $request->cookie('device_id')
            ?? $request->header('X-Device-ID');

        if ($deviceIdentifier) {
            $device = Device::where('device_id', $deviceIdentifier)->first();
            if ($device) {
                // Auto-login the device
                Auth::guard('device')->login($device);
                return $next($request);
            }
        }

        // Device not authenticated, redirect to device login
        return redirect()->route('auth.device.login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceAuthToken;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DeviceAuthController extends Controller
{
    /**
     * Show the device login form.
     */
    public function showLogin()
    {
        return view('auth.device-login');
    }

    /**
     * Handle device authentication via token.
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $authToken = DeviceAuthToken::where('token', $request->token)
            ->first();

        if (!$authToken || !$authToken->isValid()) {
            return back()->withErrors(['token' => 'Invalid or expired token.']);
        }

        $device = $authToken->device;

        // Check if device exists and is not already authenticated
        if (!$device) {
            return back()->withErrors(['token' => 'Device not found.']);
        }

        // Authenticate the device using the 'device' guard
        Auth::guard('device')->login($device);

        // Delete the token once used
        $authToken->delete();

        // Redirect to intended page or device's loaded page
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Send authentication token to device via MQTT.
     * Called by admin from Monitor page.
     */
    public function sendTokenToDevice(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->device_id);

        // Generate a unique token
        $token = Str::random(32);

        // Create token valid for 15 minutes
        $authToken = DeviceAuthToken::create([
            'device_id' => $device->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send command to device via MQTT to redirect to login page with token
        $this->sendMqttCommand($device, [
            'action' => 'redirect',
            'url' => route('auth.device.login', ['token' => $token]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Authentication token sent to device',
        ]);
    }

    /**
     * Send MQTT command to device.
     */
    protected function sendMqttCommand(Device $device, array $command)
    {
        $mqtt = new MqttService();
        $topic = "jurypanel/{$device->device_id}/command";
        $mqtt->publish($topic, json_encode($command));
    }

    /**
     * Logout the authenticated device.
     */
    public function logout()
    {
        Auth::guard('device')->logout();
        return redirect()->route('auth.device.login');
    }
}

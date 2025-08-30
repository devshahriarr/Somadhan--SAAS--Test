<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDevice
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $deviceIdentifier = $request->header('User-Agent');

        if ($user) {
            $device = $user->devices()->where('device_identifier', $deviceIdentifier)->first();

            if (! $device) {
                if ($user->devices()->count() >= 1) {
                    return redirect()->back()->with('error', 'You are not allowed to access from this device.');
                } else {
                    $user->devices()->create([
                        'device_identifier' => $deviceIdentifier,
                        'is_primary' => true,
                    ]);
                }
            }
        }

        return $next($request);
    }
}

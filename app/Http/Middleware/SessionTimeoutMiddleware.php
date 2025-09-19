<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = Session::get('lastActivityTime');
            $sessionTimeout = config('session.lifetime') * 60; // Convert minutes to seconds

            if ($lastActivity && (time() - $lastActivity) > $sessionTimeout) {
                // Session has expired
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Session expired. Please login again.',
                        'redirect' => route('login')
                    ], 401);
                }

                return redirect()->route('login')
                    ->with('warning', 'Session expired due to inactivity. Please login again.');
            }

            // Update last activity time
            Session::put('lastActivityTime', time());
        }

        return $next($request);
    }
}

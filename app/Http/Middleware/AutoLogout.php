<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutoLogout
{
    public function handle($request, Closure $next, $maxIdleTime = 1800) // 30 minutes in seconds
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            $currentTime = time();

            if ($lastActivity && $currentTime - $lastActivity > $maxIdleTime) {
                Auth::logout();
                Session::flush();
                return redirect('/login')->with('auto_logout', 'You have been automatically logged out due to inactivity.');
            }

            // Update the remaining idle time for debugging purposes
            $remainingIdleTime = $maxIdleTime - ($currentTime - $lastActivity);

            // Reset the last activity timestamp and update the remaining idle time when the user interacts with the application
            if ($request->method() !== 'GET') {
                Session::put('last_activity', $currentTime);
                $remainingIdleTime = $maxIdleTime;
            }

            // Pass the remaining idle time to the view (optional)
            $request->attributes->add(['remainingIdleTime' => $remainingIdleTime]);
        }

        return $next($request);
    }
}
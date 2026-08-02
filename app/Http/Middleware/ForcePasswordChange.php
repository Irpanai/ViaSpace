<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Check if the current route is NOT the profile page or profile update action
            if (!$request->routeIs('intern.profile') && !$request->routeIs('intern.profile.update') && !$request->routeIs('logout')) {
                return redirect()->route('intern.profile')->with('error', 'Anda harus mengubah password default (acak) Anda sebelum dapat mengakses aplikasi.');
            }
        }
        
        return $next($request);
    }
}

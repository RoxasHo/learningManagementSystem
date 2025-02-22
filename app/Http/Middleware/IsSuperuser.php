<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsSuperuser
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role == 'superuser') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Access denied.');
    }
}

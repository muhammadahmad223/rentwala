<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Onboarded
{
    public function handle(Request $request, Closure $next)
    {
        $u = $request->user();
        if (! $u->onboarded && $u->role !== 'super_admin') {
            return redirect('/setup');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if ($role == 'applicant' && auth()->user()->user_type != 0) {
            abort(403);
        }

        if ($role == 'hr-staff' && auth()->user()->user_type != 1) {
            abort(403);
        }

        if ($role == 'hr-admin' && auth()->user()->user_type != 2) {
            abort(403);
        }

        if ($role == 'admin' && auth()->user()->user_type != 3) {
            abort(403);
        }

        return $next($request);
    }
}

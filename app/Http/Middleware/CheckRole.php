<?php

namespace App\Http\Middleware;

use App\Models\User;
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

        if ($role == 'applicant' && auth()->user()->user_type != User::APPLICANT) {
            abort(403);
        }

        if ($role == 'hr-staff' && auth()->user()->user_type != USER::HR_STAFF) {
            abort(403);
        }

        if ($role == 'hr-manager' && auth()->user()->user_type != USER::HR_MANAGER) {
            abort(403);
        }

        if ($role == 'admin' && auth()->user()->user_type != USER::ADMIN) {
            abort(403);
        }

        return $next($request);
    }
}

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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!session()->has('user')) {
            return redirect()->route('login');
        }

        if (in_array(session('user.role'), $roles, true)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
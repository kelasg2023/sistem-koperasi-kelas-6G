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
        if (!auth()->check()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401)
                : redirect('/login');
        }

        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        return $request->expectsJson() || $request->is('api/*')
            ? response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403)
            : abort(403, 'Unauthorized action.');
    }
}

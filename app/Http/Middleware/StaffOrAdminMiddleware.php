<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffOrAdminMiddleware {
    public function handle(Request $request, Closure $next): Response {
        if (!auth()->check() || auth()->user()->role === 'guest') {
            abort(403, 'Access denied. Staff or Admin only.');
        }
        return $next($request);
    }
}
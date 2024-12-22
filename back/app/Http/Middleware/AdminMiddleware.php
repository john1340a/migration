<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->admin) {
            return response()->json([
                'message' => 'Accès non autorisé. Privilèges administrateur requis.'
            ], 403);
        }

        return $next($request);
    }
}
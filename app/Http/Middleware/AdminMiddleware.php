<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->roles->contains('name', 'admin')) {
            return $next($request);
        }
        
        abort(403, 'Acesso restrito. Apenas administradores.');
    }
}

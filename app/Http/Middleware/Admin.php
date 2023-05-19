<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user == null || $user->role != 'admin') {
            return response()->json(['user not in role admin' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}

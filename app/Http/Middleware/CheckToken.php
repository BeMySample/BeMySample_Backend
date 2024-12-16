<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $tokenRecord = PersonalAccessToken::findToken($token);

        if (!$tokenRecord) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $request->merge(['user_id' => $tokenRecord->tokenable_id]);

        return $next($request);
    }
}

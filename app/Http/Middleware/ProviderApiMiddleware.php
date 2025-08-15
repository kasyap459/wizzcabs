<?php

namespace App\Http\Middleware;

use Config;
use Closure;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ProviderApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function respondWithError($errorMessage)
    {
        return response()->json(['success' => "0", "message" => $errorMessage], 401);
    }

    public function handle($request, Closure $next)
    {
        Config::set('auth.providers.users.model', 'App\Models\Provider');
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // $user = JWTAuth::getToken();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->respondWithError("Token is Invalid");
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->respondWithError("Token is Expired");
            } else {
                return $this->respondWithError("Login failed");
            }
        }
        return $next($request);
    }
}

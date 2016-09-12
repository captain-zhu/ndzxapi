<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthAPIRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 试试是不是管理员
        if ($request->hasHeader('authorization')) {
            if (! $token = JWTAuth::setRequest($request)->getToken()) {
                return response()->json(['tymon.jwt.absent' => 'token_not_provided'], 400);
            }

            try {
                $admin = JWTAuth::authenticate($token);
            } catch (TokenExpiredException $e) {
                return response()->json(['tymon.jwt.expired' => 'token_expired'], $e->getStatusCode(), [$e]);
            } catch (JWTException $e) {
                return response()->json(['tymon.jwt.invalid' => 'token_invalid'], $e->getStatusCode(), [$e]);
            }

            if (! $admin) {
                return response(['tymon.jwt.user_not_found' => 'user_not_found'], 404);
            }
            session(['api.oauth_admin' => $admin]);

            return $next($request);
        }
        // 试试是不是用户
        if ($request->hasHeader('x-access-token')) {
            try {
                $user = User::where('openid', $request->header('x-access-token'))->firstOrFail();
                session(['api.oauth_user' => $user]);
                return $next($request);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => "需要先用户登录"], 401);
            }
        }
    }
}

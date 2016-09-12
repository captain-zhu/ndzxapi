<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/23
 * Time: 17:49
 */

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class APIAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $authed = false;

        // 试试是不是管理员
        try {

            if ($admin = JWTAuth::parseToken()->authenticate()) {
                $authed = true;
            }

        } catch (TokenExpiredException $e) {

        } catch (TokenInvalidException $e) {

        } catch (JWTException $e) {

        }

        // 试试是不是用户
        return response()->json($request->header());

        return $next($request);
    }

}
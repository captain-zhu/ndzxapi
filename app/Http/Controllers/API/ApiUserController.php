<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/9/6
 * Time: 17:00
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;

class ApiUserController extends Controller
{
    public function getUser() {
        $sessionUser = session('api.oauth_user');
        return response()->json($sessionUser);
    }
}
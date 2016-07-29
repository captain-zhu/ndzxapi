<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/7/29
 * Time: 17:38
 */

namespace App\Http\Controllers;


class UserController extends Controller
{
    /*
     * get修改用户信息的界面
     */
    public function getUser()
    {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料
        dd($user);
    }
}
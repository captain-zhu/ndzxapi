<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


# 微信公众平台服务端
Route::any('/wxservice', 'WechatServiceController@wxserve');

# 采用验证微信登录的middleware
Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    # 转到完善/修改用户信息的controller
    Route::get('/user', "UserController@getUser");
});

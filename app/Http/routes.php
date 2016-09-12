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
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;

Route::any('/wxservice', 'WechatServiceController@wxserve')->name('wxservice');

# 采用验证微信登录的middleware
Route::group(['middleware' => ['wechat.oauth']], function () {
    # 转到完善/修改用户信息的controller
    Route::get('/user', "UserController@getUser")->name('user.get');
    Route::post('/user', 'UserController@postUser')->name('user.post');

    # 订餐
    Route::get('/dinner/order/{type}', "DinnerController@getOrder")->name('dinner.order.get');
    Route::post('/dinner/order', 'DinnerController@postOrder')->name('dinner.order.post');

    # 订单
    Route::get('/order/me', 'OrderController@getMyOrders')->name('order.me.get');
    Route::get('/order/history', 'OrderController@getHistoryOrders')->name('order.history.get');
    Route::get('/orders/{id}', 'OrderController@getOrder')->name('order.get');

    # 充值
    Route::get('/deposit', 'DepositController@getDeposit')->name('deposit.get');
    Route::post('/deposit', 'DepositController@postDeposit')->name('deposit.post');
    Route::get('/deposit/success', 'DepositController@paySuccess')->name('deposit.success');
    Route::get('/deposit/callback', 'DepositControler@callback')->name('deposit.callback');
});

# 注册与登录
Route::group(['middleware' => 'cors'], function (Router $router) {
    # 注册
    $router->post('/api/signup', 'AuthController@signUp')->name('signup');
    # 登录
    $router->post('/api/signin', 'AuthController@signIn')->name('signin');
    # 修改用户信息
    $router->post('/api/edit', 'AuthController@edit')->name('edit');
});

# rest api
Route::group(['prefix' => 'api', 'middleware' => 'apiauth'], function (Router $router) {
    Route::group(['namespace' => 'API'], function (Router $router) {
        # 管理员
        $router->resource('admins', 'AdminController');
        # 充值
        $router->resource('deposits', 'DepositController', ['except' => [
            'create', 'edit'
        ]]);
        # 餐次
        $router->resource('dinners', 'DinnerController');
        # 菜品
        $router->resource('dishes', 'DishController');
        # 菜单
        $router->resource('menus', 'MenuController', ['except' => [
            'create', 'edit'
        ]]);
        # 订单
        $router->resource('orders', 'OrderController', ['except' => [
            'create', 'edit'
        ]]);
        # 用户
        $router->resource('users', 'UserController', ['except' => [
            'create', 'edit'
        ]]);
        $router->get('user', 'ApiUserController@getUser');
        # 用户信息
        $router->resource('userinfos', 'UserinfoController');
        # 得到现在订餐所处的日期
        $router->get('dinnerdate', 'DateController@getDinnerDate');
        # 获得用户所有的组别
        $router->get('usergroups', 'UserGroupController@getUserGroup');
        # 给某组的所有成员充值
        $router->post('depositforgroups', 'UserGroupController@postGroupDepost');
        # 获取某个用户当日的订餐情况
        $router->patch('orderinfos', 'OrderController@getOrderInfos');
    });

    # 订餐
    $router->get('mydinnerinfo', 'DinnerController@getMyDinnerInfoApi');
    $router->post('orderdinner', 'DinnerController@postOrderApi');
    # 订餐信息
    $router->get('myorders', 'OrderController@getMyOrdersApi');
});

# 转入angular应用
Route::group(['prefix' => 'app'], function() {

    Route::any('{path?}', function()
    {
        return View::make("index");
    })->where("path", ".+");

});


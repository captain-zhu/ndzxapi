<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/4
 * Time: 10:54
 */

namespace App\Http\Controllers;


use App\Helpers\TimeHelper;
use App\Models\Order;
use App\Models\User;
use Log;
class OrderController extends Controller
{
    /*
     * 通过api获得我的今日订单和历史订单
     */
    public function getMyOrdersApi()
    {
        # 获得用户信息
        $sessionUser = session('api.oauth_user');
        $id = $sessionUser->id;
        $todayOrders = Order::whereDate('date', '=', TimeHelper::getDinnerDate())
            ->where('user_id', $id);
        $historyOrders = Order::where('user_id', $id);
        $returnArray = [$todayOrders->get(), $historyOrders->get()];
        return response()->json($returnArray);
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMyOrders()
    {
        # 获得用户信息
        $sessionUser = session('wechat.oauth_user');
        $openid = $sessionUser->id;
        $user = User::where("openid", $openid)->first();
        $orders = Order::whereDate('date', '=', TimeHelper::getDinnerDate())
            ->where('user_id', $user->id);
        # 如果该用户没有订单，那么进入没有订单页
        if (is_null($orders) || $orders->count() == 0) {
            return view('orders.notorder');
        }
        $orders = $orders->get();
        # 发现了用户订单,进入我的订单页
        return view('orders.me', [
            'orders' => $orders
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHistoryOrders()
    {
        # 获得用户信息
        $sessionUser = session('wechat.oauth_user');
        $openid = $sessionUser->id;
        $user = User::where("openid", $openid)->first();
        $orders = Order::where('user_id', $user->id);
        # 如果该用户没有订单，那么进入没有订单页
        if (is_null($orders) || $orders->count() == 0) {
            return view('orders.nohistoryorder');
        } elseif ($orders->count() == 1) {
            return view('orders.order',[
                'order' => $orders->first()
            ]);
        }
        $orders = $orders->paginate(8);
        # 发现了用户订单,进入我的订单页
        return view('orders.history', [
            'orders' => $orders
        ]);
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOrder($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.order', [
            'order' => $order
        ]);
    }
}
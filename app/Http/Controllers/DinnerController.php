<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/3
 * Time: 11:14
 */

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Models\Admin;
use App\Models\Dinner;
use App\Models\Order;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;
use Log;

class DinnerController extends Controller
{
    public function getOrder($type)
    {
        $dinnerName = "";
        $dinner = Dinner::find($type);
        # 如果当前时段不能订餐，那么进入禁止订餐页面
        if (!$this->canIOrder($dinner)) {
            return $this->forbiddenOrder();
        };
        switch ($type) {
            case 1:
                $dinnerName = "早餐";
                break;
            case 2:
                $dinnerName = "午餐";
                break;
            case 3:
                $dinnerName = "晚餐";
                break;
            default:
                return view("errors.503");
        }
        # 获得今日的日期，如果现在的时间已经超过了switch-time,那么说明是第二天的订单
        $time = Time::find(1);
        $switchTime = $time->switch_time;
        $date = TimeHelper::getDinnerDate();
        # 判断是否已经有订单，如果有，那么用户将是更新订单而不是提交订单
        $sessionUser = session('wechat.oauth_user');
        $openid = $sessionUser->id;
        $user = User::where("openid", $openid)->first();
        $orders = Order::whereDate('date', "=", $date)->where([
            "user_id" => $user->id,
            'order' => $type
        ]);
        if ($orders->count() == 1) {
            # 更新订单
            return view('dinner.order', [
                'method' => 1,
                'order' => $orders->first(),
                'type' => $type,
                'dinnerName' => $dinnerName,
                'dinner' => $dinner,
                'date' => $date
            ]);
        }
        # 新建菜单
        return view('dinner.order', [
            'method' => 0,
            'type' => $type,
            'dinnerName' => $dinnerName,
            'dinner' => $dinner,
            'date' => $date
        ]);
    }

    /*
     * 获取今天的订餐信息
     */
    public function getMyDinnerInfoApi()
    {
        # 获得今日的日期，如果现在的时间已经超过了switch-time,那么说明是第二天的订单
        $time = Time::find(1);
        $switchTime = $time->switch_time;
        $date = TimeHelper::getDinnerDate();
        # 获得用户信息
        $sessionUser = session('api.oauth_user');
        $id = $sessionUser->id;
        $user = User::find($id);
        # 初始化订餐信息
        $dinnerInfo = [
            [
                'state' => 0,
                'date' => $date,
                'ordered' => 0
            ],[
                'state' => 0,
                'date' => $date,
                'ordered' => 0
            ],[
                'state' => 0,
                'date' => $date,
                'ordered' => 0
            ]
        ];
        # 更新早餐信息
        $dinnerOne = Dinner::find(1);
        # 如果当前时段不能订餐，那么进入禁止订餐页面
        if (!$this->canIOrder($dinnerOne)) {
            $dinnerInfo[0]['state'] = 1;
        };
        # 判断是否已经有订单，如果有，那么用户将是更新订单而不是提交订单
        $ordersOne = Order::whereDate('date', "=", $date)->where([
            "user_id" => $user->id,
            'order' => 1
        ]);
        if ($ordersOne->count() == 1) {
            $dinnerInfo[0]['ordered'] = 1;
            $dinnerInfo[0]['order'] = $ordersOne->first();
        }
        # 更新午餐信息
        $dinnerTwo = Dinner::find(2);
        # 如果当前时段不能订餐，那么进入禁止订餐页面
        if (!$this->canIOrder($dinnerTwo)) {
            $dinnerInfo[1]['state'] = 1;
        };
        # 判断是否已经有订单，如果有，那么用户将是更新订单而不是提交订单
        $ordersTwo = Order::whereDate('date', "=", $date)->where([
            "user_id" => $user->id,
            'order' => 2
        ]);
        if ($ordersTwo->count() == 1) {
            $dinnerInfo[1]['ordered'] = 1;
            $dinnerInfo[1]['order'] = $ordersTwo->first();
        }
        # 更新晚餐信息
        $dinnerThree = Dinner::find(3);
        # 如果当前时段不能订餐，那么进入禁止订餐页面
        if (!$this->canIOrder($dinnerThree)) {
            $dinnerInfo[2]['state'] = 1;
        };
        # 判断是否已经有订单，如果有，那么用户将是更新订单而不是提交订单
        $ordersThree = Order::whereDate('date', "=", $date)->where([
            "user_id" => $user->id,
            'order' => 2
        ]);
        if ($ordersThree->count() == 1) {
            $dinnerInfo[2]['ordered'] = 1;
            $dinnerInfo[2]['order'] = $ordersThree->first();
        }
        return response()->json($dinnerInfo);
    }

    public function postOrder(Request $request)
    {
        $this->validate($request,[
            'dinnernumber' => "required|numeric|min:1"
        ]);
        # 先判断现在能否订单
        $type = $request->input('dinnertype');
        $dinner = Dinner::find($type);
        if (!$this->canIOrder($dinner)) {
            return $this->forbiddenOrder();
        }
        # 获得用户信息
        $sessionUser = session('wechat.oauth_user');
        $openid = $sessionUser->id;
        $user = User::where("openid", $openid)->first();
        # 如果已经有订单
        if (!is_null($request->input('dinnerorderid'))) {
            $order = Order::find($request->input('dinnerorderid'));
            $count = $request->input('dinnernumber');
            $singleValue = $order->menu->value;
            $wholeValue = $count * $singleValue;
            $diff = $wholeValue - $order->whole_value;
            # 如果更新后的总价差大于用户的金币数，那么不允许用户订餐
            if ($diff > $user->gold) {
                return view("dinner.notenough");
            }
            $order->single_value = $singleValue;
            $order->count = $count;
            $order->whole_value = ceil($wholeValue * 100) / 100.00;
            $order->save();
            $user->gold = $user->gold - $diff;
            $user->save();
        } else {
            # 新建订单
            $singleValue = $dinner->menu->value;
            $count = $request->input('dinnernumber');
            $wholeValue = (float)$singleValue * (float)$count;
            $wholeValue = number_format((float)$wholeValue, 2, '.', '');
            # 用户金额不足
            if ($wholeValue > $user->gold) {
                return view("dinner.notenough");
            }
            $order = Order::create([
                'user_id' => $user->id,
                'menu_id' => $dinner->menu->id,
                'single_value' => $singleValue,
                'count' => $count,
                'whole_value' => $wholeValue,
                'date' => $request->input('dinnerdate'),
                'order' => $type,
                'state' => 0
            ]);
            $order->save();
            $user->gold = $user->gold - $wholeValue;
            $user->save();
        }
        return view('orders.order',[
            'order' => $order
        ]);
    }
    
    /*
     * 用api订餐
     */
    public function postOrderApi(Request $request)
    {
        $this->validate($request,[
            'dinnernumber' => "required|numeric|min:1",
            'dinnertype' => 'required'
        ]);
        # 先判断现在能否订单
        $type = $request->input('dinnertype');
        $dinner = Dinner::find($type);
        if (!$this->canIOrder($dinner)) {
            return response()->json(['type' => 'fail', 'msg' => '现在不是订餐时间']);
        }
        # 获得用户信息
        $sessionUser = session('api.oauth_user');
        $id = $sessionUser->id;
        $user = User::find($id);
        # 如果已经有订单
        if (!is_null($request->input('dinnerorderid'))) {
            $order = Order::find($request->input('dinnerorderid'));
            $count = $request->input('dinnernumber');
            $singleValue = $order->menu->value;
            $wholeValue = $count * $singleValue;
            $diff = $wholeValue - $order->whole_value;
            # 如果更新后的总价差大于用户的金币数，那么不允许用户订餐
            if ($diff > $user->gold) {
                return response()->json(['type' => 'fail', 'msg' => '您的金币数不够，请先充值']);
            }
            $order->single_value = $singleValue;
            $order->count = $count;
            $order->whole_value = ceil($wholeValue * 100) / 100.00;
            $order->save();
            $user->gold = $user->gold - $diff;
            $user->save();
        } else {
            # 新建订单
            $singleValue = $dinner->menu->value;
            $count = $request->input('dinnernumber');
            $wholeValue = (float)$singleValue * (float)$count;
            $wholeValue = number_format((float)$wholeValue, 2, '.', '');
            # 用户金额不足
            if ($wholeValue > $user->gold) {
                return response()->json(['type' => 'fail', 'msg' => '您的金币数不足，请先充值']);
            }
            $order = Order::create([
                'user_id' => $user->id,
                'menu_id' => $dinner->menu->id,
                'single_value' => $singleValue,
                'count' => $count,
                'whole_value' => $wholeValue,
                'date' => $request->input('dinnerdate'),
                'order' => $type,
                'state' => 0
            ]);
            $order->save();
            $user->gold = $user->gold - $wholeValue;
            $user->save();
        }
        return $order->toJson();
    }
    
    /*
     * 判断该餐次是否能够订餐
     */
    private function canIOrder(Dinner $dinner)
    {
        # 如果该餐次的订单状态，如果目前不能点餐，那么转到不能订餐的页面
        if ($dinner->state == 1) {
            return false;
        }
        # 得到该餐次的运行订餐时间，然后与目前的时间比较，如果不在订餐时间，那么转到禁止订餐的页面
        $time = Time::find(1);
        $switchTime = $time->switch_time;
        $startTime = $dinner->start_time;
        $endTime = $dinner->end_time;
        $nowTime = date("H:i:s");
        # 如果开始时间，当前时间都在同一天, 但是当前时间小于开始时间,不允许订餐
        if (((strtotime($startTime) >= strtotime($switchTime) && strtotime($nowTime) >= strtotime($switchTime))
            || (strtotime($startTime) < strtotime($switchTime) && strtotime($nowTime) < strtotime($switchTime)))
            && strtotime($nowTime) < strtotime($startTime)) {
            return false;
        }
        # 如果开始时间在后一天，当前时间在前一天，那么不允许订餐
        if (strtotime($startTime) < strtotime($switchTime) && strtotime($nowTime) >= strtotime($switchTime)) {
            return false;
        }
        # 如果结束时间和当前时间在同一天，但是当前时间大于结束时间，不允许订餐
        if (((strtotime($endTime) >= strtotime($switchTime) && strtotime($nowTime) >= strtotime($switchTime))
                || (strtotime($endTime) < strtotime($switchTime) && strtotime($nowTime) < strtotime($switchTime)))
            && strtotime($nowTime) >= strtotime($endTime)) {
            return false;
        }
        # 如果结束时间在前一天，当前时间在后一天，那么不允许订餐
        if (strtotime($endTime) >= strtotime($switchTime) && strtotime($nowTime) < strtotime($switchTime)) {
            return false;
        }
        return true;
    }

    /*
     * 转入禁止订餐的页面
     */
    private function forbiddenOrder()
    {
        $admin = Admin::find(1);
        return view('dinner.fail',[
            'admin' => $admin
        ]);
    }
}
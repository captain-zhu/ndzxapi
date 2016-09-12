<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Log;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::has('user')->get();
        return $orders->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'user_id' => 'required|regex:/^[1-9][0-9]*$/',
            'menu_id' => 'required|regex:/^[1-9][0-9]*$/',
            'single_value' => 'required|numeric|min:0',
            'count' => 'required|regex:/^[1-9][0-9]*$/',
            'order' => 'required|regex:/^[1-3]$/',
            'date' => 'required|regex:/^[1-9][0-9]{3}-[0-1][0-9]-[0-3][1-9]$/'
        );
        $validation = Validator::make($request->input(), $rules);
        if ($validation->fails()) {
            Log::info($validation->errors()->first());
            return response()->json(['type' => 'fail', 'msg' => "请按要求输入内容"]);
        }
        // 判断菜单的价格是否有变化
        try {
            $menu = Menu::findOrFail($request->input('menu_id'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail', 'msg' => "请按要求输入内容"], 404);
        }
        if ($menu->value != $request->input('single_value')) {
            response()->json(['type' => 'fail', 'msg' => '菜单价格已发生变化']);
        }
        $singleValue = ceil((float)($request->input('single_value')) * 100) / 100.00;
        $count = (int)$request->input('count');
        $wholeValue = $singleValue * $count;
        try {
            $user = User::findOrFail($request->input('user_id'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail', 'msg' => '您的账号出现问题，暂时不能订餐']);
        }
        if ((float)$user->gold < $wholeValue) {
            return response()->json(['type' => 'fail', 'msg' => '您的金币不足，请先充值']);
        }
        // 如果已经有了订单
        if (Order::whereDate('date', '=', $request->input('date'))
            ->where('order', $request->input('order'))
            ->where('user_id', $request->input('user_id'))
            ->where('menu_id', $request->input('menu_id'))->count() >= 1) {
            $order = Order::whereDate('date', '=', $request->input('date'))
                ->where('order', $request->input('order'))
                ->where('user_id', $request->input('user_id'))
                ->where('menu_id', $request->input('menu_id'))->first();
            $order->count = (int)$order->count + $count;
            $order->whole_Value = (float)$order->whole_Value + $wholeValue;
        } else {
            $order = Order::create([
                'user_id' => $request->input('user_id'),
                'menu_id' => $request->input('menu_id'),
                'single_value' => $singleValue,
                'count' => $count,
                'whole_value' => $wholeValue,
                'state' => 0,
                'order' => $request->input('order'),
                'date' => $request->input('date')
            ]);
        }
        $order->save();
        $user->gold = (float)($user->gold) - $wholeValue;
        $user->save();
        return response()->json(['type' => 'success', 'msg' => '创建订单成功']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function getOrderInfos(Request $request)
    {
        $rules = [
            'date' => 'required|regex:/^[1-9][0-9]{3}-[0-1][0-9]-[0-3][1-9]$/',
            'user_id' => 'required|regex:/^[1-9][0-9]*$/'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '获取该用户订单信息失败'], 400);
        }
        $date = $request->input('date');
        $userid = $request->input('user_id');
        $results = array();
        $breakfasts = Order::whereDate('date', '=', $date)->where('user_id', $userid)->where('order', 1);
        $lunchs = Order::whereDate('date', '=', $date)->where('user_id', $userid)->where('order', 2);
        $dinners = Order::whereDate('date', '=', $date)->where('user_id', $userid)->where('order', 3);
        if ($breakfasts->count() == 0) {
            $results['breakfast'] = "未订早餐";
        } else {
            $results['breakfast'] = '已订份数:' . $breakfasts->first()->count . "   已取餐份数:" . $breakfasts->first()->state ;
        }
        if ($lunchs->count() == 0) {
            $results['lunch'] = "未订午餐";
        } else {
            $results['lunch'] = '已订份数:' . $lunchs->first()->count . "   已取餐份数:" . $breakfasts->first()->state ;
        }
        if ($dinners->count() == 0) {
            $results['dinner'] = "未订晚餐";
        } else {
            $results['dinner'] = '已定份数:' . $dinners->first()->count . "   已取餐份数:" . $breakfasts->first()->state;
        }
        return response()->json($results);
    }
}

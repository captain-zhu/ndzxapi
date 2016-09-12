<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/9
 * Time: 17:11
 */

namespace App\Http\Controllers;

use App\Models\Deposit;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use EasyWeChat\Payment\Order;

class DepositController extends Controller
{
    /**
     * 获得充值的页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDeposit()
    {
        return view('deposits.getDeposit');
    }

    public function postDeposit(Request $request)
    {
        $this->validate($request, [
            'depositvalue' => 'required|numeric|min:0'
        ]);

        $wechat = app('wechat');
        $payment = $wechat->payment;
        $totalFee = ceil((float)($request->input('depositvalue')) * 100.00);
        $sessionUser = session('wechat.oauth_user');
        $openid = $sessionUser->id;
        $user = User::where("openid", $openid)->first();
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => '宁都中学订餐管理系统充值',
            'detail'           => $user->id,
            'out_trade_no'     => md5(uniqid().microtime()),
            'total_fee'        => $totalFee,
            'notify_url'       => 'http://fz.garmintech.net/deposit/callback', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            // ...
        ];

        $order = new Order($attributes);
        $result = $payment->pay($order);

        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
            $config = $payment->configForJSSDKPayment($prepayId);
            return view('deposits.payDeposit', [
                'totalFee' => $totalFee / 100.00,
                'config' => $config
            ]);
        }
        return view('deposits.fail');

    }

    public function paySuccess()
    {
        return view('deposits.success');
    }

    public function callback()
    {
        $app = app('wechat');
        $response = $app->payment->handleNotify(function($notify, $successful){
            $deposit = Deposit::where('transactioned', $notify->tanscation_id);
            if ($deposit->count() >= 1) {
                # 已有订单，不用重复储存
                return "Order already exist";
            }
            // 用户是否支付成功
            if ($successful) {
                // 储存订单
                $user = User::where('openid', $notify->openid)->first();
                $deposit = Deposit::create([
                    'userid' => $user->id,
                    'value' => (float)($notify->total_fee) / 100,
                    'type' => 1,
                    'transactionid' => $notify->transcation_id,
                    'time_end' => $notify->time_end
                ]);
                $deposit->save();
                $user->gold = (float)($user->gold) + ((float)($notify->total_fee) / 100);
                $user->save();
            }
            return true; // 返回处理完成
        });
        return $response;
    }
}
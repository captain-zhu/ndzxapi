<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/7/28
 * Time: 15:33
 */

namespace App\Http\Controllers;

use App\Helpers\BuildMenuHelper;
use App\Helpers\UserGroupManageHelper;
use App\Models\Admin;
use App\Models\Dinner;
use Log;
use EasyWeChat\Foundation\Application;
use App\Models\User;


class WechatServiceController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @param $wechat
     *
     *
     * @return string
     */
    public function wxserve(Application $wechat)
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $wechat->server->setMessageHandler(function($message) use ($wechat) {
            # 获取用户的openid，对应到我们用户
            # 如果该openid不存在用户，那么将新建用户
            $openid = $message->FromUserName;
            if (User::where('openid', '=', $openid)->count() == 0) {
                $user = User::create([
                    'openid' => $openid,
                    'gold' => 0,
                    'privilege' => "未分组"
                ]);
                $user->save();
            }
            $user = User::where('openid', '=', $openid)->first();
            # 如果用户已经认证，将其移到105组
            $userGroupHelper = new UserGroupManageHelper($wechat);
            $userGroupHelper->moveToNewGroup($openid, 2);

            #处理相应的消息类型
            switch ($message->MsgType) {
                case 'event':
                    # 事件消息...
                    switch ($message->Event) {
                        #订阅
                        case "subscribe" :
                            return "欢迎关注 宁都中学公众平台！\n请先在个人中心中完善个人信息";
                            break;
                        #退订
                        case "unsubscribe" :
                            break;
                        #按键事件
                        case "CLICK" :
                            switch ($message->EventKey) {
                                # 查看早餐菜单
                                case "breakfastMenu":
                                    $dinner = Dinner::find(1);
                                    if (is_null($dinner)) {
                                        return "今日无早餐菜单";
                                    }
                                    return $dinner->getDinnerDescription();
                                    break;
                                # 查看午餐菜单
                                case "lunchMenu":
                                    $dinner = Dinner::find(2);
                                    if (is_null($dinner)) {
                                        return "今日无午餐菜单";
                                    }
                                    return $dinner->getDinnerDescription();
                                    break;
                                # 查看晚餐
                                case "dinnerMenu":
                                    $dinner = Dinner::find(3);
                                    if (is_null($dinner)) {
                                        return "今日无晚餐菜单";
                                    }
                                    return $dinner->getDinnerDescription();
                                    break;
                                # 查看用户信息
                                case "viewUserInformation":
                                    return $user->getUserinfoText();
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                    break;
                case 'text':
                    # 文字消息...
                    switch ($message->Content) {
                        # 更新菜单， 会调用app\Helpers\BuildMenuHelper.php，如果需要修改菜单，那么可以去那里修改
                        case 'build menu':
                            $menuHelper = new BuildMenuHelper($wechat);
                            $menuHelper->buildMenu();
                            break;
                        default:
                            break;
                    }
                    break;
                case 'image':
                    # 图片消息...
                    break;
                case 'voice':
                    # 语音消息...
                    break;
                case 'video':
                    # 视频消息...
                    break;
                case 'location':
                    # 坐标消息...
                    break;
                case 'link':
                    # 链接消息...
                    break;
                // ... 其它消息
                default:
                    # code...
                    return "欢迎关注 宁都中学公众平台！\n请先在个人中心中完善个人信息";
                    break;
            }
        });

        Log::info('return response.');

        return $wechat->server->serve();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/7/29
 * Time: 17:38
 */

namespace App\Http\Controllers;

use App\Helpers\UserGroupManageHelper;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Userinfo;

class UserController extends Controller
{
    /*
     * get修改用户信息的界面
     */
    public function getUser()
    {
        # 获得用户信息
        $sessionUser = session('wechat.oauth_user');
        $openid = $sessionUser->id;
        $user = User::where("openid", $openid)->first();
        # 如果用户有用户信息，将其传到view中
        if (!is_null($user->userinfo)) {
            return view('user.user', [
               'userinfo' => $user->userinfo
            ]);
        }
        return view('user.user', [
            'userinfo' => null
        ]);
    }

    /*
     * post用户信息
     */
    public function postUser(Request $request, Application $wechat)
    {
        # 验证表单
        $this->validate($request, [
            'userrealname' => 'required|regex:/^[\x7f-\xff]+$/|min:2',
            'usertelephone' => "required|numeric|regex:/^[1-9][0-9]{10}$/",
        ]);
        # 更新用户信息
        $sessionUser = session('wechat.oauth_user');
        $openid = $sessionUser->id;
        $user = User::where("openid", $openid)->first();
        # 如果用户没有用户信息，新建一个
        if (is_null($user->userinfo)) {
            $userinfo = Userinfo::create([
                'avatar' => $sessionUser->avatar,
                'nickname' => $sessionUser->nickname,
                'name' => $request->input('userrealname'),
                'telephone' => $request->input('usertelephone'),
                'user_id' => $user->id
            ]);
            $userinfo->save();
            $user = User::find($user->id);
            $user->privilege = 1;
            $user->save();
            $userGroupHelper = new UserGroupManageHelper($wechat);
            $userGroupHelper->moveToNewGroup($openid, 1);
        } else {
            # 如果用户已有用户信息，则更新用户信息
            $user->userinfo->name = $request->input('userrealname');
            $user->userinfo->telephone = $request->input('usertelephone');
            $user->userinfo->save();
        }
        return view('user.userinfo', [
            'user' => $user
        ]);
    }
}
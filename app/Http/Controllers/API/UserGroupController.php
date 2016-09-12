<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/15
 * Time: 16:51
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Log;

class UserGroupController extends Controller
{
    public function getUserGroup()
    {
        try {
            $usergroup = User::pluck('privilege')->unique();
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail', 'msg' => '未能找到用户组'], 400);
        }
        return $usergroup->toJson();
    }

    public function postGroupDepost(Request $request)
    {
        $rules = [
            'privilege' => 'required',
            'value' => 'required|numeric',
            'adminname' => 'required'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请输入正确格式的数据']);
        }
        $value = ceil((float)$request->input('value') * 100) / 100.00;
        try {
            $users = User::where('privilege', $request->input('privilege'))->get();
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail', 'msg' => '未能找到用户组'], 400);
        }
        if ($users->count() <= 0) {
            return response()->json(['type' => 'fail', 'msg' => '未能找到用户组'], 400);
        }
        try {
            $admin = Admin::where('username', $request->input('adminname'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail' ,'msg' => '充值出现问题'], 404);
        }
        if (is_null($admin)) {
            return response()->json(['type' => 'fail' ,'msg' => '充值出现问题'], 404);
        }
        foreach ($users as $user) {
            $deposit = Deposit::create([
                'user_id' => $user->id,
                'value' => $value,
                'type' => 0,
                'admin_id' => $admin->id
            ]);
            $deposit->save();
            $user->gold = (float)$user->gold + $value;
            $user->save();
        }
        return response()->json(['type' => 'success' ,'msg' => '充值成功']);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/9
 * Time: 16:49
 */

namespace App\Http\Controllers;


use App\Models\Admin;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(Request $request)
    {
        $credentials = $request->only([
            'username',
            'password'
        ]);

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['type' => 'fail','msg' => 'invalid_credentials']);
            }
        } catch (JWTException $e) {
            return response()->json(['type' => 'fail', 'msg' => 'could_not_create_token']);
        }

        return response()->json(['type' => 'success', 'token' => $token]);
    }


    /**
     * 注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {
        $rules = [
            'username' => 'required|regex:/^[\x7f-\xff]+$/|min:2',
            'password' => 'required|min:6'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请核对您输入的信息']);
        }
        $credentials = [
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password'))
        ];

        try {
            $user = User::create($credentials);
        } catch (JWTException $e) {
            return response()->json(['type' => 'fail', 'msg' => '用户已经存在']);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json(['type' => 'success', 'token' => $token]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $rules = [
            'username' => 'required|regex:/^[\x7f-\xff]+$/|min:2',
            'password' => 'required|min:6',
            'secondpassword' => 'required|same:password',
            'address' => 'required',
            'phone' => 'required'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请核对您输入的信息']);
        }
        try {
            $admin = Admin::findOrFail(1);
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail', 'msg' => '请核对您输入的信息']);
        }
        $admin->username = $request->input('username');
        $admin->password = bcrypt($request->input('password'));
        $admin->save();
        return response()->json(['type' => 'success', 'msg' => '成功更新了管理员信息']);
    }
}
<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::has('userinfo')->get();
        return $users->toJson();
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
        $user = User::created([
            'openid' => $request->input('openid'),
            'gold' => $request->input('gold'),
            'privilege' => $request->input('privilege')
        ]);
        $user->save();
        return response()->json(['type' => 'success', 'msg' => '新建用户成功']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail' ,'msg' => '未发现该用户'], 404);
        }
        return $user->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $rules = [
            'privilege' => 'required',
            'name' => 'required|regex:/^[\x7f-\xff]+$/|min:2',
            'telephone' => 'required|regex:/^[1-9][0-9]{10}$/'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请按正确格式输入。名字只能为中文。']);
        }
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail' ,'msg' => '未发现该用户'], 404);
        }
        if ($request->has('privilege')) {
            $user->privilege = $request->input('privilege');
            $user->save();
        }
        if ($request->has('name')) {
            $user->userinfo->name = $request->input('name');
            $user->userinfo->save();
        }
        if ($request->has('telephone')) {
            $user->userinfo->telephone = $request->input('telephone');
            $user->userinfo->save();
        }
        return response()->json(['type' => 'success', 'msg' => '更新用户信息成功']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail' ,'msg' => '未发现该用户'], 404);
        }
        $user->delete();
        return response()->json(['type' => 'success', 'msg' => '删除用户成功']);
    }
}

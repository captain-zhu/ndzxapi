<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deposits = Deposit::has('user')->get();
        return $deposits->toJson();
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
        $rules = [
            'user_id' => 'required|regex:/^[1-9][0-9]*$/',
            'value' => 'required|regex:/^[-]?[0-9]+(\.[0-9]+)?$/',
            'type' => 'required|regex:/^[0-1]$/'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请按格式正确输入']);
        }
        try {
            $user = User::findOrFail($request->input('user_id'));
        }  catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail' ,'msg' => '充值出现问题'], 404);
        }
        try {
            $admin = Admin::where('username', $request->input('adminname'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail' ,'msg' => '充值出现问题'], 404);
        }
        if (is_null($admin)) {
            return response()->json(['type' => 'fail' ,'msg' => '充值出现问题'], 404);
        }
        $value = ceil((float)($request->input('value')) * 100) / 100.00;
        $deposit = Deposit::create([
            'user_id' => $request->input('userid'),
            'value' => $value,
            'type' => $request->input('type'),
            'admin_id' => $admin->id
        ]);
        $deposit->save();
        $user->gold = (float)$user->gold + $value;
        $user->save();
        return response()->json(['type' => 'success', 'msg' => '充值成功']);
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
}

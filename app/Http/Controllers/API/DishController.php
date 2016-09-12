<?php

namespace App\Http\Controllers\API;

use App\Models\Dish;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Log;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'name' => 'required|max:50',
            'peppery_degree' => 'required|regex:/^[1-5]$/',
            'description' => 'required|max:255',
            'menu_id' => 'required|regex:/^[1-9][0-9]*$/'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请按正确的格式输入']);
        }
        try {
            $dish = Dish::create($request->only(['name', 'peppery_degree', 'description', 'menu_id']));
            $dish->save();
        } catch (ModelNotFoundException $e) {
            return response('未能新建菜品', 404);
        }
        return response()->json(['type' => 'success', 'msg' => '成功新建了菜品']);
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
        try {
            $dish = Dish::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response('未能找到该菜品', 404);
        }
        $rules = [
            'name' => 'required|max:50',
            'peppery_degree' => 'required|regex:/^[1-5]$/',
            'description' => 'required|max:255'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请按正确的格式输入']);
        }
        $dish->update($request->only(['name', 'peppery_degree', 'description']));
        return response()->json(['type' => 'success', 'msg' => '成功更新了菜品信息']);
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
            $dish = Dish::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['type' => 'fail', 'msg' => '未能找到该菜品']);
        }
        $dish->delete();
        return response()->json(['type' => 'success', 'msg' => '该菜品已成功删除']);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\Dinner;
use App\Models\Time;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Log;

class DinnerController extends Controller
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
        //
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
        $rules = [
            'start_time' => 'regex:/^[0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/',
            'end_time' => 'regex:/^[0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/',
            'state' => 'regex:/^[0-1]$/'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json(['type' => 'fail', 'msg' => '请输入正确的格式']);
        }
        try {
            $dinner = Dinner::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response('', 404);
        }
        $time = Time::find(1);
        Log::info($time);
        if ($request->has('start_time')) {
            if ($request->input('start_time') == $time->switch_time) {
                Log::info('same start time');
                $date = new DateTime($request->input('start_time'));
                $date->add(new DateInterval('PT1S'));
                $dinner->start_time = $date;
            } else {
                Log::info('diff start time');
                $dinner->start_time = $request->input('start_time');
            }
        }
        if ($request->has('end_time')) {
            if ($request->input('end_time') == $time->switch_time) {
                $date = new DateTime($request->input('end_time'));
                $di = new DateInterval('PT1S');
                $di->invert = 1;
                $date->add($di);
                $dinner->end_time = $date;
            } else {
                $dinner->end_time = $request->input('end_time');
            }
        }
        if ($request->has('state')) {
            $dinner->state = $request->input('state');
        }
        $dinner->save();
        return response()->json(['type' => 'success', 'msg' => '更新餐次成功']);
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

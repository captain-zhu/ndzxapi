<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/12
 * Time: 16:37
 */

namespace App\Http\Controllers\API;


use App\Helpers\TimeHelper;
use App\Http\Controllers\Controller;

class DateController extends Controller
{
    public function getDinnerDate()
    {
        return response()->json([
            'date' => TimeHelper::getDinnerDate()
        ]);
    }
}
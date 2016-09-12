<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/4
 * Time: 10:56
 */

namespace App\Helpers;

use App\Models\Time;

class TimeHelper
{
    public static function getDinnerDate()
    {
        # 获得今日的日期，如果现在的时间已经超过了switch-time,那么说明是第二天的订单
        $time = Time::find(1);
        $switchTime = $time->switch_time;
        $date = date("Y-m-d");
        if (date("H:i:s") > $switchTime) {
            $date = date("Y-m-d", strtotime("+1 day"));
        }
        return $date;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/7/29
 * Time: 11:09
 */

namespace App\Helpers;


use EasyWeChat\Foundation\Application;

class Helper
{
    protected $wechat;

    /*
     * 构造方程，让wechat实例注入
     */
    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }
}
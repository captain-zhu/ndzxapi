<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/7/29
 * Time: 14:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class User extends Model
{
    protected $fillable = [
      'openid',
      'gold',
      'privilege'
    ];

    protected $with = [
        'userinfo'
    ];

    /*
     * 构建用户和用户信息的one to one Relation
     */
    public function userinfo()
    {
        return $this->hasOne('App\Models\Userinfo');
    }
    
    /*
     * 构建用户和其订单的关联
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    /*
     * 构建用户和其订过的菜单间的关联
     */
    public function menus()
    {
        return $this->hasManyThrough('App\Models\Menu', 'App\Models\Order');
    }

    /*
     * 获得用户信息的文本
     */
    public function getUserinfoText()
    {
        if (is_null($this->userinfo)) {
            return '请完善个人信息';
        }
        $string = "您的用户信息如下：\n";
        $string .= "真实姓名： " . $this->userinfo->name . "\n";
        $string .= '微信昵称:  ' . $this->userinfo->nickname . "\n";
        $string .= '手机号码:  ' . $this->userinfo->telephone . "\n";
        $string .= '金币数:  ' . $this->gold;
        return $string;
    }
    
    
}
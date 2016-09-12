<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/2
 * Time: 11:56
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'username',
        'password',
        'address',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
     * 构建管理员与菜单的关联
     */
    public function menus()
    {
        return $this->hasMany('App\Models\Menu');
    }

    public function getContactText()
    {
        $returnString = "管理员：  " . $this->username . "\n";
        $returnString .= "办公地址:  " . $this->address . "\n";
        $returnString .= "办公方式:  " . $this->phone . "\n";
        return $returnString;
     }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/10
 * Time: 14:21
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'userid',
        'value',
        'type',
        'adminid',
        'transactionid',
        'time_end'
    ];

    protected $with = [
        'user',
        'admin'
    ];

    /*
     * 构建充值单和用户的关联
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /*
     * 构建充值单和管理员的关联
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
    
}
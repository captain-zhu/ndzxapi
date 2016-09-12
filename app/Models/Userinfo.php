<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/1
 * Time: 14:55
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Userinfo extends Model
{
    protected $fillable = [
        'avatar',
        'nickname',
        'name',
        'telephone',
        'user_id'
    ];
    
    /*
     * 建立用户信息和用户的one to one Relation
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
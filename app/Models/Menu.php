<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/2
 * Time: 11:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'value',
        'admin_id'
    ];

    protected $with = [
        'dinner',
        'dishes'
    ];

    /*
     * 构建菜单和菜品的关系
     */
    public function dishes()
    {
        return $this->hasMany('App\Models\Dish');
    }

    /*
     * 构建菜单和订过该菜单用户的关联
     */
    public function users()
    {
        return $this->hasManyThrough('App\Models\User', 'App\Models\Order');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    /*
     * 构建菜单和管理员的关联
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
    
    public function dinner()
    {
        return $this->hasOne('App\Models\Dinner');
    }

    /*
     * 获得菜单的描述文本
     */
    public function getMenuDescription()
    {
        $returnString = '';
        $i = 1;
        foreach ($this->dishes as $dish) {
            $returnString .= $i . ".  " . $dish->getDishDescription();
            $i++;
        }
        $returnString .= "价格： " . $this->value . "元\n";
        return $returnString;
    }

}
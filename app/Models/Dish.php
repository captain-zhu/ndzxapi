<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/2
 * Time: 11:42
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $table = "dishes";

    protected $fillable = [
        'name',
        'peppery_degree',
        'description',
        'menu_id'
    ];
    
    /*
     * 构建菜品和菜单的管理
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }

    /*
     * 获得菜品的描述文字
     */
    public function getDishDescription()
    {
        $returnString = '';
        $returnString .=  $this->name . "\n";
        $returnString .= "辣味程度:  " . $this->peppery_degree . "星\n";
        $returnString .= "简介:  " . mb_substr($this->description, 0, 26, "utf-8") . "\n";
        return $returnString;
    }
}
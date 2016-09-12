<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/2
 * Time: 15:54
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Dinner extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'type',
        'menu_id'
    ];

    /*
     * 构建该餐次与菜单的关联关系
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }

    /*
     * 获得该餐次的描述文字
     */
    public function getDinnerDescription()
    {
        $typeString = "";
        $returnString = '';
        switch ($this->type) {
            case 1:
                $typeString = '早餐';
                break;
            case 3:
                $typeString = '晚餐';
                break;
            default:
                $typeString = '午餐';
                break;
        }
        $returnString .= '今日' . $typeString . "\n\n";
        $returnString .= $this->menu->getMenuDescription();
        return $returnString;
    }

    public function getDinnerName()
    {

    }

}
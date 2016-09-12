<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/2
 * Time: 14:22
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'menu_id',
        'whole_value',
        'single_value',
        'count',
        'state',
        'date',
        'order'
    ];

    protected $with = [
        'user'
    ];

    /*
     * 构建订单和用户的关联
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }



    /*
     * 构建订单和菜单的关联
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }

    /*
     * 获取订单的描述文字
     */
    public function getOrderDescription()
    {
        $returnString = '';
        $returnString .= '订单日期:  ' . $this->created_at . "\n";
        $returnString .= '订餐者：  ' . $this->user->userinfo->name . "\n";
        $returnString .= '单价:  ' . $this->single_value . "\n";
        $returnString .= '数量： ' . $this->count . "\t";
        $returnString .= '总价:  ' . $this->whole_value . "\n";
        $returnString .= '餐次： ' . $this->date . "日";
        switch ($this->order) {
            case 1:
                $returnString .= "早餐\n";
                break;
            case 3:
                $returnString .= "晚餐\n";
                break;
            default:
                $returnString .= "午餐\n";
                break;
        }
        $returnString .= '订单状态：  ';
        switch ($this->state) {
            case 1:
                $returnString .= "已取餐\n";
                break;
            case 2:
                $returnString .= "已取消\n";
                break;
            default:
                $returnString .= "未取餐\n";
                break;
        }
        return $returnString;
    }

    public function getOrderName()
    {
        $name = $this->date;
        switch ($this->order) {
            case 1:
                $name .= '日早餐';
                break;
            case 3:
                $name .= '日晚餐';
                break;
            default:
                $name .= '日午餐';
                break;
        }
        return $name;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/8/2
 * Time: 9:36
 */

namespace App\Helpers;

use Log;

class UserGroupManageHelper extends Helper
{
    /*
     * 根据用户的类别，将用户移动到相应的分组
     *
     * @param $openid 用户微信的openid
     *
     * @param $type 用户的类型:0未完善用户资料，1完善了用户资料但是未认证，2已经认证
     */
    public function moveToNewGroup($openid, $type)
    {
        $group = $this->wechat->user_group;
        switch ($type) {
            case 0:
                $group->moveUser($openid, 103);
                break;
            case 1:
                $group->moveUser($openid, 104);
                break;
            case 2:
                $group->moveUser($openid, 105);
                break;
            default:
                break;
        }
    }
}
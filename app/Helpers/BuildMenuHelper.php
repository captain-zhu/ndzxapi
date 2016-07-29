<?php
/**
 * Created by PhpStorm.
 * User: zhu yongxuan
 * Date: 2016/7/29
 * Time: 10:55
 */

namespace App\Helpers;

class BuildMenuHelper extends Helper
{
    /*
     * 构造菜单
     */
    public function buildMenu()
    {
        $this->buildNormalMenu();
        $this->buildZeroLevelMenu();
        $this->buildOneLevelMenu();
        $this->buildTwoLevelMenu();
    }

    /*
     * 构造普通版的菜单
     */
    private function buildNormalMenu()
    {
        $buttons = [
            [
                "name" => "今日菜单",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "今日早餐",
                        "key" => "breakfastMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日午餐",
                        "key" => "lunchMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日晚餐",
                        "key" => "dinnerMenu"
                    ]
                ]
            ],
            [
                "type" => "view",
                "name" => "完善信息",
                "url"  => "http://fz.garmintech.net/user"
            ]
        ];

        $this->wechat->menu->add($buttons);
    }

    /*
     * 构造普通版的菜单
     */
    private function buildZeroLevelMenu()
    {
        $buttons = [
            [
                "name" => "今日菜单",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "今日早餐",
                        "key" => "breakfastMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日午餐",
                        "key" => "lunchMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日晚餐",
                        "key" => "dinnerMenu"
                    ]
                ]
            ],
            [
                "type" => "view",
                "name" => "完善信息",
                "url"  => "http://fz.garmintech.net/user/"
            ]
        ];

        $matchRule = [
            "group_id"             => "104"
        ];

        $this->wechat->menu->add($buttons, $matchRule);
    }

    /*
     * 构造完善过用户资料者的菜单
     */
    private function buildOneLevelMenu()
    {
        $buttons = [
            [
                "name" => "今日菜单",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "今日早餐",
                        "key" => "breakfastMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日午餐",
                        "key" => "lunchMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日晚餐",
                        "key" => "dinnerMenu"
                    ]
                ]
            ],
            [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "修改信息",
                        "key"  => "fillUserInformation"
                    ],
                    [
                        "type" => "click",
                        "name" => "查看个人信息",
                        "key" => "viewUserInformation"
                    ],
                    [
                        "type" => "click",
                        "name" => "请求认证",
                        "key" => "applyAuthenticate"
                    ]
                ],
            ],
        ];

        $matchRule = [
            "group_id"             => "104"
        ];

        $this->wechat->menu->add($buttons, $matchRule);
    }

    /*
     * 构造完全认证过的用户的菜单
     */
    private function buildTwoLevelMenu()
    {
        $buttons = [
            [
                "name" => "今日菜单",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "今日早餐",
                        "key" => "breakfastMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日午餐",
                        "key" => "lunchMenu"
                    ],
                    [
                        "type" => "click",
                        "name" => "今日晚餐",
                        "key" => "dinnerMenu"
                    ]
                ]
            ],
            [
                "name" => "订餐",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "订早晨",
                        "key" => "orderBreakfast"
                    ],
                    [
                        "type" => "click",
                        "name" => "订午餐",
                        "key" => "orderLunch"
                    ],
                    [
                        "type" => "click",
                        "name" => "订晚餐",
                        "key" => "orderDinner"
                    ]
                ]
            ],
            [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "修改信息",
                        "key"  => "fillUserInformation"
                    ],
                    [
                        "type" => "click",
                        "name" => "查看个人信息",
                        "key" => "viewUserInformation"
                    ],
                    [
                        "type" => "click",
                        "name" => "充值",
                        "key" => "deposit"
                    ],
                    [
                        "type" => "click",
                        "name" => "查看今日订单",
                        "key" => "viewTodayOrders"
                    ],
                    [
                        "type" => "click",
                        "name" => "查看历史订单",
                        "key" => "viewHistoryOrders"
                    ]
                ],
            ],
        ];

        $matchRule = [
            "group_id"             => "105"
        ];

        $this->wechat->menu->add($buttons, $matchRule);
    }
}
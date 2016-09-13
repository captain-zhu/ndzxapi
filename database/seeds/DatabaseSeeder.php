<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // 塞入用户数据
        DB::table('users')->insert([
            'id' => 10,
            'openid' => 'ofNsRuC15VVXUUteWZW1UVFuUvYk',
            'gold' => 80.33,
            'privilege' => '教务组',
        ],[
            'id' => 11,
            'openid' => 'ofNsRuC15VVXUUteWZW1UVFuUvY5',
            'gold' => 5.38,
            'privilege' => '教务组',
        ],[
            'id' => 12,
            'openid' => 'ofNsRuAQCu08vU14rLrSwi1uOf8k',
            'gold' => 125,
            'privilege' => '1',
        ],[
            'id' => 13,
            'openid' => 'ofNsRuBWKKSv13mUaI4WCQdheqIE',
            'gold' => 50,
            'privilege' => '1',
        ]);
        DB::table('users')->insert([
            'openid' => 'ofNsRuC15VVXUUteWZW1UVFuUvY6',
            'gold' => 85.33,
            'privilege' => '1',
        ],[
            'openid' => 'ofNsRuC15VVXUUteWZW1UVFuUvY7',
            'gold' => 5.37,
            'privilege' => '1',
        ],[
            'openid' => 'ofNsRuAQCu08vU14rLrSwi1uOf88',
            'gold' => 115,
            'privilege' => '1',
        ],[
            'openid' => 'ofNsRuBWKKSv13mUaI4WCQdheqI9',
            'gold' => 52,
            'privilege' => '1',
        ]);
        DB::table('users')->insert([
            'openid' => 'ofNsRuC15VVXUUteWZW1UVFuUvY6',
            'gold' => 68.25,
            'privilege' => '0',
        ],[
            'openid' => 'ofNsRuC15VVXUUteWZW1UVFuUvY7',
            'gold' => 65.21,
            'privilege' => '0',
        ],[
            'openid' => 'ofNsRuAQCu08vU14rLrSwi1uOf88',
            'gold' => 165,
            'privilege' => '0',
        ],[
            'openid' => 'ofNsRuBWKKSv13mUaI4WCQdheqI9',
            'gold' => 45,
            'privilege' => '0',
        ]);
        // 塞入用户信息数据
        DB::table('userinfos')->insert([
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 10,
            'nickname' => '羊刀',
            'name' => '羊刀',
            'telephone' => '21474836474',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 11,
            'nickname' => '打发',
            'name' => '打发',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 12,
            'nickname' => '实打实',
            'name' => '实打实',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 13,
            'nickname' => '东风',
            'name' => '东风',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 14,
            'nickname' => '挨叼',
            'name' => '挨叼',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 15,
            'nickname' => '大的',
            'name' => '大的',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 16,
            'nickname' => '儿童',
            'name' => '儿童',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 17,
            'nickname' => '斯达康',
            'name' => '雕刻',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 18,
            'nickname' => '稳定',
            'name' => '通道',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 19,
            'nickname' => '来的',
            'name' => '额哦她',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 20,
            'nickname' => '特点是',
            'name' => '特殊',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 21,
            'nickname' => '哦方大同',
            'name' => '盆地',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 22,
            'nickname' => '流程',
            'name' => '额辛苦',
            'telephone' => '21474836475',
        ],[
            'avatar' => 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCJ7UY8XqafLTTD',
            'user_id' => 23,
            'nickname' => '头等舱',
            'name' => '开车呢',
            'telephone' => '21474836475',
        ]);
        // 填充管理员信息
        DB::table('admins')->insert([
            'id' => 1,
            'username' => '宁都中学',
            'password' => '$2y$10$jkV9asSFM5kw0zTxpOAkpu1PJki5Zjkas/.rztm/LRmtYCOQmbPpG',
            'address' => '宁都中学食堂管理处',
            'phone' => '12345678912',
            'auth' => 2
        ]);
        // 填充餐次信息
        DB::table('dinners')->insert([
            'id' => 1,
            'end_time' => "11:50:00",
            'start_time' => '18:10:00',
            'menu_id' => 1,
            'state' => 0
        ],[
            'id' => 2,
            'end_time' => "11:50:00",
            'start_time' => '18:00:01',
            'menu_id' => 2,
            'state' => 0
        ],[
            'id' => 3,
            'end_time' => "17:59:59",
            'start_time' => '09:00:00',
            'menu_id' => 3,
            'state' => 0
        ]);

        // 填充菜品信息
        DB::table('dishes')->insert([
            'menu_id' => 1,
            'name' => '酸菜炒牛肉',
            'peppery_degree' => 4,
            'description' => '房间开始大幅度萨芬的撒发生大姐夫是大家发撒的h',
        ],[
            'menu_id' => 1,
            'name' => '炸酱面',
            'peppery_degree' => 3,
            'description' => '打扫房间空间放口袋里撒娇啊双方的；发生经济法速度快放假撒打开',
        ],[
            'menu_id' => 1,
            'name' => '蒸蛋',
            'peppery_degree' => 1,
            'description' => '尽快发了睡觉啊分了就卡是否时间的咖啡机看到了是放假啊开始了反击卡了萨芬的撒艰苦奋斗撒净空法师贷记卡副书记快乐',
        ],[
            'menu_id' => 2,
            'name' => '小炒鱼',
            'peppery_degree' => 3,
            'description' => '爱仕达施工单位；去看了看过来看；列为全国快乐；违法情况为了；付款了范围',
        ],[
            'menu_id' => 2,
            'name' => '粉蒸鱼',
            'peppery_degree' => 3,
            'description' => '方的身份他完全额外企鹅；来看看了我；而其他就让我去莪空间客人；了我去额居然冷酷无情额家里客人；我去额就哭了；认为人家我去看；二楼',
        ],[
            'menu_id' => 2,
            'name' => '炒苦瓜',
            'peppery_degree' => 3,
            'description' => '撒地方外婆而且金额为他强迫其他空间问问他去空间',
        ],[
            'menu_id' => 3,
            'name' => '炒粉',
            'peppery_degree' => 4,
            'description' => '顺丰到品味脾气哦他完全呃投票贴我去哦哦脾气太委屈问题',
        ],[
            'menu_id' => 3,
            'name' => '小笼包',
            'peppery_degree' => 1,
            'description' => '啊双方的可我问看起来枯萎了；去卡了就是发动机卡拉蜂；沙发上',
        ],[
            'menu_id' => 3,
            'name' => '稀饭',
            'peppery_degree' => 1,
            'description' => '为武器而困扰情况为空气污染了',
        ]);

        // 填充时间信息
        DB::table('times')->insert([
            'id' => 1,
            'switch_time' => '18:00:00'
        ]);

        // 填充菜单信息
        DB::table('menus')->insert([
            'id' => 1,
            'admin_id' => 1,
            'value' => 7
        ],[
            'id' => 2,
            'admin_id' => 1,
            'value' => 8
        ],[
            'id' => 3,
            'admin_id' => 1,
            'value' => 7
        ]);
    }
}

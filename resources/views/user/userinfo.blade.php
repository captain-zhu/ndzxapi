@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-个人信息')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 面板头部开始 --}}
        <div class="panel-heading">
            您的个人信息
        </div>
        {{-- 面板头部结束 --}}
        {{-- 面板正文开始 --}}
        <div class="panel-body">
            {{-- 用户姓名开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">真实姓名:</span>
                <span class="col-xs-8">{{ $user->userinfo->name }}</span>
            </div>
            {{-- 用户姓名结束 --}}
            {{-- 微信昵称开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">微信昵称:</span>
                <span class="col-xs-8">{{ $user->userinfo->nickname }}</span>
            </div>
            {{-- 微信昵称结束 --}}
            {{-- 手机号码开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">手机号码:</span>
                <span class="col-xs-8">{{ $user->userinfo->telephone }}</span>
            </div>
            {{-- 手机号码结束 --}}
            {{-- 金币数开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">金币数:</span>
                <span class="col-xs-8">{{ $user->gold }}</span>
            </div>
            {{-- 金币数结束 --}}
            {{-- 认证状态开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">认证状态:</span>
                <span class="col-xs-8">
                    @if( $user->privilege == 1)
                        未认证,请联系管理员认证
                    @elseif($user->privilege == 2)
                        已认证
                    @else
                        请完善资料
                    @endif
                </span>
            </div>
            {{-- 认证状态结束 --}}
        </div>
        {{-- 面板正文结束 --}}
    </div>
    {{-- 面板结束 --}}
@endsection
{{-- 扩展内容结束 --}}
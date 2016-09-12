@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-未发现订单')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 面板头部开始 --}}
        <div class="panel-heading">
            未发现该订单
        </div>
        {{-- 面板头部结束 --}}
        {{-- 面板正文开始 --}}
        <div class="panel-body">
            {{-- 失败文字开始 --}}
            <h3>您搜索的订单不存在</h3>
            <h5>如有疑问请联系管理员</h5>
            <hr>
            {{-- 失败文字结束 --}}
            {{-- 管理员姓名开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">管理员:</span>
                <span class="col-xs-8">{{ $admin->username }}</span>
            </div>
            {{-- 管理员姓名结束 --}}
            {{-- 管理员电话开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">办公电话:</span>
                <span class="col-xs-8">{{ $admin->phone }}</span>
            </div>
            {{-- 管理员电话结束 --}}
            {{-- 办公地址开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">办公地址:</span>
                <span class="col-xs-8">{{ $admin->address }}</span>
            </div>
            {{-- 办公地址结束 --}}
        </div>
        {{-- 面板正文结束 --}}
    </div>
    {{-- 面板结束 --}}
@endsection
{{-- 扩展内容结束 --}}
@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-订单信息')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 面板头部开始 --}}
        <div class="panel-heading">
            订单信息
        </div>
        {{-- 面板头部结束 --}}
        {{-- 面板正文开始 --}}
        <div class="panel-body">
            {{-- 下单用户开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">下单用户:</span>
                <span class="col-xs-8">{{ $order->user->userinfo->name }}</span>
            </div>
            {{-- 下单用户结束 --}}
            {{-- 订单名开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">订单名:</span>
                <span class="col-xs-8">{{ $order->getOrderName() }}</span>
            </div>
            {{-- 订单名结束 --}}
            {{-- 单价开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">单价:</span>
                <span class="col-xs-8">{{ $order->single_value }}元</span>
            </div>
            {{-- 单价结束 --}}
            {{-- 份数开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">份数:</span>
                <span class="col-xs-8">{{ $order->count }}份</span>
            </div>
            {{-- 份数结束 --}}
            {{-- 总价开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">总价:</span>
                <span class="col-xs-8">{{ $order->whole_value }}元</span>
            </div>
            {{-- 总价结束 --}}
            {{-- 订单状态开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">订单状态:</span>
                <span class="col-xs-8">
                    @if( $order->state == 1)
                        已取餐
                    @elseif($order->state == 2)
                        已取消
                    @else
                        尚未取餐
                    @endif
                </span>
            </div>
            {{-- 订单状态结束 --}}
        </div>
        {{-- 面板正文结束 --}}
    </div>
    {{-- 面板结束 --}}
@endsection
{{-- 扩展内容结束 --}}
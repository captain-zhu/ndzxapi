@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-历史订单')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 列表开始 --}}
        <div class="list-group">
            @foreach($orders as $order)
                <a href="{{'/orders/' . $order->id}}" class="list-group-item list-group-item-action">
                    {{-- 列表行开始 --}}
                    @if( $order->state == 1)
                        <span class="badge badge-info pull-xs-right">
                            已取餐
                        </span>
                    @elseif($order->state == 2)
                        <span class="badge badge-danger pull-xs-right">
                            已取消
                        </span>
                    @else
                        <span class="badge badge-warning pull-xs-right">
                            未取餐
                        </span>
                    @endif
                    <h5 class="list-group-item-heading">{{ $order->getOrderName() }}</h5>
                    <p class="list-group-item-text">份数:{{ $order->count }}份&nbsp;单价:{{ $order->single_value }}元&nbsp;总价:{{ $order->whole_value }}元</p>
                    {{-- 列表行结束 --}}
                </a>
            @endforeach
        </div>
        {{-- 列表结束 --}}
        {{-- paginate开始 --}}
        {{ $orders->links() }}
        {{-- paginate结束 --}}
    </div>
    {{-- 面板结束 --}}
@endsection
{{-- 扩展内容结束 --}}
@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-订餐')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 面板头部开始 --}}
        <div class="panel-heading">
            订{{ $dinnerName }}
        </div>
        {{-- 面板头部结束 --}}
        {{-- 面板正文开始 --}}
        <div class="panel-body">
            {{-- 错误信息开始 --}}
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- 错误信息结束 --}}
            {{-- 餐次名开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">餐次:</span>
                <span class="col-xs-8">{{ $date . "日" . $dinnerName }}</span>
            </div>
            {{-- 餐次名结束 --}}
            {{-- 单价开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">单价:</span>
                <span class="col-xs-8">{{ $dinner->menu->value }}元</span>
            </div>
            {{-- 单价结束 --}}
            {{-- 总价开始 --}}
            <div class="col-xs-12">
                <span class="col-xs-4 text-right">总价:</span>
                <span class="col-xs-8" id="dinnerWholeValue">{{ (Request::old('dinnernumber') || 1) *  $dinner->menu->value }}元</span>
            </div>
            {{-- 总价结束 --}}
            {{-- 如果已经有订单了 --}}
            @if( $method == 1)
                <div class="col-xs-12 text-center">
                    <br>
                    <span>你已经订过{{ $dinnerName }},份数为{{ $order->count }}</span>
                    <br>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-12">
                    <hr>
                </div>
            </div>
            {{-- 表单开始 --}}
            <form class="form-inline" method="post" action="/dinner/order">
                {{-- csrf开始 --}}
                {{ csrf_field() }}
                {{-- csrf结束 --}}
                {{-- 数量开始 --}}
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">份数</span>
                        <input type="number" class="form-control" id="dinnernumber" name="dinnernumber"
                               onchange="computeWholeValue({{ $dinner->menu->value }})" placeholder="1" value="{{ Request::old('dinnernumber') || 1 }}">
                    </div>
                </div>
                {{-- 数量结束 --}}
                {{-- 隐藏input开始 --}}
                <div class="form-group hidden">
                    <input type="text" class="form-control" id="dinnermenuid" name="dinnermenuid" value="{{ $dinner->menu->id }}">
                    <input type="text" class="form-control" id="dinnerdate" name="dinnerdate" value="{{ $date }}">
                    <input type="text" class="form-control" id="dinnertype" name="dinnertype" value="{{ $type }}">
                    @if($method == 1)
                        <input type="text" class="form-control" id="dinnerorderid" name="dinnerorderid" value="{{ $order->id }}">
                    @endif
                </div>
                {{-- 隐藏input结束 --}}
                {{-- 提交按钮开始 --}}
                <div class="form-group">
                    <button type="submit" id="userInputSubmit" class="btn btn-info center-block">
                        @if($method == 1)
                            更新份数
                        @else
                            确定订餐
                        @endif
                    </button>
                </div>
                {{-- 提交按钮结束 --}}
            </form>
            {{-- 表单结束 --}}
        </div>
        {{-- 面板正文结束 --}}
    </div>
    {{-- 面板结束 --}}
@endsection
{{-- 扩展内容结束 --}}
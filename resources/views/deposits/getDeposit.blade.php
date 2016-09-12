@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-充值')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 面板头部开始 --}}
        <div class="panel-heading">
            充值
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
            {{-- 表单开始 --}}
            <form class="form-inline" method="post" action="/deposit">
                {{-- csrf开始 --}}
                {{ csrf_field() }}
                {{-- csrf结束 --}}
                {{-- 金额开始 --}}
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">金额</span>
                        <input type="number" class="form-control" id="depositvalue" name="depositvalue"
                               placeholder="10" value="{{ Request::old('dinnernumber') || 10 }}">
                        <span class="input-group-addon">元</span>
                    </div>
                </div>
                {{-- 金额结束 --}}
                {{-- 提交按钮开始 --}}
                <div class="form-group">
                    <button type="submit" id="depositInputSubmit" class="btn btn-info center-block">
                        确定
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
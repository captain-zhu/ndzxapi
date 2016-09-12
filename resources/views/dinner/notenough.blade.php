@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-金钱不足')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 面板头部开始 --}}
        <div class="panel-heading">
            金币不足
        </div>
        {{-- 面板头部结束 --}}
        {{-- 面板正文开始 --}}
        <div class="panel-body">
            {{-- 失败文字开始 --}}
            <h3>您的金币数不足，请及时充值</h3>
            {{-- 失败文字结束 --}}
        </div>
        {{-- 面板正文结束 --}}
    </div>
    {{-- 面板结束 --}}
@endsection
{{-- 扩展内容结束 --}}
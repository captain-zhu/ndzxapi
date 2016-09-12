@extends('layouts.app')

{{-- 扩展本页标题 --}}
@section('title', '宁都中学订餐系统-完善个人信息')
{{-- 扩展结束--}}

{{-- 扩展内容 --}}
@section('content')
    {{-- 面板开始 --}}
    <div class="panel panel-info top-panel">
        {{-- 面板头部开始 --}}
        <div class="panel-heading">
            完善/修改您的个人信息
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
            {{-- 如果用户已经有用户信息 --}}
            @if($userinfo)
                {{-- hint开始 --}}
                <div class="col-xs-12 text-center">
                    <h5>您当前的用户信息</h5>
                </div>
                {{-- hint结束 --}}
                {{-- 用户姓名开始 --}}
                <div class="col-xs-12">
                    <span class="col-xs-4 text-right">真实姓名:</span>
                    <span class="col-xs-8">{{ $userinfo->name }}</span>
                </div>
                {{-- 用户姓名结束 --}}
                {{-- 联系方式开始 --}}
                <div class="col-xs-12">
                    <span class="col-xs-4 text-right">手机号码:</span>
                    <span class="col-xs-8">{{ $userinfo->telephone }}</span>
                </div>
                {{-- 联系方式结束 --}}
                <div class="row">
                    <div class="col-xs-12">
                        <hr>
                    </div>
                </div>
            @endif
            {{-- 表单开始 --}}
            <form method="post" action="/user">
                {{-- csrf开始 --}}
                {{ csrf_field() }}
                {{-- csrf结束 --}}
                {{-- 真实姓名开始 --}}
                <div class="form-group">
                    <label for="userInputRealname">真实姓名</label>
                    <input type="text" class="form-control" id="userrealname" name="userrealname" placeholder="真实姓名" value="{{ Request::old('userrealname') }}">
                </div>
                {{-- 真实姓名结束 --}}
                {{-- 手机号码开始 --}}
                <div class="form-group">
                    <label for="userInputTelephone">手机号码</label>
                    <input type="text" class="form-control" id="usertelephone" name="usertelephone" placeholder="手机号码" value="{{ Request::old('usertelephone') }}">
                </div>
                {{-- 手机号码结束 --}}
                {{-- 提交按钮开始 --}}
                <div class="form-group">
                    <button type="submit" id="userInputSubmit" class="btn btn-info center-block">确认</button>
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
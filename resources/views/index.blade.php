<!DOCTYPE html>
<html lang="en" ng-app="ndzxApp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head
         content must come *after* these tags -->

    <title>宁都中学订餐系统</title>
    <!-- Bootstrap -->
    <!-- 以下的注释是为了使两个注释之间的css文件合为一个 -->
    <!-- build:css styles/main.css -->
    <link href='{{ URL::asset('angular/node_modules/bootstrap/dist/css/bootstrap.min.css') }}' rel="stylesheet">
    <link href='{{ URL::asset('angular/node_modules/bootstrap/dist/css/bootstrap-theme.min.css') }}' rel="stylesheet">
    <link href='{{ URL::asset('angular/node_modules/font-awesome/css/font-awesome.min.css') }}' rel="stylesheet">
    <link href='{{ URL::asset('angular/node_modules/ng-dialog/css/ngDialog.min.css') }}' rel="stylesheet">
    <link href='{{ URL::asset('angular/node_modules/ng-dialog/css/ngDialog-theme-plain.min.css') }}' rel="stylesheet">
    <link href='{{ URL::asset('angular/node_modules/ng-dialog/css/ngDialog-theme-default.min.css') }}' rel="stylesheet">
    <link href='{{ URL::asset('angular/app/styles/mystyles.css') }}' rel="stylesheet">
    <!-- endbuild -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div ui-view="header"></div>

<div ui-view="content"></div>

<div ui-view="footer"></div>

<!-- 以下的注释是为了使两个注释之间的js文件合为一个 -->
<!-- build:js scripts/main.js -->
<script src='{{ URL::asset('angular/node_modules/angular/angular.min.js') }}'></script>
<script src='{{ URL::asset('angular/node_modules/angular-i18n/angular-locale_zh-cn.js') }}'></script>
<script src="{{ URL::asset('angular/node_modules/angular-animate/angular-animate.min.js') }}"></script>
<script src="{{ URL::asset('angular/node_modules/angular-touch/angular-touch.min.js') }}"></script>
<script src="{{ URL::asset('angular/node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js') }}"></script>
<script src='{{ URL::asset('angular/node_modules/angular-ui-router/release/angular-ui-router.min.js') }}'></script>
<script src='{{ URL::asset('angular/node_modules/angular-resource/angular-resource.min.js') }}'></script>
<script src='{{ URL::asset('angular/node_modules/ng-dialog/js/ngDialog.min.js') }}'></script>
<script src='{{ URL::asset('angular/app/scripts/app.js') }}'></script>
<script src='{{ URL::asset('angular/app/scripts/controllers.js') }}'></script>
<script src='{{ URL::asset('angular/app/scripts/services.js') }}'></script>
<script src='{{ URL::asset('angular/app/scripts/filters.js') }}'></script>
<!-- endbuild -->

</body>

</html>
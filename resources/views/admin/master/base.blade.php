<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('admins/images/favicon.ico') }}">
    <title>管理后台</title>
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link href="{{ asset('admins/css/dashboard.css') }}" rel="stylesheet">
    @yield('css')
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="javascript:;">管理后台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ URL('admin/show') }}">演出列表</a></li>
                <li><a href="{{ URL('admin/show/tj') }}">演出统计</a></li>
                <li><a href="{{ URL('admin/order') }}">挂单列表</a></li>
                <li><a href="{{ URL('admin/order/tj') }}">挂单统计</a></li>
                <li><a href="{{ URL('admin/user') }}">卖家列表</a></li>
                <li><a href="{{ URL('admin/user/tj') }}">卖家统计</a></li>
                <li><a href="{{ URL('admin/venue') }}">场馆列表</a></li>
                <li><a href="{{ URL('admin/logout') }}">退出</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <h3>演出管理</h3>
            <ul class="nav nav-sidebar">
                <li @if (Request::is('admin/show')) class="active" @endif>
                    <a href="{{ URL('admin/show') }}">演出列表</a>
                </li>
                <li @if (Request::is('admin/show/create')) class="active" @endif>
                    <a href="{{ URL('admin/show/create') }}">添加演出</a>
                </li>
                <li @if (Request::is('admin/show/tj')) class="active" @endif>
                    <a href="{{ URL('admin/show/tj') }}">演出统计</a>
                </li>
            </ul>
            <h3>挂单管理</h3>
            <ul class="nav nav-sidebar">
                <li @if (Request::is('admin/order')) class="active" @endif>
                    <a href="{{ URL('admin/order') }}">挂单列表</a>
                </li>
                <li @if (Request::is('admin/order/tj')) class="active" @endif>
                    <a href="{{ URL('admin/order/tj') }}">挂单统计</a>
                </li>
            </ul>
            <h3>卖家管理</h3>
            <ul class="nav nav-sidebar">
                <li @if (Request::is('admin/user')) class="active" @endif>
                    <a href="{{ URL('admin/user') }}">卖家列表</a>
                </li>
                <li @if (Request::is('admin/user/tj')) class="active" @endif>
                    <a href="{{ URL('admin/user/tj') }}">卖家统计</a>
                </li>
            </ul>
            <h3>场馆管理</h3>
            <ul class="nav nav-sidebar">
                <li @if (Request::is('admin/venue')) class="active" @endif>
                    <a href="{{ URL('admin/venue') }}">场馆列表</a>
                </li>
                <li @if (Request::is('admin/venue/create')) class="active" @endif>
                    <a href="{{ URL('admin/venue/create') }}">添加场馆</a>
                </li>
            </ul>
            <h3>管理员管理</h3>
            <ul class="nav nav-sidebar">
                <li @if (Request::is('admin/admin')) class="active" @endif>
                    <a href="{{ URL('admin/admin') }}">管理员列表</a>
                </li>
                <li @if (Request::is('admin/admin/create')) class="active" @endif>
                    <a href="{{ URL('admin/admin/create') }}">添加管理员</a>
                </li>
                <li @if (Request::is('admin/admin/platform')) class="active" @endif>
                    <a href="{{ URL('admin/admin/platform') }}">第三方平台</a>
                </li>
                <li @if (Request::is('admin/admin/urlExplain')) class="active" @endif>
                    <a href="{{ URL('admin/admin/urlExplain') }}">前台URL说明</a>
                </li>
                {{--<li><a href="">权限分配</a></li>--}}
            </ul>

            {{--<h3>运营管理</h3>
            <ul class="nav nav-sidebar">
                <li><a href="">运营管理列表</a></li>
            </ul>
            <h3>增值服务管理</h3>
            <ul class="nav nav-sidebar">
                <li><a href="">增值服务管理列表</a></li>
            </ul>
            <h3>订单管理</h3>
            <ul class="nav nav-sidebar">
                <li><a href="">订单列表</a></li>
            </ul>
            <h3>报表管理</h3>
            <ul class="nav nav-sidebar">
                <li @if (Request::is('admin/report')) class="active" @endif><a href="{{ URL('admin/report') }}">财务报表</a></li>
            </ul>--}}
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            @yield('content')
        </div>
    </div>
</div>

<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="{{ URL('admins/js/docs.min.js') }}"></script>
@yield('js')
</body>
</html>
@extends('admin.master.base')
@section('css')
    <link href="{{ asset('admins/css/bootstrap-select.css') }}" rel="stylesheet">
    <style type="text/css">
        .bootstrap-select.btn-group .dropdown-menu li {width:450px; overflow:hidden}
    </style>
@endsection

@section('content')
    <div class="page-header row">
        <h1>卖家列表</h1>
    </div>

    <div class="wrap">
        <form class="form-inline" action="{{ URL('admin/user') }}" method="get" style="margin-bottom: 30px">
            <div class="form-group">
                <input type="text" placeholder="请输入手机号或微信昵称" class="form-control" name="keyword" value="{{ $request['keyword'] or '' }}">
            </div>
            <button type="submit" class="btn btn-primary">搜索</button>
        </form>

        <div class="form-group">
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <a class="close" data-dismiss="alert">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">关闭</span>
                </a>
                <p id="J-tips"></p>
            </div>
        </div>

        <table class="table table-striped table-bordered table-h">
            <thead>
            <tr>
                <th>卖家ID</th>
                <th>头 像</th>
                <th>手机号</th>
                <th width="10%">微信昵称</th>
                <th>邀请码</th>
                <th>有效挂单数</th>
                <th>邀请人</th>
                <th>最近登录时间</th>
                <th>状 态</th>
                <th>操 作</th>
            </tr>
            </thead>
            <tbody>
            @if (count($list))
                @foreach( $list as $v)
                    <tr>
                        <td align="center">{{ $v->id }}</td>
                        <td align="center"><img src="{{ $v->Photo }}" width="60"></td>
                        <td align="center">{{ $v->MobilePhone }}</td>
                        <td align="center" class="J-realname">{{ $v->Realname }}</td>
                        <td align="center">
                            @inject('formatCode', 'App\Http\Controllers\Admin\UserController')
                            {{ $formatCode->formatCode($v->id) }}
                            {{ $v->Code }}
                        </td>
                        <td align="center">
                            @inject('getOrder', 'App\Http\Controllers\Admin\UserController')
                            {{ $getOrder->getOrder($v->id) }}
                        </td>
                        <td align="center">{{ $v->referee }}</td>
                        <td align="center">{{ $v->LastLoginTime }}</td>
                        <td align="center" class="J-status">
                            @if ($v->Status == 0)
                                已冻结
                            @elseif ($v->Status == 1)
                                有效
                            @endif
                        </td>
                        <td align="center">
                            <a class="btn btn-success" href="{{ URL('admin/user/'.$v->id) }}">查看</a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    操作
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-proxy">代挂</a></li>
                                    <li class="divider"></li>
                                    @if ($v->Status == 0)
                                        <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-thaw">解冻</a></li>
                                    @elseif ($v->Status == 1)
                                        <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-frozen">冻结</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" align="center">暂无数据</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="row">
            <div class="dataTables_paginate">
                {!! $list->appends($request)->render() !!}
            </div>
        </div>
    </div>

    <script type="text/html" id="J-proxy-tpl">
        <div class="form-group">
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <a class="close" data-dismiss="alert">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">关闭</span>
                </a>
                <p id="J-modal-tips"></p>
            </div>
        </div>

        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">微信昵称</label>
                <div class="col-sm-10">
                    <p class="form-control-static" id="J-proxy-user"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">演出名称</label>
                <div class="col-sm-10">
                    <select id="J-showName" class="selectpicker form-control" data-live-search="true" title="请选择演出" name="ShowId">
                        @foreach( $show as $v)
                            <option value="{{ $v->id }}">{{ $v->ShowName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">演出场次</label>
                <div class="col-sm-10">
                    <select id="J-showTime" class="form-control" name="ShowTimeId">
                        <option value="">请先选择演出</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">票面价</label>
                <div class="col-sm-10">
                    <select id="J-showPrice" class="form-control" name="ShowPriceId">
                        <option value="">请先选择演出和场次</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">同行价</label>
                <div class="col-sm-10">
                    <input name="PerPrice" class="form-control" type="text" placeholder="请输入同行价" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">出售数量</label>
                <div class="col-sm-10">
                    <input name="SellNum" class="form-control" type="text" placeholder="请输入出售数量" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">有效天数</label>
                <div class="col-sm-10">
                    <select class="form-control" name="restDay">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }}天</option>
                        @endfor
                    </select>
                </div>
            </div>
        </form>
    </script>
@endsection

@section('js')
    <script src="{{ URL('admins/js/bootstrap-select.js') }}"></script>
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        $(function(){
            User.init();
        });
    </script>
@endsection
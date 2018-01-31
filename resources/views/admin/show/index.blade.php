@extends('admin.master.base')

@section('css')
    <link href="{{ asset('admins/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="page-header row">
        <h1><a href="{{ URL('admin/show/create') }}" class="btn btn-warning btn-lg pull-right">添加演出</a> 演出列表</h1>
    </div>

    <div class="wrap">
        <form class="form-inline" action="{{ URL('admin/show') }}" method="get" style="margin-bottom: 30px">
            <div class="form-group">
                <select class="form-control" name="CityName">
                    <option value="全国" @if(!empty($request['CityName'])) {{ $request['CityName'] == '全国' ? 'selected' : '' }} @endif>全国</option>
                    @foreach( $city as $v)
                        <option value="{{ $v->CityName }}" @if(!empty($request['CityName'])) {{ $request['CityName'] == $v->CityName ? 'selected' : '' }} @endif>{{ $v->CityName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="text" placeholder="请输入演出名称" class="form-control" name="ShowName" value="{{ $request['ShowName'] or '' }}">
            </div>
            <div class="form-group">
                <input type="text" value="{{ $request['Start'] or '' }}" placeholder="选择演出时间" readonly class="form-control" id="datetimepicker" data-date-format="yyyy-mm-dd hh:ii:00" name="Start">
                至
                <input type="text" value="{{ $request['End'] or '' }}" placeholder="选择演出时间" readonly class="form-control" id="datetimepicker2" data-date-format="yyyy-mm-dd hh:ii:00" name="End">
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
                <th>演出ID</th>
                <th>演出封面</th>
                <th width="25%">演出名称</th>
                <th width="15%">演出场次</th>
                <th>演出城市</th>
                <th width="15%">演出场馆</th>
                <th>状 态</th>
                <th>操 作</th>
            </tr>
            </thead>
            <tbody>
            @if (count($list))
                @foreach($list as $v)
                    <tr>
                        <td align="center">{{ $v->id }}</td>
                        <td align="center"><img src="{{ $v->Photo }}" width="80"></td>
                        <td align="center">{{ $v->ShowName }}</td>
                        <td align="center">
                            @foreach($ShowTime as $vv)
                                @if ($v->id == $vv->ShowId)
                                    {{ $vv->ShowTime }}<br>
                                @endif
                            @endforeach
                        </td>
                        <td align="center">{{ $v->CityName }}</td>
                        <td align="center">{{ $v->Place }}</td>
                        <td align="center" class="J-status">
                            @if ($v->Status == 0)
                                已冻结
                            @elseif ($v->Status == 1)
                                有效
                            @elseif ($v->Status == 2)
                                已过期
                            @endif
                        </td>
                        <td align="center">
                            <a href="{{ URL('admin/show/'.$v->id) }}" class="btn btn-success">查看</a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    操作
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ URL('admin/show/'.$v->id.'/edit') }}">修改</a></li>
                                    @if ($v->Status == 0)
                                        <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-thaw">解冻</a></li>
                                    @elseif ($v->Status == 1)
                                        <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-frozen">冻结</a></li>
                                    @endif
                                    <li class="divider"></li>
                                    <li>
                                        <a href="javascript:;" data-id="{{ $v->id }}" class="J-remove">删除</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" align="center">暂无数据</td>
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
@endsection

@section('js')
    <script src="{{ URL('admins/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        $(function(){
            Show.init();

            $('#datetimepicker, #datetimepicker2').datetimepicker();
        });
    </script>
@endsection
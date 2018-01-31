@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1><a href="{{ URL('admin/venue/create') }}" class="btn btn-warning btn-lg pull-right" id="ID-import-btn">添加场馆</a>场馆列表</h1>
    </div>

    <div class="wrap">
        <form class="form-inline" action="{{ URL('admin/venue') }}" method="get" style="margin-bottom: 30px">
            <div class="form-group">
                <select class="form-control" name="CityName">
                    <option value="全国" @if(!empty($request['CityName'])) {{ $request['CityName'] == '全国' ? 'selected' : '' }} @endif>全国</option>
                    @foreach( $city as $v)
                        <option value="{{ $v->CityName }}" @if(!empty($request['CityName'])) {{ $request['CityName'] == $v->CityName ? 'selected' : '' }} @endif>{{ $v->CityName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="text" placeholder="请输入场馆名称" class="form-control" name="Name" value="{{ $request['Name'] or '' }}">
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
                <th>场馆ID</th>
                <th width="20%">场馆名称</th>
                <th>所在城市</th>
                <th width="40%">场馆地址</th>
                <th>状 态</th>
                <th>操 作</th>
            </tr>
            </thead>
            <tbody>
            @if (count($list))
                @foreach( $list as $v)
                    <tr>
                        <td align="center">{{ $v->id }}</td>
                        <td align="center">{{ $v->Name }}</td>
                        <td align="center">{{ $v->CityName }}</td>
                        <td align="center">{{ $v->Address }}</td>
                        <td align="center">
                            @if ($v->Status == 1)
                                有效
                            @else
                                {{ $v->Status }}
                            @endif
                        </td>
                        <td align="center">
                            <a href="{{ URL('admin/venue/'.$v->id) }}" class="btn btn-success">查看</a>

                            <div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    操作 <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ URL('admin/venue/'.$v->id.'/edit') }}">修改</a></li>
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
                    <td colspan="6" align="center">暂无数据</td>
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
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        $(function(){
            Venue.init();
        });
    </script>
@endsection
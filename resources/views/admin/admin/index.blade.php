@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>管理员列表</h1>
    </div>

    <div class="wrap">
        <table class="table table-striped table-bordered table-h">
            <thead>
                <tr>
                    <th>管理员ID</th>
                    <th>用户名</th>
                    <th>真实姓名</th>
                    <th>最近登录时间</th>
                    <th>操 作</th>
                </tr>
            </thead>
            <tbody>
            @if (count($list))
                @foreach( $list as $v)
                    <tr>
                        <td align="center">{{ $v->id }}</td>
                        <td align="center">{{ $v->username }}</td>
                        <td align="center">{{ $v->realname }}</td>
                        <td align="center">{{ $v->last_login_time }}</td>
                        <td align="center">
                            <a href="{{ URL('admin/admin/'.$v->id) }}" class="btn btn-success">查看</a>
                            <a href="{{ URL('admin/admin/'.$v->id.'/edit') }}" class="btn btn-info">修改</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" align="center">暂无数据</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="row">
            <div class="dataTables_paginate">
                {!! $list->render() !!}
            </div>
        </div>
    </div>
@endsection
@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>{{ $res->realname }}</h1>
    </div>

    <div class="wrap">
        <table class="table table-striped table-bordered ">
            <tbody>
            <tr>
                <th>管理员ID</th>
                <td>{{ $res->id }}</td>
            </tr>
            <tr>
                <th>用户名</th>
                <td>{{ $res->username }}</td>
            </tr>
            <tr>
                <th>邮 箱</th>
                <td>{{ $res->email }}</td>
            </tr>
            <tr>
                <th>真实姓名</th>
                <td>{{ $res->realname }}</td>
            </tr>
            <tr>
                <th>手机号</th>
                <td>{{ $res->mobile_number }}</td>
            </tr>
            <tr>
                <th>身份证</th>
                <td>{{ $res->id_card }}</td>
            </tr>
            <tr>
                <th>昵 称</th>
                <td>{{ $res->nickname }}</td>
            </tr>
            <tr>
                <th>最近登录时间</th>
                <td>{{ $res->last_login_time }}</td>
            </tr>
            <tr>
                <th>备 注</th>
                <td>{{ $res->comment }}</td>
            </tr>
            <tr>
                <th>头 像</th>
                <td><img src="{{ $res->photo }}"></td>
            </tr>
            <tr>
                <th>禁用时间</th>
                <td>{{ $res->disable_time }}</td>
            </tr>
            <tr>
                <th>最近登录ip</th>
                <td>{{ $res->LastLoginIp }}</td>
            </tr>
            <tr>
                <th>常用ip</th>
                <td>{{ $res->CommonIp }}</td>
            </tr>
            <tr>
                <th>密码计数</th>
                <td>{{ $res->PasswordCount }}</td>
            </tr>
            <tr>
                <th>状 态</th>
                <td>
                    @if ($res->status == 0)
                        冻结
                    @elseif($res->status == 1)
                        有效
                    @elseif($res->status == 4)
                        删除
                    @endif
                </td>
            </tr>
            <tr>
                <th>注册时间</th>
                <td>{{ $res->register_time }}</td>
            </tr>
            @if ($res->UpdateOn)
                <tr>
                    <th>更新时间</th>
                    <td>{{ $res->UpdateOn }}</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="form-group">
            <a href="{{ URL('admin/admin/'.$res->id.'/edit') }}" class="btn btn-success btn-lg">修改</a>
        </div>
    </div>
@endsection
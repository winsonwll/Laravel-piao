@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1 class="col-sm-9">{{ $res->Name }}</h1>
    </div>

    <div class="wrap">
        <div class="form-group">
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <a class="close" data-dismiss="alert">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">关闭</span>
                </a>
                <p id="J-tips"></p>
            </div>
        </div>

        <table class="table table-striped table-bordered ">
            <tbody>
            <tr>
                <th>场馆ID</th>
                <td>{{ $res->id }}</td>
            </tr>
            <tr>
                <th>场馆名称</th>
                <td>{{ $res->Name }}</td>
            </tr>
            <tr>
                <th>所在城市</th>
                <td>{{ $res->CityName }}</td>
            </tr>
            <tr>
                <th>场馆地址</th>
                <td>{{ $res->Address }}</td>
            </tr>
            <tr>
                <th>简 介</th>
                <td>{{ $res->Introduce }}</td>
            </tr>
            <tr>
                <th>座位数</th>
                <td>{{ $res->SeatNum }}</td>
            </tr>
            <tr>
                <th>备 注</th>
                <td>{{ $res->Remark }}</td>
            </tr>
            <tr>
                <th>状 态</th>
                <td>
                    @if ($res->Status == 1)
                        有效
                    @else
                        {{ $v->Status }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>创建时间</th>
                <td>{{ $res->CreatOn }}</td>
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
            <a href="{{ URL('admin/venue/'.$res->id.'/edit') }}" class="btn btn-success btn-lg">修改</a>
            <a href="javascript:;" class="btn btn-default btn-lg" id="J-remove" data-id="{{ $res->id }}">删除</a>
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
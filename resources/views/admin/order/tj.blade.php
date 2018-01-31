@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>挂单统计</h1>
    </div>

    <div class="wrap">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <th>挂单总数</th>
                <td>{{ $orderCount }}</td>
            </tr>
            <tr>
                <th>有效挂单数</th>
                <td>{{ $onlineOrderCount }}</td>
            </tr>
            <tr>
                <th>在售票总数</th>
                <td>{{ $sellOrderCount }}</td>
            </tr>
            <tr>
                <th>已下架挂单数</th>
                <td>{{ $offlineOrderCount }}</td>
            </tr>
            <tr>
                <th>浏览总数</th>
                <td>{{ $viewOrderCount }}</td>
            </tr>
            <tr>
                <th>联系总数</th>
                <td>{{ $linkOrderCount }}</td>
            </tr>
            </tbody>
        </table>

        <div class="panel panel-default">
            <div class="panel-heading"><h3>统计详情</h3></div>

            <table class="table table-striped table-bordered table-h">
                <thead>
                <tr>
                    <th>挂单ID</th>
                    <th>微信昵称</th>
                    <th>手机号</th>
                    <th width="20%">演出名称</th>
                    <th width="15%">演出场次</th>
                    <th width="15%">演出场馆</th>
                    <th>票面价</th>
                    <th>同行价</th>
                    <th>售票总数</th>
                    <th>浏览总数</th>
                    <th>联系总数</th>
                    <th width="15%">挂单时间</th>
                    <th width="15%">有效期至</th>
                    <th>状 态</th>
                </tr>
                </thead>
                <tbody>
                @if (count($list))
                    @foreach( $list as $v)
                        <tr>
                            <td align="center">{{ $v->id }}</td>
                            <td align="center">{{ $v->UserRealName }}</td>
                            <td align="center">
                                @inject('phone', 'App\Http\Controllers\Admin\OrderController')
                                {{ $phone->getUser($v->UserId)->MobilePhone }}
                            </td>
                            <td align="center">{{ $v->ShowName }}</td>
                            <td align="center">{{ $v->ShowTime }}</td>
                            <td align="center">{{ $v->VenueName }}</td>
                            <td align="center">{{ $v->AreaName }}</td>
                            <td align="center">{{ $v->PerPrice }}</td>
                            <td align="center">{{ $v->SellNum }}</td>
                            <td align="center">{{ $v->views }}</td>
                            <td align="center">
                                @inject('order', 'App\Http\Controllers\Admin\OrderController')
                                {{ $order->getLinkhistoryById($v->id, 'OrderId')->count() }}
                            </td>
                            <td align="center">{{ $v->CreatOn }}</td>
                            <td align="center">{{ $v->deadline }}</td>
                            <td align="center">
                                @if ($v->Status == 0)
                                    草稿
                                @elseif ($v->Status == 1)
                                    在售
                                @elseif ($v->Status == 2)
                                    已下架
                                @elseif ($v->Status == 3)
                                    已售罄
                                @elseif ($v->Status == 4)
                                    已删除
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="14" align="center">暂无数据</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="dataTables_paginate">
                {!! $list->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        $(function(){
            Show.init();
        });
    </script>
@endsection
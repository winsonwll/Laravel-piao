@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>卖家统计</h1>
    </div>

    <div class="wrap">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <th>卖家总数</th>
                <td>{{ $userCount }}</td>
            </tr>
            <tr>
                <th>有挂单的卖家数</th>
                <td>{{ $orderUserCount }}</td>
            </tr>
            <tr>
                <th>当前有挂单的卖家数</th>
                <td>{{ $onlineOrderUserCount }}</td>
            </tr>
            <tr>
                <th>今日登录的卖家数</th>
                <td>{{ $loginUserCount }}</td>
            </tr>
            </tbody>
        </table>

        <div class="panel panel-default">
            <div class="panel-heading"><h3>统计详情</h3></div>

            <table class="table table-striped table-bordered table-h">
                <thead>
                <tr>
                    <th>卖家ID</th>
                    <th>头 像</th>
                    <th>手机号</th>
                    <th>微信昵称</th>
                    <th>演出总数</th>
                    <th>挂单总数</th>
                    <th>浏览总数</th>
                    <th>联系总数</th>
                    <th>最近登录时间</th>
                    <th>状 态</th>
                </tr>
                </thead>
                <tbody>
                @if (count($list))
                    @foreach( $list as $v)
                        @inject('order', 'App\Http\Controllers\Admin\OrderController')
                        <tr>
                            <td align="center">{{ $v->id }}</td>
                            <td align="center"><img src="{{ $v->Photo }}" width="60"></td>
                            <td align="center">{{ $v->MobilePhone }}</td>
                            <td align="center">{{ $v->Realname }}</td>
                            <td align="center">
                                {{ $order->getOrderById($v->id, 'UserId')['show'] }}
                            </td>
                            <td align="center">
                                {{ $order->getOrderById($v->id, 'UserId')['count'] }}
                            </td>
                            <td align="center">
                                {{ $order->getOrderById($v->id, 'UserId')['views'] }}
                            </td>
                            <td align="center">
                                {{ $order->getLinkhistoryById($v->id, 'UserId')->count() }}
                            </td>
                            <td align="center">{{ $v->LastLoginTime }}</td>
                            <td align="center">
                                @if ($v->Status == 0)
                                    已冻结
                                @elseif ($v->Status == 1)
                                    有效
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
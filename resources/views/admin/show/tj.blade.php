@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>演出统计</h1>
    </div>

    <div class="wrap">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <th>演出总数</th>
                <td>{{ $showCount }}</td>
            </tr>
            <tr>
                <th>演出场次总数</th>
                <td>{{ $showTimeCount }}</td>
            </tr>
            <tr>
                <th>有挂单演出数</th>
                <td>{{ $showOrderCount }}</td>
            </tr>
            <tr>
                <th>已冻结演出数</th>
                <td>{{ $showFrozenCount }}</td>
            </tr>
            <tr>
                <th>已过期演出数</th>
                <td>{{ $showExpireCount }}</td>
            </tr>
            </tbody>
        </table>

        <div class="panel panel-default">
            <div class="panel-heading"><h3>统计详情</h3></div>

            <table class="table table-striped table-bordered table-h">
                <thead>
                <tr>
                    <th>演出ID</th>
                    <th>演出封面</th>
                    <th>演出名称</th>
                    <th>演出城市</th>
                    <th>演出场馆</th>
                    <th>挂单总数</th>
                    <th>出售总数</th>
                    <th>浏览总数</th>
                    <th>联系总数</th>
                    <th>状 态</th>
                </tr>
                </thead>
                <tbody>
                @if (count($list))
                    @foreach( $list as $v)
                        @inject('order', 'App\Http\Controllers\Admin\OrderController')
                        <tr>
                            <td align="center">{{ $v->id }}</td>
                            <td><img src="{{ $v->Photo }}" width="80"></td>
                            <td align="center">{{ $v->ShowName }}</td>
                            <td align="center">{{ $v->CityName }}</td>
                            <td align="center">{{ $v->Place }}</td>
                            <td align="center">
                                {{ $order->getOrderById($v->id, 'ShowId')['count'] }}
                            </td>
                            <td align="center">
                                {{ $order->getOrderById($v->id, 'ShowId')['SellNum'] }}
                            </td>
                            <td align="center">
                                {{ $order->getOrderById($v->id, 'ShowId')['views'] }}
                            </td>
                            <td align="center">
                                {{ $order->getLinkhistoryById($v->id, 'ShowId')->count() }}
                            </td>
                            <td align="center">
                                @if ($v->Status == 0)
                                    已冻结
                                @elseif ($v->Status == 1)
                                    有效
                                @elseif ($v->Status == 2)
                                    已过期
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" align="center">暂无数据</td>
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
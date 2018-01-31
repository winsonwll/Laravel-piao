@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>{{ $res->ShowName }}</h1>
    </div>

    <div class="wrap">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" @if (!count($list)) class="active" @endif>
                <a href="#J-show-info" aria-controls="J-show-info" role="tab" data-toggle="tab">演出信息</a>
            </li>
            <li role="presentation" @if (count($list)) class="active" @endif>
                <a href="#J-order-record" aria-controls="J-order-record" role="tab" data-toggle="tab">挂单记录</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane @if (!count($list)) active @endif" id="J-show-info">
                <div class="form-group">
                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                        <a class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">关闭</span>
                        </a>
                        <p id="J-tips"></p>
                    </div>
                </div>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th>演出ID</th>
                        <td>{{ $res->id }}</td>
                    </tr>
                    <tr>
                        <th>演出名称</th>
                        <td>{{ $res->ShowName }}</td>
                    </tr>
                    <tr>
                        <th>演出封面</th>
                        <td><img src="{{ $res->Photo }}"></td>
                    </tr>
                    <tr>
                        <th>表演者</th>
                        <td>{{ $res->Performer }}</td>
                    </tr>
                    <tr>
                        <th>演出城市</th>
                        <td>{{ $res->CityName }}</td>
                    </tr>
                    <tr>
                        <th>演出场馆</th>
                        <td>{{ $res->Place }}</td>
                    </tr>
                    <tr>
                        <th>演出地址</th>
                        <td>{{ $res->Address }}</td>
                    </tr>
                    <tr class="success">
                        <th colspan="2">演出场次和票面价</th>
                    </tr>
                    @foreach($ShowTime as $k=>$v)
                        <tr>
                            <th>演出场次{{$k+1}}</th>
                            <td>
                                {{ $v->ShowTime }}
                            </td>
                        </tr>
                        <tr>
                            <th>票面价</th>
                            <td>
                                @foreach($ShowPrice[$k] as $v)
                                    {{ $v->AreaName }} /
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    @inject('order', 'App\Http\Controllers\Admin\OrderController')
                    <tr class="success">
                        <th colspan="2">统计信息</th>
                    </tr>
                    <tr>
                        <th>挂单总数</th>
                        <td>{{ $order->getOrderById($res->id, 'ShowId')['count'] }}</td>
                    </tr>
                    <tr>
                        <th>出售总数</th>
                        <td>{{ $order->getOrderById($res->id, 'ShowId')['SellNum'] }}</td>
                    </tr>
                    <tr>
                        <th>浏览总数</th>
                        <td>{{ $order->getOrderById($res->id, 'ShowId')['views'] }}</td>
                    </tr>
                    <tr>
                        <th>联系总数</th>
                        <td>{{ $order->getLinkhistoryById($res->id, 'ShowId')->count() }}</td>
                    </tr>
                    <tr>
                        <th>库存</th>
                        <td>{{ $res->Inventory }}</td>
                    </tr>
                    <tr class="success">
                        <th colspan="2">其他信息</th>
                    </tr>
                    <tr>
                        <th>演出类型</th>
                        <td>{{ $res->Note1 }}</td>
                    </tr>
                    <tr>
                        <th>演出介绍</th>
                        <td>{{ $res->Introduce }}</td>
                    </tr>
                    <tr>
                        <th>演出介绍图</th>
                        <td><img src="{{ $res->Introduce }}"></td>
                    </tr>
                    <tr>
                        <th>座位图</th>
                        <td><img src="{{ $res->SeatPic }}"></td>
                    </tr>
                    <tr>
                        <th>是否推荐</th>
                        <td>{{ $res->IsRecommend }}</td>
                    </tr>
                    <tr>
                        <th>是否周末场</th>
                        <td>{{ $res->IsWeekEnd }}</td>
                    </tr>
                    <tr>
                        <th>状态</th>
                        <td class="J-status">
                            @if ($res->Status == 0)
                                已冻结
                            @elseif ($res->Status == 1)
                                有效
                            @elseif ($res->Status == 2)
                                已过期
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
                    <a href="{{ URL('admin/show/'.$res->id.'/edit') }}" class="btn btn-success btn-lg">修改</a>
                    @if ($res->Status == 0)
                        <a href="javascript:;" class="btn btn-warning btn-lg" id="J-thaw" data-id="{{ $res->id }}">解冻</a>
                    @elseif ($res->Status == 1)
                        <a href="javascript:;" class="btn btn-warning btn-lg" id="J-frozen" data-id="{{ $res->id }}">冻结</a>
                    @endif
                    <a href="javascript:;" class="btn btn-default btn-lg" id="J-remove" data-id="{{ $res->id }}">删除</a>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane @if (count($list)) active @endif" id="J-order-record">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3>挂单记录</h3></div>
                    <table class="table table-striped table-bordered table-h">
                        <thead>
                        <tr>
                            <th>挂单ID</th>
                            <th>微信昵称</th>
                            <th>手机号</th>
                            <th width="10%">演出场次</th>
                            <th>票面价</th>
                            <th>同行价</th>
                            <th>出售数量</th>
                            <th>浏览数</th>
                            <th width="10%">挂单时间</th>
                            <th width="10%">有效期至</th>
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
                                    <td align="center">{{ $v->ShowTime }}</td>
                                    <td align="center">{{ $v->AreaName }}</td>
                                    <td align="center">{{ $v->PerPrice }}</td>
                                    <td align="center">{{ $v->SellNum }}</td>
                                    <td align="center">{{ $v->views }}</td>
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
                                <td colspan="11" align="center">暂无数据</td>
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
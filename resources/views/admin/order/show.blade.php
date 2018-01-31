@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>{{ $res->ShowName }}</h1>
    </div>

    <div class="wrap">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" @if (!count($list)) class="active" @endif>
                <a href="#J-order-info" aria-controls="J-order-info" role="tab" data-toggle="tab">挂单信息</a>
            </li>
            <li role="presentation" @if (count($list)) class="active" @endif>
                <a href="#J-link-record" aria-controls="J-link-record" role="tab" data-toggle="tab">联系记录</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane @if (!count($list)) active @endif" id="J-order-info">
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
                        <th>挂单ID</th>
                        <td>{{ $res->id }}</td>
                    </tr>
                    <tr>
                        <th>卖家ID</th>
                        <td>{{ $res->UserId }}</td>
                    </tr>
                    <tr>
                        <th>微信昵称</th>
                        <td>{{ $res->UserRealName }}</td>
                    </tr>
                    <tr>
                        <th>头像</th>
                        <td>
                            @inject('photo', 'App\Http\Controllers\Admin\OrderController')
                            <img src="{{ $photo->getUser($res->UserId)->Photo }}" width="80">
                        </td>
                    </tr>
                    <tr>
                        <th>手机号</th>
                        <td>
                            @inject('phone', 'App\Http\Controllers\Admin\OrderController')
                            {{ $phone->getUser($res->UserId)->MobilePhone }}
                        </td>
                    </tr>
                    <tr>
                        <th>演出名称</th>
                        <td>{{ $res->ShowName }}</td>
                    </tr>
                    <tr>
                        <th>演出场次</th>
                        <td>{{ $res->ShowTime }}</td>
                    </tr>
                    <tr>
                        <th>演出场馆</th>
                        <td>{{ $res->VenueName }}</td>
                    </tr>
                    <tr>
                        <th>票面价</th>
                        <td>{{ $res->AreaName }}</td>
                    </tr>
                    <tr>
                        <th>同行价</th>
                        <td>{{ $res->PerPrice }}</td>
                    </tr>
                    <tr>
                        <th>挂单时间</th>
                        <td>{{ $res->CreatOn }}</td>
                    </tr>
                    <tr>
                        <th>有效期至</th>
                        <td>{{ $res->deadline }}</td>
                    </tr>
                    <tr>
                        <th>状 态</th>
                        <td class="J-status">
                            @if ($res->Status == 0)
                                草稿
                            @elseif ($res->Status == 1)
                                在售
                            @elseif ($res->Status == 2)
                                已下架
                            @elseif ($res->Status == 3)
                                已售罄
                            @elseif ($res->Status == 4)
                                已删除
                            @endif
                        </td>
                    </tr>
                    <tr class="success">
                        <th colspan="2">统计信息</th>
                    </tr>
                    <tr>
                        <th>售票总数</th>
                        <td>{{ $res->SellNum }}</td>
                    </tr>
                    <tr>
                        <th>浏览总数</th>
                        <td>{{ $res->views }}</td>
                    </tr>
                    <tr>
                        <th>联系总数</th>
                        <td>
                            @inject('order', 'App\Http\Controllers\Admin\OrderController')
                            {{ $order->getLinkhistoryById($res->id, 'OrderId')->count() }}
                        </td>
                    </tr>
                    <tr class="success">
                        <th colspan="2">其他信息</th>
                    </tr>
                    <tr>
                        <th>有效天数</th>
                        <td>{{ $res->restDay }}</td>
                    </tr>
                    <tr>
                        <th>是否有座位</th>
                        <td>{{ $res->IsHaveSeat }}</td>
                    </tr>
                    <tr>
                        <th>座位区</th>
                        <td>{{ $res->SeatArea }}</td>
                    </tr>
                    <tr>
                        <th>座位排</th>
                        <td>{{ $res->SeatRow }}</td>
                    </tr>
                    <tr>
                        <th>座位号</th>
                        <td>{{ $res->Seats }}</td>
                    </tr>
                    <tr>
                        <th>补充</th>
                        <td>{{ $res->Additional }}</td>
                    </tr>
                    <tr>
                        <th>已售数量</th>
                        <td>{{ $res->soldNum }}</td>
                    </tr>
                    <tr>
                        <th>卖出座位号</th>
                        <td>{{ $res->SoldSeats }}</td>
                    </tr>
                    <tr>
                        <th>总价</th>
                        <td>{{ $res->TotalPrice }}</td>
                    </tr>
                    <tr>
                        <th>备注</th>
                        <td>{{ $res->Remark }}</td>
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
                    <a href="{{ URL('admin/order/'.$res->id.'/edit') }}" class="btn btn-success btn-lg">修改</a>
                    @if ($res->Status == 0)
                        <a href="javascript:;" data-id="{{ $res->id }}" id="J-onSell" class="btn btn-warning btn-lg">上架</a>
                    @elseif ($res->Status == 1)
                        <a href="javascript:;" data-id="{{ $res->id }}" id="J-offSell" class="btn btn-warning btn-lg">下架</a>
                    @elseif ($res->Status == 2)
                        <a href="javascript:;" data-id="{{ $res->id }}" id="J-onSell" class="btn btn-warning btn-lg">上架</a>
                    @endif
                </div>
            </div>
            <div role="tabpanel" class="tab-pane @if (count($list)) active @endif" id="J-link-record">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3>联系记录</h3></div>
                    <table class="table table-striped table-bordered table-h">
                        <thead>
                        <tr>
                            <th>记录ID</th>
                            <th>联系人</th>
                            <th>联系时间</th>
                            <th>状 态</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($list))
                            @foreach( $list as $v)
                                <tr>
                                    <td align="center">{{ $v->id }}</td>
                                    <td align="center">{{ $v->UserName }}</td>
                                    <td align="center">{{ $v->CreatOn }}</td>
                                    <td align="center">
                                        @if ($v->Status == 1)
                                            有效
                                        @elseif ($v->Status == 4)
                                            被清除
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" align="center">暂无数据</td>
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
            Order.init();
        });
    </script>
@endsection
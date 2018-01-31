@extends('admin.master.base')
@section('css')
    <link href="{{ asset('admins/css/bootstrap-select.css') }}" rel="stylesheet">
    <style type="text/css">
        .bootstrap-select.btn-group .dropdown-menu li {width:450px; overflow:hidden}
    </style>
@endsection

@section('content')
    <div class="page-header row">
        <h1>{{ $res->Realname }}</h1>
    </div>

    <div class="wrap">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" @if (!count($list)) class="active" @endif>
                <a href="#J-user-info" aria-controls="J-user-info" role="tab" data-toggle="tab">卖家信息</a>
            </li>
            <li role="presentation" @if (count($list)) class="active" @endif>
                <a href="#J-order-record" aria-controls="J-order-record" role="tab" data-toggle="tab">挂单记录</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane @if (!count($list)) active @endif" id="J-user-info">
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
                        <th>卖家ID</th>
                        <td>{{ $res->id }}</td>
                    </tr>
                    <tr>
                        <th>姓名</th>
                        <td>{{ $res->Name }}</td>
                    </tr>
                    <tr>
                        <th>头像</th>
                        <td><img src="{{ $res->Photo }}" width="80"></td>
                    </tr>
                    <tr>
                        <th>手机号</th>
                        <td>{{ $res->MobilePhone }}</td>
                    </tr>
                    <tr>
                        <th>微信昵称</th>
                        <td class="J-realname">{{ $res->Realname }}</td>
                    </tr>
                    <tr>
                        <th>TA的邀请码</th>
                        <td>
                            @inject('formatCode', 'App\Http\Controllers\Admin\UserController')
                            {{ $formatCode->formatCode($res->id) }}
                            {{ $res->Code }}
                        </td>
                    </tr>
                    <tr>
                        <th>有效挂单数</th>
                        <td>
                            @inject('getOrder', 'App\Http\Controllers\Admin\UserController')
                            {{ $getOrder->getOrder($res->id) }}
                        </td>
                    </tr>
                    <tr>
                        <th>邀请人</th>
                        <td>{{ $res->referee }}</td>
                    </tr>
                    @inject('order', 'App\Http\Controllers\Admin\OrderController')
                    <tr class="success">
                        <th colspan="2">统计信息</th>
                    </tr>
                    <tr>
                        <th>演出总数</th>
                        <td>{{ $order->getOrderById($res->id, 'UserId')['show'] }}</td>
                    </tr>
                    <tr>
                        <th>挂单总数</th>
                        <td>{{ $order->getOrderById($res->id, 'UserId')['count'] }}</td>
                    </tr>
                    <tr>
                        <th>浏览总数</th>
                        <td>{{ $order->getOrderById($res->id, 'UserId')['views'] }}</td>
                    </tr>
                    <tr>
                        <th>联系总数</th>
                        <td>{{ $order->getLinkhistoryById($res->id, 'UserId')->count() }}</td>
                    </tr>
                    <tr class="success">
                        <th colspan="2">其他信息</th>
                    </tr>
                    <tr>
                        <th>性别</th>
                        <td>
                            @if ($res->Sex == 0)
                                未知
                            @elseif ($res->Sex == 1)
                                男
                            @elseif ($res->Sex == 4)
                                女
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>生日</th>
                        <td>{{ $res->BirthDay }}</td>
                    </tr>
                    <tr>
                        <th>身份证</th>
                        <td>{{ $res->IdCard }}</td>
                    </tr>
                    <tr>
                        <th>微信设置地址</th>
                        <td>{{ $res->Adress }}</td>
                    </tr>
                    <tr>
                        <th>最近登录时间</th>
                        <td>{{ $res->LastLoginTime }}</td>
                    </tr>
                    <tr>
                        <th>最近登录IP</th>
                        <td>{{ $res->LastLoginIp }}</td>
                    </tr>
                    <tr>
                        <th>常用IP</th>
                        <td>{{ $res->CommonIp }}</td>
                    </tr>
                    <tr>
                        <th>LinkWay</th>
                        <td>{{ $res->LinkWay }}</td>
                    </tr>
                    <tr>
                        <th>LinkNo</th>
                        <td>{{ $res->LinkNo }}</td>
                    </tr>
                    <tr>
                        <th>状 态</th>
                        <td class="J-status">
                            @if ($res->Status == 0)
                                已冻结
                            @elseif ($res->Status == 1)
                                有效
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>注册时间</th>
                        <td>{{ $res->RegisterTime }}</td>
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
                    <a href="javascript:;" class="btn btn-success btn-lg" id="J-proxy" data-id="{{ $res->id }}">代挂</a>
                    @if ($res->Status == 0)
                        <a href="javascript:;" class="btn btn-warning btn-lg" id="J-thaw" data-id="{{ $res->id }}">解冻</a>
                    @elseif ($res->Status == 1)
                        <a href="javascript:;" class="btn btn-warning btn-lg" id="J-frozen" data-id="{{ $res->id }}">冻结</a>
                    @endif
                </div>
            </div>
            <div role="tabpanel" class="tab-pane @if (count($list)) active @endif" id="J-order-record">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3>挂单记录</h3></div>
                    <table class="table table-striped table-bordered table-h">
                        <thead>
                        <tr>
                            <th width="5%">挂单ID</th>
                            <th width="20%">演出名称</th>
                            <th width="10%">演出场次</th>
                            <th width="15%">演出场馆</th>
                            <th>票面价</th>
                            <th>同行价</th>
                            <th width="5%">出售数量</th>
                            <th width="5%">浏览数</th>
                            <th width="10%">挂单时间</th>
                            <th width="10%">有效期至</th>
                            <th width="5%">状 态</th>
                            {{--<th width="15%">备 注</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($list))
                            @foreach( $list as $v)
                                <tr>
                                    <td align="center">{{ $v->id }}</td>
                                    <td align="center">{{ $v->ShowName }}</td>
                                    <td align="center">{{ $v->ShowTime }}</td>
                                    <td align="center">{{ $v->VenueName }}</td>
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
                                    {{--<td>
                                        <p>有效天数：{{ $v->restDay }}</p>
                                        <p>是否有座位：{{ $v->IsHaveSeat }}</p>
                                        <p>座位区：{{ $v->SeatArea }}</p>
                                        <p>座位排：{{ $v->SeatRow }}</p>
                                        <p>座位号：{{ $v->Seats }}</p>
                                        <p>补充：{{ $v->Additional }}</p>
                                        <p>已售数量：{{ $v->soldNum }}</p>
                                    </td>--}}
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

    <script type="text/html" id="J-proxy-tpl">
        <div class="form-group">
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <a class="close" data-dismiss="alert">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">关闭</span>
                </a>
                <p id="J-modal-tips"></p>
            </div>
        </div>

        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">微信昵称</label>
                <div class="col-sm-10">
                    <p class="form-control-static" id="J-proxy-user"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">演出名称</label>
                <div class="col-sm-10">
                    <select id="J-showName" class="selectpicker form-control" data-live-search="true" title="请选择演出" name="ShowId">
                        @foreach( $show as $v)
                            <option value="{{ $v->id }}">{{ $v->ShowName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">演出场次</label>
                <div class="col-sm-10">
                    <select id="J-showTime" class="form-control" name="ShowTimeId">
                        <option value="">请先选择演出</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">票面价</label>
                <div class="col-sm-10">
                    <select id="J-showPrice" class="form-control" name="ShowPriceId">
                        <option value="">请先选择演出和场次</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">同行价</label>
                <div class="col-sm-10">
                    <input name="PerPrice" class="form-control" type="text" placeholder="请输入同行价" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">出售数量</label>
                <div class="col-sm-10">
                    <input name="SellNum" class="form-control" type="text" placeholder="请输入出售数量" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">有效天数</label>
                <div class="col-sm-10">
                    <select class="form-control" name="restDay">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }}天</option>
                        @endfor
                    </select>
                </div>
            </div>
        </form>
    </script>
@endsection

@section('js')
    <script src="{{ URL('admins/js/bootstrap-select.js') }}"></script>
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        $(function(){
            User.init();
        });
    </script>
@endsection
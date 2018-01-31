@extends('admin.master.base')

@section('css')
    <link href="{{ asset('admins/css/daterangepicker-bs3.css') }}" rel="stylesheet">
    <link href="{{ asset('admins/font-awesome-4.1.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="page-header row">
        <h1>挂单列表</h1>
    </div>

    <div class="wrap">
        <form class="form-inline" action="{{ URL('admin/order') }}" method="get" style="margin-bottom: 30px">
            <div class="form-group">
                <input type="text" placeholder="请输入卖家昵称" class="form-control" name="UserRealName" value="{{ $request['UserRealName'] or '' }}">
                <input type="text" placeholder="请输入演出名称" class="form-control" name="ShowName" value="{{ $request['ShowName'] or '' }}">
            </div>
            <div class="form-group">
                <select class="form-control" name="Status">
                    <option value="10" @if(!empty($request['Status'])) {{ $request['Status'] == 10 ? 'selected' : '' }} @endif>挂单状态</option>
                    {{--<option value="0" @if(!empty($request['Status'])) {{ $request['Status'] == 0 ? 'selected' : '' }} @endif>草稿</option>--}}
                    <option value="1" @if(!empty($request['Status'])) {{ $request['Status'] == 1 ? 'selected' : '' }} @endif>在售</option>
                    <option value="2" @if(!empty($request['Status'])) {{ $request['Status'] == 2 ? 'selected' : '' }} @endif>下架</option>
                    <option value="3" @if(!empty($request['Status'])) {{ $request['Status'] == 3 ? 'selected' : '' }} @endif>售罄</option>
                    <option value="4" @if(!empty($request['Status'])) {{ $request['Status'] == 4 ? 'selected' : '' }} @endif>删除</option>
                </select>
            </div>
            <div class="input-group">
                <input type="text" placeholder="选择挂单时间" class="form-control" id="dateTimeRange" readonly  style="width:330px" value="@if(!empty($request['Start'])) {{ $request['Start'].' 至 '.$request['End']}} @endif">
                <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
                <input type="hidden" name="Start" id="beginTime" value="">
                <input type="hidden" name="End" id="endTime" value="">
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
                <th>挂单ID</th>
                <th>微信昵称</th>
                <th>手机号</th>
                <th width="20%">演出名称</th>
                <th width="15%">演出场次</th>
                <th width="15%">演出场馆</th>
                <th>票面价</th>
                <th>同行价</th>
                <th>出售数量</th>
                <th>浏览数</th>
                <th width="15%">挂单时间</th>
                <th width="15%">有效期至</th>
                <th>状 态</th>
                <th>操 作</th>
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
                        <td align="center">{{ $v->CreatOn }}</td>
                        <td align="center">{{ $v->deadline }}</td>
                        <td align="center" class="J-status">
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
                        <td align="center">
                            <a class="btn btn-success" href="{{ URL('admin/order/'.$v->id) }}">查看</a>
                            <div class="btn-group" style="margin-top: 10px;">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    操作 <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ URL('admin/order/'.$v->id.'/edit') }}">修改</a></li>
                                    <li class="divider"></li>
                                    @if ($v->Status == 0)
                                        <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-onSell">上架</a></li>
                                    @elseif ($v->Status == 1)
                                        <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-offSell">下架</a></li>
                                    @elseif ($v->Status == 2)
                                        <li><a href="javascript:;" data-id="{{ $v->id }}" class="J-onSell">上架</a></li>
                                    @endif
                                </ul>
                            </div>
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
        <div class="row">
            <div class="dataTables_paginate">
                {!! $list->appends($request)->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL('admins/js/moment.min.js') }}"></script>
    <script src="{{ URL('admins/js/daterangepicker-1.3.7.js') }}"></script>
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        $(function(){
            Order.init();

            //时间插件
            $('#dateTimeRange').daterangepicker({
                    applyClass: 'btn-sm btn-success',
                    cancelClass: 'btn-sm btn-default',
                    locale: {
                        applyLabel : '确定',
                        cancelLabel : '取消',
                        fromLabel : '起始时间',
                        toLabel : '结束时间',
                        customRangeLabel : '自定义',
                        daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                        monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                        firstDay : 1
                    },
                    ranges: {
                        //'最近1小时': [moment().subtract('hours',1), moment()],
                        '今日': [moment().startOf('day'), moment()],
                        '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
                        '最近7日': [moment().subtract('days', 6), moment()],
                        '最近30日': [moment().subtract('days', 29), moment()],
                        '本月': [moment().startOf("month"),moment().endOf("month")],
                        '上个月': [moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")]
                    },
                    opens: 'right',    // 日期选择框的弹出位置
                    separator: ' 至 ',
                    showWeekNumbers: true,     // 是否显示第几周

                    timePicker: true,
                    timePickerIncrement: 60, // 时间的增量，单位为分钟
                    timePicker12Hour: false, // 是否使用12小时制来显示时间

                    maxDate: moment(),           // 最大时间
                    format: 'YYYY-MM-DD HH:mm:ss' //控件中from和to 显示的日期格式

                }, function(start, end, label) { // 格式化日期显示框
                    $('#beginTime').val(start.format('YYYY-MM-DD HH:mm:ss'));
                    $('#endTime').val(end.format('YYYY-MM-DD HH:mm:ss'));
                })
                .next().on('click', function(){
                    $(this).prev().focus();
                });
            });
    </script>
@endsection
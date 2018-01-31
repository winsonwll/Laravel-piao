@extends('admin.master.base')

@section('css')
    <link href="{{ asset('admins/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('admins/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('admins/css/fileinput.css') }}" rel="stylesheet">
    <style type="text/css">
        .bootstrap-select.btn-group .dropdown-menu li {width:450px; overflow:hidden}
    </style>
@endsection

@section('content')
    <div class="page-header row">
        <h1 class="col-sm-9">修改演出</h1>
    </div>

    <div class="wrap">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#J-show-info" aria-controls="J-show-info" role="tab" data-toggle="tab">演出信息</a>
            </li>
            <li role="presentation">
                <a href="#J-show-timePrice" aria-controls="J-show-timePrice" role="tab" data-toggle="tab">场次和票面价</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="form-group">
                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <a class="close" data-dismiss="alert">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">关闭</span>
                    </a>
                    <p id="J-tips"></p>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane active" id="J-show-info">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <form class="form-horizontal" action="" method="POST" id="J-update-showInfo-form" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">演出名称：</label>
                                        <div class="col-sm-5">
                                            <input name="ShowName" class="form-control" type="text" placeholder="请输入演出名称" value="{{ $res->ShowName }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">演出封面：</label>
                                        <div class="col-sm-5 tl th">
                                            <input type="file" name="Photo" class="projectfile" value="{{ $res->Photo }}">
                                            <p class="help-block">支持jpg、jpeg、png、gif格式，大小不超过2.0M</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">表演者：</label>
                                        <div class="col-sm-5">
                                            <input name="Performer" class="form-control" type="text" placeholder="请输入表演者" value="{{ $res->Performer }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">演出类型：</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="Note1">
                                                <option value="演唱会" {{ $res->Note1 == '演唱会' ? 'selected' : '' }}>演唱会</option>
                                                <option value="音乐会" {{ $res->Note1 == '音乐会' ? 'selected' : '' }}>音乐会</option>
                                                <option value="曲苑杂坛" {{ $res->Note1 == '曲苑杂坛' ? 'selected' : '' }}>曲苑杂坛</option>
                                                <option value="话剧歌剧" {{ $res->Note1 == '话剧歌剧' ? 'selected' : '' }}>话剧歌剧</option>
                                                <option value="体育比赛" {{ $res->Note1 == '体育比赛' ? 'selected' : '' }}>体育比赛</option>
                                                <option value="舞蹈芭蕾" {{ $res->Note1 == '舞蹈芭蕾' ? 'selected' : '' }}>舞蹈芭蕾</option>
                                                <option value="度假休闲" {{ $res->Note1 == '度假休闲' ? 'selected' : '' }}>度假休闲</option>
                                                <option value="儿童亲子" {{ $res->Note1 == '儿童亲子' ? 'selected' : '' }}>儿童亲子</option>
                                                <option value="动漫" {{ $res->Note1 == '动漫' ? 'selected' : '' }}>动漫</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">演出城市：</label>
                                        <div class="col-sm-5">
                                            <select id="J-cityName" class="form-control" name="CityName">
                                                @foreach( $city as $v)
                                                    <option value="{{ $v->CityName }}" {{ $res->CityName == $v->CityName ? 'selected' : '' }}>{{ $v->CityName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">演出场馆：</label>
                                        <div class="col-sm-5">
                                            <select id="J-venue" class="selectpicker form-control" data-live-search="true" title="请选择演出场馆" name="PlaceId">
                                                @foreach( $venue as $v)
                                                    <option value="{{ $v->id }}" {{ $res->PlaceId == $v->id ? 'selected' : '' }}>{{ $v->Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">是否立即在前端显示：</label>
                                        <div class="col-sm-5">
                                            <label class="radio-inline">
                                                <input type="radio" name="Status" value="1" {{ $res->Status == 1 ? 'checked' : '' }}> 是
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="Status" value="0" {{ $res->Status == 0 ? 'checked' : '' }}> 否
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px">
                                        <div class="col-sm-5 col-sm-offset-3">
                                            {{ method_field('PUT') }}
                                            <button class="btn btn-success btn-lg" type="submit" data-id="{{ $res->id }}">提交修改</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="J-show-timePrice">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <form class="form-horizontal" action="" method="">
                                    {{--<div class="form-group">
                                        <label class="col-sm-3 control-label">演出场次：</label>
                                        <div class="col-sm-5 form-inline">
                                            <input type="text" value="" placeholder="请选择演出场次" readonly class="form-control" id="datetimepicker" data-date-format="yyyy-mm-dd hh:ii:00">
                                        </div>
                                    </div>--}}

                                    <div id="J-add-showTime">
                                        @foreach($ShowTime as $k=>$v)
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" data-showTimeId="{{ $v->id }}">{{ $v->ShowTime }}</label>
                                                <div class="col-sm-5">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>票面价名称</th>
                                                            <th>票面价</th>
                                                            <th>操作</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($ShowPrice[$k] as $vv)
                                                            <tr data-showPriceId="{{ $vv->id }}">
                                                                <td><input name="AreaName" class="form-control" type="text" placeholder="请输入票面价名称" value="{{ $vv->AreaName }}"></td>
                                                                <td><input name="AreaPrice" class="form-control" type="text" placeholder="请输入票面价" value="{{ $vv->AreaPrice }}"></td>
                                                                <td>
                                                                    <button class="btn btn-default J-delete-showPrice" type="button">删除</button>
                                                                    <button class="btn btn-success J-edit-showPrice" type="button">修改</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                        <tr class="warning">
                                                            <td><input name="AreaName" class="form-control" type="text" placeholder="请输入票面价名称"></td>
                                                            <td><input name="AreaPrice" class="form-control" type="text" placeholder="请输入票面价"></td>
                                                            <td>
                                                                <button class="btn btn-info J-add-Area" type="button">添加</button>
                                                            </td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                    <button class="btn btn-default J-delete-showTime" type="button" data-showTimeId="{{ $v->id }}">删除场次</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL('admins/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ URL('admins/js/bootstrap-select.js') }}"></script>
    <script src="{{ URL('admins/js/fileinput.js') }}"></script>
    <script src="{{ URL('admins/js/fileinput_locale_zh.js') }}"></script>
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        var $arrShowTime=[],       //存储演出场次
            $addShowTime = $('#J-add-showTime');    //添加场次区
        var iFlag_showTime=false;

        $(function(){
            /*$('#datetimepicker').datetimepicker().on('changeDate', function(){
                var $val = $.trim($(this).val());

                if($.inArray($val, $arrShowTime)===-1){
                    $arrShowTime.push($val);

                    var html = '<div class="form-group">'+
                            '<label class="col-sm-3 control-label">'+$val+'</label>'+
                            '<div class="col-sm-5">'+
                            '<table class="table table-striped table-hover">'+
                            '<thead>'+
                            '<tr>'+
                            '<th>票面价名称</th>'+
                            '<th>票面价</th>'+
                            '<th>操作</th>'+
                            '</tr>'+
                            '</thead>'+
                            '<tbody></tbody>'+
                            '<tfoot>'+
                            '<tr class="warning">'+
                            '<td><input name="AreaName" class="form-control" type="text" placeholder="请输入票面价名称"></td>'+
                            '<td><input name="AreaPrice" class="form-control" type="text" placeholder="请输入票面价"></td>'+
                            '<td>' +
                            '<button class="btn btn-default J-add-Area" type="button">添加</button>' +
                            '</td>'+
                            '</tr>'+
                            '</tfoot>'+
                            '</table>'+
                            '<button class="btn btn-default J-delete-showTime" type="button" data-showTimeId="">删除场次</button>'+
                            '</div>'+
                            '</div>';
                    $addShowTime.append(html);
                    iFlag_showTime=true;
                }else{
                    fnModal('温馨提示', "该场次已经存在！", false, 'modal-sm');
                }
            });*/
            Show.init();
        });
    </script>
@endsection

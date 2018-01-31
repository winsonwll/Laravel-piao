@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1 class="col-sm-9">修改场馆</h1>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal" action="" method="post" id="J-update-form">
                        <div class="form-group">
                            <div class="col-sm-5 col-sm-offset-3">
                                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                                    <a class="close" data-dismiss="alert">
                                        <span aria-hidden="true">×</span>
                                        <span class="sr-only">关闭</span>
                                    </a>
                                    <p id="J-tips"></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">场馆名称：</label>
                            <div class="col-sm-5">
                                <input name="Name" class="form-control" type="text" placeholder="请输入场馆名称" value="{{ $res->Name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">所在城市：</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CityName">
                                    @foreach( $city as $v)
                                        <option value="{{ $v->CityName }}" {{ $res->CityName == $v->CityName ? 'selected' : '' }}>{{ $v->CityName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">场馆地址：</label>
                            <div class="col-sm-5">
                                <input name="Address" class="form-control" type="text" placeholder="请输入场馆地址" value="{{ $res->Address }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">简 介：</label>
                            <div class="col-sm-5">
                                <textarea class="form-control" rows="3" placeholder="请输入场馆简介" name="Introduce"></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 30px">
                            <div class="col-sm-5 col-sm-offset-3">
                                <button class="btn btn-success btn-lg" type="submit" data-id="{{ $res->id }}">提交修改</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

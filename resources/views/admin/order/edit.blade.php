@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1 class="col-sm-9">修改挂单</h1>
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
                            <label class="col-sm-3 control-label">演出名称：</label>
                            <div class="col-sm-5">
                                <p class="form-control-static">{{ $res->ShowName }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">演出场次：</label>
                            <div class="col-sm-5">
                                <p class="form-control-static">{{ $res->ShowTime }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">票面价：</label>
                            <div class="col-sm-5">
                                <p class="form-control-static">{{ $res->AreaName }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">同行价：</label>
                            <div class="col-sm-5">
                                <input name="PerPrice" class="form-control" type="text" placeholder="请输入同行价" value="{{ $res->PerPrice }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">出售数量：</label>
                            <div class="col-sm-5">
                                <input name="SellNum" class="form-control" type="text" placeholder="请输入出售数量" value="{{ $res->SellNum }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">有效天数：</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="restDay">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ $res->restDay == $i ? 'selected' : '' }}>{{ $i }}天</option>
                                    @endfor
                                </select>
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
            Order.init();
        });
    </script>
@endsection

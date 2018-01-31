@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>修改管理员</h1>
    </div>

    <div class="wrap">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#J-admin-info" aria-controls="J-admin-info" role="tab" data-toggle="tab">基本信息修改</a>
            </li>
            <li role="presentation">
                <a href="#J-admin-pwd" aria-controls="J-admin-pwd" role="tab" data-toggle="tab">密码修改</a>
            </li>
        </ul>

        <div class="tab-content">
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

            <div role="tabpanel" class="tab-pane active" id="J-admin-info">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <form class="form-horizontal" action="" method="post" id="J-updateInfo-form">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">用户名：</label>
                                        <div class="col-sm-5">
                                            <p class="form-control-static">{{ $res->username }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">真实姓名：</label>
                                        <div class="col-sm-5">
                                            <input name="realname" class="form-control" type="text" placeholder="请输入真实姓名" value="{{ $res->realname }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">手机号：</label>
                                        <div class="col-sm-5">
                                            <input name="mobile_number" class="form-control" type="text" placeholder="请输入手机号" value="{{ $res->mobile_number }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">邮 箱：</label>
                                        <div class="col-sm-5">
                                            <input name="email" class="form-control" type="text" placeholder="请输入邮箱" value="{{ $res->email }}">
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

            <div role="tabpanel" class="tab-pane" id="J-admin-pwd">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <form class="form-horizontal" action="" method="post" id="J-updatePwd-form">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">用户名：</label>
                                        <div class="col-sm-5">
                                            <p class="form-control-static">{{ $res->username }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">原密码：</label>
                                        <div class="col-sm-5">
                                            <input name="oldpassword" class="form-control" type="password" placeholder="请输入原密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">新密码：</label>
                                        <div class="col-sm-5">
                                            <input name="password" class="form-control" type="password" placeholder="请输入新密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">确认新密码：</label>
                                        <div class="col-sm-5">
                                            <input name="repassword" class="form-control" type="password" placeholder="请输入确认新密码">
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
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL('admins/js/main.js') }}"></script>
    <script>
        $(function(){
            Admin.init();
        });
    </script>
@endsection

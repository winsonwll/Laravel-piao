@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>添加管理员</h1>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal" action="" method="POST" id="J-reg-form">
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
                            <label class="col-sm-3 control-label">用户名：</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" placeholder="请输入用户名" name="username" autofocus required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">密 码：</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" placeholder="请输入密码" name="password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">确认密码：</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" placeholder="请输入确认密码" name="repassword" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 30px">
                            <div class="col-sm-5 col-sm-offset-3">
                                <button class="btn btn-success btn-lg" type="submit">立即添加</button>
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
            Admin.init();
        });
    </script>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('admins/images/favicon.ico') }}">
    <title>注册</title>

    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <style type="text/css">
        body { background-color: #eee; font-family: "Microsoft YaHei";}
        .container { padding-top: 100px;}
        form { max-width: 330px; padding: 15px; margin: 0 auto; background-color: #fff; border-radius: 10px;}
        dl { padding-top: 0; margin: 0;}
        form dt { font-size: 30px;}
        form dd { margin-top: 15px;}
        form dd .form-control { height: auto; -webkit-box-sizing: border-box; -moz-box-sizing: border-box;  box-sizing: border-box; padding: 10px; font-size: 16px;}
        dd.alert { display: none;}
    </style>
</head>

<body>
<div class="container">
    <form role="form" method="post" action="" id="ID-reg-form">
        <dl>
            <dt>注 册</dt>
            <dd class="alert alert-warning alert-dismissible fade in" role="alert">
                <a class="close" data-dismiss="alert">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">关闭</span>
                </a>
                <p id="ID-tips"></p>
            </dd>
            <dd>
                <input type="text" class="form-control" placeholder="请输入用户名" name="username" autofocus required>
            </dd>
            <dd>
                <input type="password" class="form-control" placeholder="请输入密码" name="password" required>
            </dd>
            <dd>
                <input type="password" class="form-control" placeholder="请输入确认密码" name="repassword" required>
            </dd>
            <dd>
                <button class="btn btn-lg btn-primary btn-block" type="submit">注 册</button>
            </dd>
        </dl>
    </form>
</div>

<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script>
    var REG = (function(){
        var win = window;
        var $regForm = $('#ID-reg-form'),                  //注册表单
            $tips = $('#ID-tips'),                         //提示框
            $regBtn = $regForm.find('button'),              //注册按钮
            $name = $regForm.find('input[name=username]'),     //账号
            $pwd = $regForm.find('input[name=password]'),       //密码
            $repwd = $regForm.find('input[name=repassword]'),       //确认密码
            $msg = {                                         //提示信息
                0: '用户名不能为空！',
                1: '密码不能为空！',
                2: '确认密码不能为空！',
                3: '用户名为5-18位字符！',
                4: '密码为5-18位字符！',
                5: '两次密码输入不一致！',
                6: '注册成功！',
                7: '用户名已经存在，请重新输入！',
                8: '注册失败，账号或密码错误！'
            };

        return {
            init: function(){
                this.doReg();
            },
            //执行注册
            doReg: function(){
                var self = this;

                $regBtn.off().on('click', function(){
                    var _this = $(this);
                    var $nameVal = $.trim($name.val()),
                        $pwdVal = $.trim($pwd.val()),
                        $repwdVal = $.trim($repwd.val());

                    //检测账号 密码
                    if(self.checkName() && self.checkPwd() && self.checkRePwd()){
                        _this.hide();
                        _this.after('<button class="btn btn-lg btn-primary btn-block" type="button" disabled>注册中...</button>');

                        $.ajax({
                            type: 'POST',
                            url: '{{ URL("admin/reg") }}',
                            data: {
                                username: $nameVal,
                                password: $pwdVal,
                                repassword: $repwdVal,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json',
                            success: function (data) {
                                if(data.status == 6){
                                    setTimeout(function () {
                                        win.location.href='{{ URL("admin/login") }}';
                                    },300)
                                }
                                $tips.html(data.msg);
                                $tips.parent().show();

                                _this.show();
                                _this.next().remove();
                            }
                        });
                    }
                    return false;
                });
            },
            //检测用户名
            checkName: function(){
                var $val = $name.val();

                if($.trim($val).length!=0){
                    var re = /^[0-9a-zA-z]{5,18}$/.test($val);
                    if(!re){
                        $tips.html($msg[3]).parent().show();
                        return false;
                    }
                }else{
                    $tips.html($msg[0]).parent().show();
                    return false;
                }
                return true;
            },
            //检测密码
            checkPwd: function(){
                var $val = $pwd.val();

                if($.trim($val).length!=0){
                    var re = /^[0-9a-zA-z]{5,18}$/.test($val);
                    if(!re){
                        $tips.html($msg[4]).parent().show();
                        return false;
                    }
                }else{
                    $tips.html($msg[1]).parent().show();
                    return false;
                }
                return true;
            },
            //检测确认密码
            checkRePwd: function(){
                var $val = $repwd.val();

                if($.trim($val).length!=0){
                    if($pwd.val() !== $val){
                        $tips.html($msg[5]).parent().show();
                        return false;
                    }
                }else{
                    $tips.html($msg[2]).parent().show();
                    return false;
                }
                return true;
            }
        }
    })();

    $(function () {
        //初始化
        REG.init();
    })
</script>
</body>
</html>

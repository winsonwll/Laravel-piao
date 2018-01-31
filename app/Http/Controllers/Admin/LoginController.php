<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Gregwar\Captcha\CaptchaBuilder;
use Session;
use Crypt;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * 显示注册页
     */
    public function reg()
    {
        return view('admin.reg');
    }

    /**
     * 执行注册
     */
    public function doReg(Request $request)
    {
        //提取部分参数
        $data = $request->except(['_token']);
        $pattern = '/^[0-9a-zA-z]{5,18}$/';

        //验证账号
        if(empty($data['username'])) {
            return response()->json([
                'status' => 0,
                'msg' => '账号不能为空！'
            ]);
        }else{
            if(!preg_match($pattern, $data['username'])){
                return response()->json([
                    'status' => 3,
                    'msg' => '账号为5-18位字符！'
                ]);
            }
        }

        //验证密码
        if(empty($data['password'])) {
            return response()->json([
                'status' => 1,
                'msg' => '密码不能为空！'
            ]);
        }else{
            if(!preg_match($pattern, $data['password'])){
                return response()->json([
                    'status' => 4,
                    'msg' => '密码为5-18位字符！'
                ]);
            }
        }
        
        //验证确认密码
        if(empty($data['repassword'])) {
            return response()->json([
                'status' => 2,
                'msg' => '确认密码不能为空！'
            ]);
        }else{
            if($data['password'] !== $data['repassword']){
                return response()->json([
                    'status' => 5,
                    'msg' => '两次密码输入不一致！'
                ]);
            }
        }

        //验证账号是否已存在
        $name = Admin::where('username', $data['username'])->first();
        if(!empty($name)) {
            return response()->json([
                'status' => 7,
                'msg' => '账号已经存在，请重新输入！'
            ]);
        }

        $admin = new Admin();
        $admin->username = $data['username'];
        $admin->password = Hash::make($data['password']);
        $admin->register_time = date('Y-m-d H:i:s');
        $admin->status = 1;
        $admin->PasswordCount = 0;

        //执行添加
        $res = $admin->save();

        if($res){
            //注册成功
            return response()->json([
                'status' => 6,
                'msg' => '注册成功！'
            ]);
        }else{
            return response()->json([
                'status' => 8,
                'msg' => '注册失败，账号或密码错误！'
            ]);
        }
    }
    
    /**
     * 显示登录页
     */
    public function login()
    {
        return view('admin.login');
    }

    /**
     * 执行登录
     */
    public function doLogin(Request $request)
    {
        //提取部分参数
        $data = $request->except(['_token']);
        $pattern = '/^[0-9a-zA-z]{5,18}$/';

        //验证账号
        if(empty($data['username'])) {
            return response()->json([
                'status' => 0,
                'msg' => '账号不能为空！'
            ]);
        }else{
            if(!preg_match($pattern, $data['username'])){
                return response()->json([
                    'status' => 3,
                    'msg' => '账号为5-18位字符！'
                ]);
            }
        }

        //验证密码
        if(empty($data['password'])) {
            return response()->json([
                'status' => 1,
                'msg' => '密码不能为空！'
            ]);
        }else{
            if(!preg_match($pattern, $data['password'])){
                return response()->json([
                    'status' => 4,
                    'msg' => '密码为5-18位字符！'
                ]);
            }
        }

        //验证验证码
        if(empty($data['vcode'])){
            return response()->json([
                'status' => 2,
                'msg' => '验证码不能为空！'
            ]);
        }else{
            $sessionVcode = Session::get('vcode');
            if($data['vcode'] != $sessionVcode) {
                return response()->json([
                    'status' => 5,
                    'msg' => '验证码错误！'
                ]);
            }
        }

        //根据账号获取用户信息
        $admin = Admin::where('username', $data['username'])->first();

        if(!empty($admin) && Hash::check($data['password'], $admin->password)){
            $request->session()->put('admin', $admin);      //登录成功 则记录登录信息

            //自动登录的操作
            if($data['remember'] == 1){
                $str = $data['username'].'|'.$data['password'];
                //加密
                $auth_admin = Crypt::encrypt($str);
                //写入cookie
                \Cookie::queue('auth_admin', $auth_admin, 60*24*30);
            }

            $admin = Admin::find($admin->id);
            $admin->last_login_time = date('Y-m-d H:i:s');
            $admin->LastLoginIp = $_SERVER["REMOTE_ADDR"];

            $admin->save();

            return response()->json([
                'status' => 6,
                'msg' => '登录成功！'
            ]);
        }else{
            return response()->json([
                'status' => 7,
                'msg' => '登录失败，账号或密码错误！'
            ]);
        }
    }

    /**
     * 验证码
     */
    public function captcha($tmp)
    {
        ob_clean();     //清除
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = 100, $height = 34, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        Session::flash('vcode', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    /**
     * 退出
     */
    public function logout()
    {
        session()->forget('admin'); //删除session对应的值
        $cookie = \Cookie::forget('auth_admin');    //删除cookie对应的值
        return redirect('admin/login')->withCookie($cookie);
    }
}

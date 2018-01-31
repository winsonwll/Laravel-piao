<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * 显示管理员列表页
     */
    public function index()
    {
        //读取数据 并且分页
        $list = Admin::where('status',1)->paginate(10);
        return view('admin.admin.index',['list'=>$list]);
    }

    /**
     * 显示添加管理员页
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * 执行添加管理员
     */
    public function store(Request $request)
    {
        //提取部分参数
        $data = $request->all();
        $pattern = '/^[0-9a-zA-z]{5,18}$/';

        //验证账号
        if(empty($data['username'])) {
            return response()->json([
                'status' => 201,
                'msg' => '用户名不能为空！'
            ]);
        }else{
            if(!preg_match($pattern, $data['username'])){
                return response()->json([
                    'status' => 202,
                    'msg' => '用户名为5-18位字符！'
                ]);
            }
        }

        //验证密码
        if(empty($data['password'])) {
            return response()->json([
                'status' => 203,
                'msg' => '密码不能为空！'
            ]);
        }else{
            if(!preg_match($pattern, $data['password'])){
                return response()->json([
                    'status' => 204,
                    'msg' => '密码为5-18位字符！'
                ]);
            }
        }

        //验证确认密码
        if(empty($data['repassword'])) {
            return response()->json([
                'status' => 205,
                'msg' => '确认密码不能为空！'
            ]);
        }else{
            if($data['password'] !== $data['repassword']){
                return response()->json([
                    'status' => 206,
                    'msg' => '两次密码输入不一致！'
                ]);
            }
        }

        //验证账号是否已存在
        $name = Admin::where('username', $data['username'])->first();
        if(!empty($name)) {
            return response()->json([
                'status' => 207,
                'msg' => '用户名已经存在，请重新输入！'
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
                'status' => 1,
                'msg' => '添加成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '添加失败，用户名或密码错误！'
            ]);
        }
    }

    /**
     * 查看单个管理员
     */
    public function show($id)
    {
        $res = Admin::find($id);
        return view('admin.admin.show',['res'=>$res]);
    }

    /**
     * 显示修改管理员页
     */
    public function edit($id)
    {
        $res = Admin::find($id);
        return view('admin.admin.edit',['res'=>$res]);
    }

    /**
     * 执行修改管理员信息
     */
    public function update(Request $request, $id)
    {
        //提取部分参数
        $data = $request->all();

        switch ($data['action']){
            case 1:
                //验证真实姓名
                if($data['realname'] == '') {
                    return response()->json([
                        'status' => 201,
                        'msg' => '真实姓名不能为空！'
                    ]);
                }
                //验证手机号
                if($data['mobile_number'] == '') {
                    return response()->json([
                        'status' => 202,
                        'msg' => '手机号不能为空！'
                    ]);
                }
                //验证邮箱
                if($data['email'] == '') {
                    return response()->json([
                        'status' => 203,
                        'msg' => '邮箱不能为空！'
                    ]);
                }
            break;
            case 2:
                //验证原密码
                if($data['oldpassword'] == '') {
                    return response()->json([
                        'status' => 201,
                        'msg' => '原密码不能为空！'
                    ]);
                }
                //验证新密码
                if($data['password'] == '') {
                    return response()->json([
                        'status' => 202,
                        'msg' => '新密码不能为空！'
                    ]);
                }
                //验证确认新密码
                if($data['repassword'] == '') {
                    return response()->json([
                        'status' => 203,
                        'msg' => '确认新密码不能为空！'
                    ]);
                }elseif ($data['repassword'] !== $data['password']){
                    return response()->json([
                        'status' => 204,
                        'msg' => '两次密码不一致！'
                    ]);
                }

                //根据账号获取用户信息
                $admin = Admin::where('id', $id)->first();
                if(!empty($admin) && Hash::check($data['oldpassword'], $admin->password)){
                    unset($data['oldpassword']);
                    unset($data['repassword']);
                }else{
                    return response()->json([
                        'status' => 0,
                        'msg' => '修改失败，原密码错误！'
                    ]);
                }

                break;
        }

        unset($data['action']);
        //执行更新管理员信息
        $res = Admin::where('id',$id)->update($data);
        if($res){
            //更新成功
            return response()->json([
                'status' => 1,
                'msg' => '更新成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '更新失败！'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 显示第三方平台页
     */
    public function platform()
    {
        return view('admin.admin.platform');
    }

    /**
     * 显示前台URL说明页
     */
    public function urlExplain()
    {
        return view('admin.admin.urlExplain');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Venue;

class VenueController extends Controller
{
    /**
     * 显示场馆列表页
     */
    public function index(Request $request)
    {
        $city = self::getAllCity();
        //读取数据 并且分页
        $list = Venue::where(function($query) use ($request){
                if($request->input('CityName')){
                    if($request->input('CityName') == '全国'){
                        $query->where('CityId', 0);
                    }else{
                        $query->where('CityName', $request->input('CityName'));
                    }
                }

                if($request->input('Name')){
                    $query->where('Name','like','%'.$request->input('Name').'%');
                }
            })
            ->paginate(10);

        return view('admin.venue.index',[
            'list'=>$list,
            'city'=>$city,
            'request'=>$request->all()
        ]);
    }

    /**
     * 显示添加场馆页
     */
    public function create()
    {
        $city = self::getAllCity();
        return view('admin.venue.create',['city'=>$city]);
    }

    /**
     * 执行添加场馆
     */
    public function store(Request $request)
    {
        $data = $request->all();
        //验证场馆名称
        if($data['Name'] == '') {
            return response()->json([
                'status' => 201,
                'msg' => '场馆名称不能为空！'
            ]);
        }
        //验证场馆所在城市
        if($data['CityName'] == '') {
            return response()->json([
                'status' => 202,
                'msg' => '场馆所在城市不能为空！'
            ]);
        }
        //验证场馆地址
        if($data['Address'] == '') {
            return response()->json([
                'status' => 203,
                'msg' => '场馆地址不能为空！'
            ]);
        }

        //验证场馆是否已存在
        $venueName = Venue::where('Name', $data['Name'])->first();
        if(!empty($venueName)) {
            return response()->json([
                'status' => 204,
                'msg' => '场馆已经存在，请重新输入！'
            ]);
        }

        $venue = new Venue();
        $venue->Name = $data['Name'];
        $venue->CityName = $data['CityName'];
        $venue->Address = $data['Address'];
        $venue->Introduce = $data['Introduce'];
        $venue->Status = 1;
        $venue->CreatOn = date('Y-m-d H:i:s');

        //执行添加
        $res = $venue->save();
        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '添加成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '添加失败！'
            ]);
        }
    }

    /**
     * 查看单个场馆
     */
    public function show($id)
    {
        $res = Venue::find($id);
        return view('admin.venue.show',['res'=>$res]);
    }

    /**
     * 显示修改场馆页
     */
    public function edit($id)
    {
        $res = Venue::find($id);
        $city = self::getAllCity();
        return view('admin.venue.edit',['res'=>$res, 'city'=>$city]);
    }

    /**
     * 执行修改场馆
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        //验证场馆名称
        if($data['Name'] == '') {
            return response()->json([
                'status' => 201,
                'msg' => '场馆名称不能为空！'
            ]);
        }
        //验证场馆所在城市
        if($data['CityName'] == '') {
            return response()->json([
                'status' => 202,
                'msg' => '场馆所在城市不能为空！'
            ]);
        }
        //验证场馆地址
        if($data['Address'] == '') {
            return response()->json([
                'status' => 203,
                'msg' => '场馆地址不能为空！'
            ]);
        }

        $data['Status']=1;
        //执行修改
        $res = Venue::where('id', $id)->update($data);

        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '修改成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '修改失败！'
            ]);
        }
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $res = Venue::destroy($id);

        if($res){
            //删除成功
            return response()->json([
                'status' => 1,
                'msg' => '删除成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '删除失败！'
            ]);
        }
    }

    /**
     * 获取所有城市
     */
    public static function getAllCity()
    {
        return City::all();
    }

    /**
     * 获取所有场馆
     */
    public static function getAllVenue()
    {
        return Venue::all();
    }

    /**
     * 获取指定城市的场馆By城市名
     */
    public static function getVenue($cityName)
    {
        return Venue::where('CityName', $cityName)->get();
    }

    /**
     * 获取指定的场馆By场馆id
     */
    public static function getVenueByPlaceId($id)
    {
        return Venue::find($id);
    }

    /**
     * 获取指定城市的场馆
     */
    public function getVenueByCityName($cityName)
    {
        $venue = Venue::where('CityName', $cityName)->get();
        if($venue){
            return response()->json([
                'status' => 1,
                'msg' => '获取演出场馆成功！',
                'data' => $venue
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '获取演出场馆失败！',
                'data' => ''
            ]);
        }
    }

    /**
     * 显示添加城市页
     */
    public function city()
    {
        return view('admin.venue.city');
    }

    /**
     * 执行添加城市
     */
    public function doCity(Request $request)
    {
        $data = $request->all();

        //验证场馆所在城市
        if($data['CityName'] == '') {
            return response()->json([
                'status' => 201,
                'msg' => '城市不能为空！'
            ]);
        }

        //验证城市是否已存在
        $cityName = City::where('CityName', $data['CityName'])->first();
        if(!empty($cityName)) {
            return response()->json([
                'status' => 202,
                'msg' => '该城市已经存在，请重新输入！'
            ]);
        }

        $city = new City();
        $city->CityName = $data['CityName'];
        $city->Status = 1;
        $city->CreatOn = date('Y-m-d H:i:s');

        //执行添加
        $res = $city->save();
        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '添加成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '添加失败！'
            ]);
        }
    }
}

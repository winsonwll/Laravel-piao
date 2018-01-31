<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\ShowTime;
use App\Models\ShowPrice;
use App\Models\Order;
use Config;
use Intervention\Image\ImageManagerStatic as Image;

class ShowController extends Controller
{
    /**
     * 显示演出列表页
     */
    public function index(Request $request)
    {
        $city = VenueController::getAllCity();
        $ShowTime = self::getAllShowTime();

        //读取数据 并且分页
        $list = Show::where(function($query) use ($request){
                    if($request->input('CityName')){
                        if($request->input('CityName') == '全国'){
                            $query->where('CityId', 0);
                        }else{
                            $query->where('CityName', $request->input('CityName'));
                        }
                    }
    
                    if($request->input('Start') && $request->input('End')){
                        $query->where([
                            ['Start', '>=', $request->input('Start')],
                            ['End', '<=', $request->input('End')],
                        ]);
                    }elseif ($request->input('Start') && empty($request->input('End'))){
                        $query->where([
                            ['Start', '>=', $request->input('Start')],
                        ]);
                    }elseif ($request->input('End') && empty($request->input('Start'))){
                        $query->where([
                            ['End', '<=', $request->input('End')],
                        ]);
                    }
    
                    if($request->input('ShowName')){
                        $query->where('ShowName','like','%'.$request->input('ShowName').'%');
                    }
                })
            ->orderBy('CreatOn', 'desc')
            ->paginate(10);

        return view('admin.show.index',[
            'list'=>$list,
            'city'=>$city,
            'ShowTime'=>$ShowTime,
            'request'=>$request->all()
        ]);
    }

    /**
     * 显示添加演出页
     */
    public function create()
    {
        $city = VenueController::getAllCity();
        $venue = VenueController::getVenue('北京');

        return view('admin.show.create',['city'=>$city, 'venue'=>$venue]);
    }

    /**
     * 执行添加演出
     */
    public function store(Request $request)
    {
        //提取部分参数
        $data = $request->all();

        //验证演出名称
        if($data['ShowName'] == '') {
            return response()->json([
                'status' => 201,
                'msg' => '演出名称不能为空！'
            ]);
        }
        //验证演出城市
        if($data['CityName'] == '') {
            return response()->json([
                'status' => 202,
                'msg' => '演出城市不能为空！'
            ]);
        }
        //验证演出场馆
        if($data['PlaceId'] == '') {
            return response()->json([
                'status' => 203,
                'msg' => '演出场馆不能为空！'
            ]);
        }
        //验证演出场次 票面价
        if($data['Note'] == '') {
            return response()->json([
                'status' => 204,
                'msg' => '演出场次和票面价不能为空！'
            ]);
        }

        //实现图片上传 且随机文件名
        $filename = self::uploadImage($request);

        //$data['Photo'] = trim(Config::get('app.upload_dir').$filename,'.');
        $data['Photo'] = 'http://www.piaobuyer.com/Ticket/upload/show/'.$filename;
        $data['CityId'] = 0;
        $data['GroupId'] = 0;
        $data['type'] = 0;
        $data['Inventory'] = 0;
        $data['BrowseNum'] = 0;
        $data['IsRecommend'] = 0;
        $data['IsWeekEnd'] = 0;
        $data['CreatOn'] = date('Y-m-d H:i:s');

        /*$venue = VenueController::getVenue($data['CityName']);
        $data['Place'] = $venue[0]->Name;
        $data['Address'] = $venue[0]->Address;*/

        $venue = VenueController::getVenueByPlaceId($data['PlaceId']);
        $data['Place'] = $venue->Name;
        $data['Address'] = $venue->Address;

        $showTimePrice = json_decode($data['Note'],true);
        $start = json_decode($showTimePrice[0]);
        $end = json_decode($showTimePrice[count($showTimePrice)-1]);
        $data['Start'] = $start->ShowTime;
        $data['End'] = $end->ShowTime;
        $str = '';

        foreach ($showTimePrice as $v){
            $str .= "time:".json_decode($v)->ShowTime."|area:".implode(",",json_decode($v)->AreaName)."|price:".implode(',',json_decode($v)->AreaPrice).'#';
        }
        $data['Note2'] = 'auto';
        $data['Note3'] = rtrim($str,'#');

        unset($data['Note']);
        unset($data['file']);
        unset($data['AreaName']);
        unset($data['AreaPrice']);

        //执行添加演出
        $res = Show::create($data);
        if($res){
            //创建成功
            $dataShowTime['ShowId'] = $res->id;
            $dataShowTime['Status'] = 1;

            foreach ($showTimePrice as $v) {
                $dataShowTime['ShowTime'] = json_decode($v)->ShowTime;
                $dataShowTime['CreatOn'] = date('Y-m-d H:i:s');
                $item['AreaName'] = json_decode($v)->AreaName;
                $item['AreaPrice'] = json_decode($v)->AreaPrice;

                $showTimeId = ShowTime::create($dataShowTime)->id;
                if($showTimeId){
                    $dataShowPrice['ShowTimeId'] = $showTimeId;
                    $dataShowPrice['Status'] = 1;

                    foreach ($item['AreaName'] as $k=>$v){
                        $dataShowPrice['AreaName'] = $v;
                        $dataShowPrice['AreaPrice'] = $item['AreaPrice'][$k];
                        $dataShowPrice['CreatOn'] = date('Y-m-d H:i:s');
                        ShowPrice::create($dataShowPrice);
                    }
                }
            }

            return response()->json([
                'status' => 1,
                'msg' => '创建成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '创建失败！'
            ]);
        }
    }

    /**
     * 查看单条演出
     */
    public function show($id)
    {
        $res = Show::find($id);
        $ShowTime = self::getShowTime($id);
        $ShowPrice = [];

        $list = Order::where('ShowId', $id)
            ->orderBy('CreatOn', 'desc')
            ->paginate(10);

        foreach($ShowTime as $v){
            $ShowPrice[] = self::getShowPrice($v->id);
        }

        return view('admin.show.show',[
            'res'=>$res,
            'ShowTime'=>$ShowTime,
            'ShowPrice'=>$ShowPrice,
            'list'=>$list
        ]);
    }

    /**
     * 显示修改演出页
     */
    public function edit($id)
    {
        $res = Show::find($id);
        $city = VenueController::getAllCity();
        $venue = VenueController::getVenue($res->CityName);
        $ShowTime = self::getShowTime($id);
        
        $ShowPrice = [];
        foreach($ShowTime as $v){
            $ShowPrice[] = self::getShowPrice($v->id);
        }

        return view('admin.show.edit',[
            'res'=>$res,
            'city'=>$city,
            'venue'=>$venue,
            'ShowTime'=>$ShowTime,
            'ShowPrice'=>$ShowPrice
        ]);
    }

    /**
     * 执行修改演出信息
     */
    public function update(Request $request, $id)
    {
        //提取部分参数
        $data = $request->all();

        //验证演出名称
        if($data['ShowName'] == '') {
            return response()->json([
                'status' => 201,
                'msg' => '演出名称不能为空！'
            ]);
        }
        //验证演出城市
        if($data['CityName'] == '') {
            return response()->json([
                'status' => 202,
                'msg' => '演出城市不能为空！'
            ]);
        }
        //验证演出场馆
        if($data['PlaceId'] == '') {
            return response()->json([
                'status' => 203,
                'msg' => '演出场馆不能为空！'
            ]);
        }
        //验证演出封面
        if($data['file'] != 'undefined') {
            //实现图片上传 且随机文件名
            $filename = self::uploadImage($request);
            //$data['Photo'] = trim(Config::get('app.upload_dir').$filename,'.');
            $data['Photo'] = 'http://www.piaobuyer.com/Ticket/upload/show/'.$filename;
        }

        $venue = VenueController::getVenueByPlaceId($data['PlaceId']);
        $data['Place'] = $venue->Name;
        $data['Address'] = $venue->Address;

        unset($data['file']);
        unset($data['_method']);

        //执行更新演出信息
        $res = Show::where('id',$id)->update($data);
        if($res){
            //更新成功
            return response()->json([
                'status' => 1,
                'msg' => '演出信息更新成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '演出信息更新失败！'
            ]);
        }
    }

    /**
     * 执行修改演出票面价
     */
    public function doUpdateShowPrice(Request $request, $id)
    {
        //提取部分参数
        $data = $request->all();

        $res = ShowPrice::where('id', $id)->update($data);
        if($res){
            //修改成功
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
     * 执行删除演出票面价
     */
    public function doDeleteShowPrice($id)
    {
        $res = ShowPrice::destroy($id);
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
     * 执行添加演出票面价
     */
    public function doStoreShowPrice(Request $request, $id)
    {
        //提取部分参数
        $data = $request->all();

        $data['ShowTimeId'] = $id;
        $data['Status'] = 1;
        $data['CreatOn'] = date('Y-m-d H:i:s');

        $res = ShowPrice::create($data);
        if($res){
            //添加成功
            return response()->json([
                'status' => 1,
                'msg' => '添加成功！',
                'data' => $res->id
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '添加失败！'
            ]);
        }
    }

    /**
     * 执行删除演出场次
     */
    public function doDeleteShowTime($id)
    {
        $res = ShowTime::destroy($id);
        if($res){
            //删除成功
            $result = ShowPrice::where('ShowTimeId', $id)->delete();
            if($result){
                return response()->json([
                    'status' => 1,
                    'msg' => '删除成功！'
                ]);
            }else{
                return response()->json([
                    'status' => 2,
                    'msg' => '该场次下的票面价删除失败！'
                ]);
            }
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '删除失败！'
            ]);
        }
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $res = Show::destroy($id);

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
     * 显示演出统计页
     */
    public function tj(Request $request)
    {
        $list = Show::orderBy('CreatOn', 'desc')->paginate(10);
        $showCount = Show::count();
        $showTimeCount = ShowTime::count();

        $showOrder = Order::pluck('ShowId');
        $showOrderCount = count(array_flip(json_decode($showOrder)));

        $showFrozenCount = Show::where('Status', '0')->count();
        $showExpireCount = Show::where('Status', '2')->count();

        return view('admin.show.tj',[
            'list'=>$list,
            'showCount'=>$showCount,
            'showTimeCount'=>$showTimeCount,
            'showOrderCount'=>$showOrderCount,
            'showFrozenCount'=>$showFrozenCount,
            'showExpireCount'=>$showExpireCount,
            'request'=>$request->all()
        ]);
    }

    /**
     * 冻结演出
     */
    public function doFrozen(Request $request)
    {
        $show = Show::find($request->id);
        $show->Status = 0;

        $res = $show->save();
        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '冻结成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '冻结失败！'
            ]);
        }
    }

    /**
     * 解冻卖家
     */
    public function doThaw(Request $request)
    {
        $show = Show::find($request->id);
        $show->Status = 1;

        $res = $show->save();
        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '解冻成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '解冻失败！'
            ]);
        }
    }

    /**
     * 获取所有的演出
     */
    public static function getAllShow()
    {
        return Show::whereIn('Status', [0,1])->get();
    }

    /**
     * 获取所有演出场次
     */
    public static function getAllShowTime()
    {
        return ShowTime::all();
    }

    /**
     * 获取指定演出的场次
     */
    public static function getShowTime($id)
    {
        return ShowTime::where('ShowId', $id)->get();
    }

    /**
     * 获取指定演出场次的票价
     */
    public static function getShowPrice($id)
    {
        return ShowPrice::where('ShowTimeId', $id)->get();
    }
    
    /**
     * 实现图片上传 且随机文件名
     */
    public static function uploadImage(Request $request)
    {
        if($request->hasFile('file')){     //判断是否有上传
            $file=$request->file('file');      //获取上传信息
            if($file->isValid()){   //确认上传的文件是否成功
                //$picname=$file->getClientOriginalName();    //获取上传原文件名
                $ext=$file->getClientOriginalExtension();   //获取上传文件名的后缀名
                $filename=rand(120000,150000).'.'.$ext;
                $file->move(Config::get('app.upload_dir'),$filename);    //执行移动上传文件

                //第三方插件执行等比缩放
                $img = Image::make(Config::get('app.upload_dir').$filename)->fit(109,144,function($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $img->save(Config::get('app.upload_dir').$filename); //另存为

                return $filename;
            }
        }
    }
}
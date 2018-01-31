<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Show;
use App\Models\ShowTime;
use App\Models\ShowPrice;

class UserController extends Controller
{
    /**
     * 显示卖家列表页
     */
    public function index(Request $request)
    {
        $show = ShowController::getAllShow();
        //读取数据 并且分页
        $list = User::where(function($query) use ($request){
            if($request->input('keyword')){
                $query->where('MobilePhone','like','%'.$request->input('keyword').'%')
                    ->orWhere('Realname','like','%'.$request->input('keyword').'%');
            }
        })
            ->orderBy('RegisterTime', 'desc')
            ->paginate(10);

        return view('admin.user.index',[
            'list'=>$list,
            'show'=>$show,
            'request'=>$request->all()
        ]);
    }

    /**
     * 显示新建卖家页
     */
    public function create()
    {

    }

    /**
     * 执行添加卖家
     */
    public function store(Request $request)
    {

    }

    /**
     * 查看单个卖家
     */
    public function show($id)
    {
        $show = ShowController::getAllShow();

        $list = Order::where('UserId', $id)
            ->orderBy('CreatOn', 'desc')
            ->paginate(10);

        $res = User::find($id);
        return view('admin.user.show',[
            'res'=>$res,
            'list'=>$list,
            'show'=>$show
        ]);
    }

    /**
     * 显示修改卖家页
     */
    public function edit($id)
    {

    }

    /**
     * 执行修改卖家
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * 删除
     */
    public function destroy($id)
    {

    }

    /**
     * 显示卖家统计页
     */
    public function tj(Request $request)
    {
        //读取数据 并且分页
        $list = User::orderBy('RegisterTime', 'desc')->paginate(10);

        $userCount = User::count();
        $orderUser = Order::pluck('UserId');
        $orderUserCount = count(array_flip(json_decode($orderUser)));

        $onlineOrderUser = Order::where('Status', '1')->pluck('UserId');
        $onlineOrderUserCount = count(array_flip(json_decode($onlineOrderUser)));

        /*case 1:
            $startTime = date('Y-m-d 00:00:00');
            $endTime = date('Y-m-d 23:59:59');
            break;
        case 7:
            $startTime = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
            $endTime = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
            break;
        case 30:
            $startTime = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
            $endTime = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
            break;*/
        
        $startTime = date('Y-m-d 00:00:00');
        $endTime = date('Y-m-d 23:59:59');
        $loginUserCount = User::whereBetween('LastLoginTime', [$startTime, $endTime])->count();

        return view('admin.user.tj',[
            'list'=>$list,
            'userCount'=>$userCount,
            'orderUserCount'=>$orderUserCount,
            'onlineOrderUserCount'=>$onlineOrderUserCount,
            'loginUserCount'=>$loginUserCount,
            'request'=>$request->all()
        ]);
    }

    /**
     * 冻结卖家
     */
    public function doFrozen(Request $request)
    {
        $user = User::find($request->id);
        $user->Status = 0;

        $res = $user->save();
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
        $user = User::find($request->id);
        $user->Status = 1;

        $res = $user->save();
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
     * 获取指定卖家的挂单数
     */
    public function getOrder($id)
    {
        $count = Order::where('UserId', $id)->count();
        return $count;
    }

    /**
     * 格式化邀请码
     */
    public function formatCode($id)
    {
        $dec = base_convert($id,10,36);
        return str_pad($dec, 4, "0", STR_PAD_LEFT);
    }

    /**
     * 代挂单 获取指定演出的场次
     */
    public function getShowTimeByShowId($id)
    {
        $showTime = ShowTime::where('ShowId', $id)->get();
        if($showTime){
            return response()->json([
                'status' => 1,
                'msg' => '获取演出场次成功！',
                'data' => $showTime
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '获取演出场次失败！',
                'data' => ''
            ]);
        }
    }

    /**
     * 代挂单 获取指定演出场次的票价
     */
    public function getShowPriceByShowTimeId($id)
    {
        $showPrice = ShowPrice::where('ShowTimeId', $id)->get();
        if($showPrice){
            return response()->json([
                'status' => 1,
                'msg' => '获取演出票价成功！',
                'data' => $showPrice
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '获取演出票价失败！',
                'data' => ''
            ]);
        }
    }

    /**
     * 执行代挂单
     */
    public function doProxy(Request $request)
    {
        $data = $request->all();
        //验证卖家
        if($data['UserId'] == '') {
            return response()->json([
                'status' => 201,
                'msg' => '卖家不能为空！'
            ]);
        }
        //验证演出名称
        if($data['ShowId'] == '') {
            return response()->json([
                'status' => 202,
                'msg' => '演出名称不能为空！'
            ]);
        }
        //验证演出场次
        if($data['ShowTimeId'] == '') {
            return response()->json([
                'status' => 203,
                'msg' => '演出场次不能为空！'
            ]);
        }
        //验证票面价
        if($data['ShowPriceId'] == '') {
            return response()->json([
                'status' => 204,
                'msg' => '票面价不能为空！'
            ]);
        }
        //验证同行价
        if($data['PerPrice'] == '') {
            return response()->json([
                'status' => 205,
                'msg' => '同行价不能为空！'
            ]);
        }elseif ($data['PerPrice'] < 1){
            return response()->json([
                'status' => 206,
                'msg' => '同行价最小为1元！'
            ]);
        }
        //验证出售数量
        if($data['SellNum'] == '') {
            return response()->json([
                'status' => 207,
                'msg' => '出售数量不能为空！'
            ]);
        }elseif ($data['PerPrice'] < 1){
            return response()->json([
                'status' => 208,
                'msg' => '出售数量最少为1！'
            ]);
        }
        //验证有效天数
        if($data['restDay'] == '') {
            return response()->json([
                'status' => 209,
                'msg' => '有效天数不能为空！'
            ]);
        }elseif ($data['PerPrice'] < 1){
            return response()->json([
                'status' => 210,
                'msg' => '有效天数至少为1天！'
            ]);
        }

        $data['Status']=1;
        $data['views']=0;
        $data['CreatOn']=date('Y-m-d H:i:s');
        $data['deadline']=date("Y-m-d H:i:s",strtotime("+".$data['restDay']." days"));
        $data['UserRealName']=User::where('id',$data['UserId'])->value('Realname');

        $show = Show::find($data['ShowId']);
        $data['ShowName']=$show->ShowName;
        $data['VenueId']=$show->PlaceId;
        $data['VenueName']=$show->Place;
        $data['ShowTime']=ShowTime::where('id',$data['ShowTimeId'])->value('ShowTime');
        $showPrice = ShowPrice::find($data['ShowPriceId']);
        $data['AreaName']=$showPrice->AreaName;
        $data['ParValue']=$showPrice->AreaPrice;

        //执行挂单
        $res = Order::create($data);

        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '代挂单成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '代挂单失败！'
            ]);
        }
    }
}
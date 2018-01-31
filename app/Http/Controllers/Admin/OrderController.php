<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Linkhistory;

class OrderController extends Controller
{
    /**
     * 显示挂单列表页
     */
    public function index(Request $request)
    {
        //读取数据 并且分页
        $list = Order::where(function($query) use ($request){
                if($request->input('Start') && $request->input('End')){
                    $query->whereBetween('CreatOn', [$request->input('Start'), $request->input('End')]);
                }

                if($request->input('UserRealName')){
                    $query->where('UserRealName','like','%'.$request->input('UserRealName').'%');
                }

                if($request->input('ShowName')){
                    $query->where('ShowName','like','%'.$request->input('ShowName').'%');
                }

                if($request->input('Status')){
                    if($request->input('Status') == 10){
                        $query->whereNotNull('Status');
                    }else{
                        $query->where('Status', $request->input('Status'));
                    }
                }
            })
            ->orderBy('CreatOn', 'desc')
            ->paginate(10);

        return view('admin.order.index',['list'=>$list, 'request'=>$request->all()]);
    }

    /**
     * 显示添加挂单页
     */
    public function create()
    {

    }

    /**
     * 执行添加挂单
     */
    public function store(Request $request)
    {

    }

    /**
     * 查看单条挂单
     */
    public function show($id)
    {
        $list = Linkhistory::where('OrderId', $id)
            ->orderBy('CreatOn', 'desc')
            ->paginate(10);

        $res = Order::find($id);
        return view('admin.order.show',['res'=>$res, 'list'=>$list]);
    }

    /**
     * 显示修改挂单页
     */
    public function edit($id)
    {
        $res = Order::find($id);
        return view('admin.order.edit',['res'=>$res]);
    }

    /**
     * 执行修改挂单
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        //验证同行价
        if($data['PerPrice'] == '') {
            return response()->json([
                'status' => 201,
                'msg' => '同行价不能为空！'
            ]);
        }elseif ($data['PerPrice'] < 1){
            return response()->json([
                'status' => 204,
                'msg' => '同行价最小为1元！'
            ]);
        }
        //验证出售数量
        if($data['SellNum'] == '') {
            return response()->json([
                'status' => 202,
                'msg' => '出售数量不能为空！'
            ]);
        }elseif ($data['PerPrice'] < 1){
            return response()->json([
                'status' => 205,
                'msg' => '出售数量最少为1！'
            ]);
        }
        //验证有效天数
        if($data['restDay'] == '') {
            return response()->json([
                'status' => 203,
                'msg' => '有效天数不能为空！'
            ]);
        }elseif ($data['PerPrice'] < 1){
            return response()->json([
                'status' => 206,
                'msg' => '有效天数至少为1天！'
            ]);
        }

        $data['Status']=1;
        $data['CreatOn']=date('Y-m-d H:i:s');
        //执行修改
        $res = Order::where('id', $id)->update($data);

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
    public function destroy(Request $request, $id)
    {

    }

    /**
     * 显示挂单统计页
     */
    public function tj(Request $request)
    {
        //读取数据 并且分页
        $list = Order::orderBy('views', 'desc')->paginate(10);

        $orderCount = Order::count();
        $onlineOrderCount = Order::where('Status', '1')->count();
        $offlineOrderCount = Order::where('Status', '2')->count();
        $sellOrderCount = Order::pluck('SellNum')->sum();
        $viewOrderCount = Order::pluck('views')->sum();
        $linkOrderCount = Linkhistory::count();
        
        return view('admin.order.tj',[
            'list'=>$list,
            'orderCount'=>$orderCount,
            'onlineOrderCount'=>$onlineOrderCount,
            'offlineOrderCount'=>$offlineOrderCount,
            'sellOrderCount'=>$sellOrderCount,
            'viewOrderCount'=>$viewOrderCount,
            'linkOrderCount'=>$linkOrderCount,
            'request'=>$request->all()
        ]);
    }

    /**
     * 获取指定卖家
     */
    public function getUser($id)
    {
        return User::find($id);
    }

    /**
     * 获取指定演出的挂单
     */
    public function getOrderById($id, $idName)
    {
        $list = Order::where($idName, $id)->get();

        $data['count'] = count($list);
        $count_SellNum = 0;
        $count_views = 0;
        $show = [];

        foreach ($list as $v){
            $count_SellNum += $v->SellNum;
            $count_views += $v->views;
            $show[] = $v->ShowId;
        }

        $data['SellNum'] = $count_SellNum;
        $data['views'] = $count_views;
        $data['show'] = count(array_unique($show));

        return $data;
    }

    /**
     * 获取指定演出的联系记录
     */
    public function getLinkhistoryById($id, $idName)
    {
        $list = Linkhistory::where($idName, $id)->get();
        return $list;
    }

    /**
     * 上架
     */
    public function doOnSell(Request $request)
    {
        $order = Order::find($request->id);
        $order->Status = 1;
        $order->CreatOn = date('Y-m-d H:i:s');

        $res = $order->save();
        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '上架成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '上架失败！'
            ]);
        }
    }

    /**
     * 下架
     */
    public function doOffSell(Request $request)
    {
        $order = Order::find($request->id);
        $order->Status = 2;

        $res = $order->save();
        if($res){
            return response()->json([
                'status' => 1,
                'msg' => '下架成功！'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => '下架失败！'
            ]);
        }
    }
}
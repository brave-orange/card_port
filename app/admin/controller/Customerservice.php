<?php
namespace app\admin\controller;
use app\common\controller\AdminController;
use think\Db;
use think\Session;
use think\Request;

class Customerservice extends AdminController{    //客服控制器
    public function index(){
        return view();
    }
    public function consume(){
        return view();
    }
    public function cardstatus(){
        return view();
    }
    public function _initialize(){
        if (Request::instance()->isGet()){
            if(3 != Session::get('admin_type')) {
                
                $this->redirect('admin/admin/login');
                //没登陆，跳转到登陆页
            }
        }else if (Request::instance()->isPost()){
            if(3 != Session::get('admin_type')) {             
                $this->error(["code"=>0,"msg"=>"未登录状态无法调用！"]);

            }
        }
    }
    public function searchByPhone(){
        if(Request::instance()->isPost()){
            $phone = input('phone');
            $page = input('param.page');
            $limit = input('param.limit');
            $start = ($page-1)*$limit;
            if('' == $phone){
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => 0,'data'=>[]],JSON_UNESCAPED_UNICODE));
            }
            $res = model('Customerservice','service')->getCard($phone,$start,$limit);
            if($res['data']){
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => $res['count'],'data'=>$res['data']],JSON_UNESCAPED_UNICODE));
            }else{
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => 0,'data'=>[]],JSON_UNESCAPED_UNICODE));
            }
        }
    }

    public function searchConsume(){
        if(Request::instance()->isPost()){
            $phone = input('phone');
            $page = input('param.page');
            $limit = input('param.limit');
            $start = ($page-1)*$limit;
            if('' == $phone){
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => 0,'data'=>[]],JSON_UNESCAPED_UNICODE));
            }
            $res = model('Customerservice','service')->getConsume($phone,$start,$limit);
            if($res['data']){
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => $res['count'],'data'=>$res['data']],JSON_UNESCAPED_UNICODE));
            }else{
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => 0,'data'=>[]],JSON_UNESCAPED_UNICODE));
            }
        }
    }
    public function getOrderDetail(){
        if(Request::instance()->isPost()){
            $order_id = input ('param.order_id');
            $res = Db::table('haochong_order')->where(['sporderid'=>$order_id])->find();

            if($res){
                $msg = "好充订单号：<span style='font-size:15px;font-weight:bold;color:red'>".$res['orderid']."</span>, 消费物品： <span style='font-size:15px;font-weight:bold;color:red'>".$res['productname']."</span>, 充值对象：<span style='font-size:15px;font-weight:bold;color:red'>".$res['mobile']."</span>, 充值时间：<span style='font-size:15px;font-weight:bold;color:red'>".$res['merchantsubmittime']."</span>";
                unset($res);
                return json('success',$msg);
            }else{
                return json('error','似乎出现了错误...');
            }
            
        }
    }
}
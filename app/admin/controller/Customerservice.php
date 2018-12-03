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
    public function searchByPhone(){    //通过手机号找到该用户的积分充值记录
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

    public function searchConsume(){     //通过手机号找到该用户的消费记录
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
    public function getOrderDetail(){        //获取某条消费记录详细信息
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

    public function getCardStatusDetail(){      //获取积分卡的状态详情
        if(Request::instance()->isPost()){
            $card_no = input ('param.card_no');
            $d = model('RechargeRecord')->alias('a')
                ->join('user b','a.userid = b.id')
                ->field('b.phone,a.time')
                ->where(['a.card_no'=>$card_no])
                ->find();
            if($d){
                $msg = "<span style='font-size:15px;font-weight:bold;color:blue'>".$d['phone']."</span>用户于 <span style='font-size:15px;font-weight:bold;color:red'>".$d['time']."</span>使用了此卡充值。";
                unset($d);
                return json('success',$msg);
            }else{
                return json('error','似乎出现了错误...');
            }
            
        }
    }
    public function checkCardNo(){      //检查积分卡号的合法性
        $card_no = input('param.card_no');
        $card_no = trim($card_no);
        if(card_is_real($card_no)){   //common.php中的公共方法
            return json('success','card is real!');
        }else{
            return json('error','卡号有错误，请检查卡号！'.$card_no);
        }
    }    
    public function getCardStatus(){    //获取积分卡的信息
        if(Request::instance()->isPost()){
            $card_no = input('card_no');
            $page = input('param.page');
            $limit = input('param.limit');
            $start = ($page-1)*$limit;
            if('' == $card_no){
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => 0,'data'=>[]],JSON_UNESCAPED_UNICODE));
            }
            $res = model('Customerservice','service')->getCardStatus($card_no,$start,$limit);
            if($res['data']){
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => $res['count'],'data'=>$res['data']],JSON_UNESCAPED_UNICODE));
            }else{
                return json_decode(json_encode(['code'=>'0','msg'=>'','count' => 0,'data'=>[]],JSON_UNESCAPED_UNICODE));
            }

        }
    }
}
<?php
namespace app\chuanze\controller;
use app\common\HaoChong as HC;
use \think\Request;
use \think\Session;
use SimpleXMLElement;
class Haochongapi{
    public function index(){      //提供给好充的回调接口
        if(Request::instance()->isPost()){
            $userid = input('param.userid');    
            $orderid = input('param.orderid');    
            $sporderid = input('param.sporderid');    
            $merchantsubmittime = input('param.merchantsubmittime');
            $resultno = input('param.resultno');
            $sign = input('param.sign');
            $parvalue = input('param.parvalue');
            $fundbalance = input('param.fundbalance');
            $token = md5("userid=".$userid."&orderid=".$orderid."&sporderid=".$sporderid."&merchantsubmittime=".$merchantsubmittime."&resultno=".$resultno."&key=".HC::$key);
            if($sign == $token ){
                //存入数据库
            }else{

            }

        }else{
            return HC::$key;
        }
    }
    public function tel_pay(){    //充话费接口
        $userid = Session::get("userid");
        $balance = user_balance($userid);
        $money = input("param.value");
        $phone = input("phone");
        if($userid == ''){
            return json('error','请登录！');
        }
        if($money == '' || $phone == ""){
            return json('error','参数不足');
        }
        if($balance['hf']<$money){
            return json('error','账户余额不足');
        }else{
            $count = model("OrderRecord")->where(['time'=>['between',[date("Y-m-d 00:00:00"),date("Y-m-d 23:59:59")]]])->count();
            $orderid = 'hf'.date('md').sprintf("%04d",($count+1));
            $hc = new HC();
            $res = $hc->recharge($phone,$money,$orderid);
            $xml_res = new SimpleXMLElement($res);
            $status = (int)$xml_res->resultno;
            if($status <= 2){
                model("OrderRecord")->insert(['id'=>$orderid,'goodid'=>' ','userid'=>$userid,'order_type'=>'hf','time'=>date("Y-m-d H:i:s"),'money'=>$money]);
                return json('success','充值成功请等待通知！');
            }else{
                
                return json('error','系统出错无法充值，请联系客服！');
            }
            
        }
    }    
}
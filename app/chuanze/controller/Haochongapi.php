<?php
namespace app\chuanze\controller;
use app\common\HaoChong as HC;
use \think\Request;
use \think\Session;
use SimpleXMLElement;
class Haochongapi{
    public function backapi(){      //提供给好充的回调接口
        if(Request::instance()->isPost()){
            $userid = input('param.userid');    
            $orderid = input('param.orderid');    
            $sporderid = input('param.sporderid');    
            $merchantsubmittime = input('param.merchantsubmittime');
            $resultno = input('param.resultno');
            $sign = input('param.sign');
            $parvalue = input('param.parvalue');
            $fundbalance = input('param.fundbalance');
            $token = strtoupper(md5("userid=".$userid."&orderid=".$orderid."&sporderid=".$sporderid."&merchantsubmittime=".$merchantsubmittime."&resultno=".$resultno."&key=".HC::$key));
            if($sign == $token ){
                if((int)$resultno != 1){
                    $order = model('OrderRecord')->where(['id'=>$sporderid])->find();
                    $user = model('user')->where(['id'=>$order['userid']])->find();
                    $user_phone = $user['phone'];
                    unset($user);
                    $hco = model("HaochongOrder")->where(['orderid'=>$orderid])->find();
                    $hco['result'] = config('haochong_status')[''.$resultno];
                    $hco['merchantsubmittime'] = $merchantsubmittime;
                    SendWarring($user_phone,$order['time'],$hco['mobile'],$order['money']);//发信息
                    $order['status'] = 0;
                    $order->save();
                    $hco->save();
                }else{
                    $hco = model("HaochongOrder")->where(['orderid'=>$orderid])->find();
                    $hco['result'] = config('haochong_status')[''.$resultno];
                    $hco['merchantsubmittime'] = $merchantsubmittime;
                    $hco->save();
                    return "ok!";
                }
                
            }else{
                return "data is broken!";
            }

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
            $orderid = 'hf'.date('ymd').sprintf("%04d",($count+1));
            $hc = new HC();
            $res = $hc->recharge($phone,$money,$orderid);
            $xml_res = new SimpleXMLElement($res);
            $status = (int)$xml_res->resultno;
            if($status <= 2){
                model("OrderRecord")->insert(['id'=>$orderid,'goodid'=>' ','userid'=>$userid,'order_type'=>'hf','time'=>date("Y-m-d H:i:s"),'money'=>$money,'status'=>1]);
                model('HaochongOrder')->insert(['orderid'=>$xml_res->orderid,'sporderid'=>$xml_res->sporderid,'ordercash'=>$xml_res->ordercash,'productname'=>$xml_res->productname,'mobile'=>$xml_res->mobile,'merchantsubmittime'=>$xml_res->merchantsubmittime,'result'=>config('haochong_status')[''.$xml_res->resultno]]);
                return json('success','充值成功请等待通知！');
            }else{
                
                return json('error','系统出错无法充值，请联系客服！');
            }
            
        }

        
    }
    public function HaochongBalance(){
        $hc = new HC();
        $res = $hc->getBalance();
        $xml_res = new SimpleXMLElement($res);
        return json('success',"",array('balance'=>$xml_res->balance));
    }  
}
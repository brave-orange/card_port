<?php
namespace app\chuanze\controller;
use app\common\HaoChong as HC;
class HaoChong{
    public function index(){
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
            }

        }
    }
}
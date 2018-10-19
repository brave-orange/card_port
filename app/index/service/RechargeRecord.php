<?php

namespace app\index\service;
use think\Model;
use think\Session;
class RechargeRecord extends Model{
    public function addRecord($card_no,$type){
        $num = (int)substr($card_no,18,3);
        $userid = Session::get("userid");
        $time = date("Y-m-d H:i:s");
        return model("RechargeRecord")->insert(['userid'=>$userid,'time'=>$time,'card_no'=>$card_no,'type'=>$type,'money'=>$num]);
    }
}
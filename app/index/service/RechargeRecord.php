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
    public function getLog($userid){
        $start = input("start");
        $end = input("end");
        $start = date("Y-m-d 00:00:00",strtotime($start));
        $end = date("Y-m-d 23:59:59",strtotime($end));
        $log = model("RechargeRecord")->where(['userid'=>$userid,'time'=>['between',[$start,$end]]])->select();
        foreach ($log as $key => $value) {
            if($value['type'] == 'hf'){
                $value['type'] = '话费';
            }else if($value['type'] == 'yk'){
                $value['type'] = '油卡';
            }
        }
        return $log;
    }
}
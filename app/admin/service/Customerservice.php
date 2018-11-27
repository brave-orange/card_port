<?php
namespace app\admin\service;
class Customerservice{
    public function getCard($phone,$start,$limit){
        $res = model('RechargeRecord')
            ->alias('a')
            ->join('user b','a.userid = b.id')
            ->where(['b.phone'=>$phone])
            ->limit($start.','.$limit)

            ->select();
        $count = model('RechargeRecord')
            ->alias('a')
            ->join('user b','a.userid = b.id')
            ->where(['b.phone'=>$phone])
            ->count();
        return array('count'=>$count,'data'=>$res);
    }
    public function getConsume($phone,$start,$limit){
         $res = model('OrderRecord')
            ->alias('a')
            ->join('user b','a.userid = b.id')
            ->where(['b.phone'=>$phone])
            ->field('a.id order_id,a.time order_time,order_type,money,status')
            ->limit($start.','.$limit)
            ->select();
        foreach ($res as &$value) {
             $value['status'] = config('haochong_status')[$value['status']];
        }
       
        $count = model('OrderRecord')
            ->alias('a')
            ->join('user b','a.userid = b.id')
            ->where(['b.phone'=>$phone])
            ->count();
        return array('count'=>$count,'data'=>$res);
    }
}
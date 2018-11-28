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

    public function getCardStatus($card_no,$start,$limit){
        $res = model('Card')->alias('a')
            ->join('buy_card_record b','a.buy_id=b.id')
            ->join('company_code c','a.comp_id = c.comp_id')
            ->where(['a.card_no'=>$card_no])
            ->field('a.type,c.name company_name,b.time,a.is_used')
            ->limit($start.','.$limit)
            ->find();
        if($res['is_used'] == '0'){
            $res['status'] = '未用';
        }else if($res['is_used'] == '1'){
            $res['status'] = '已用';
        }else{
            $res['status'] = '未知';
        }
        $res = array(0=>$res);
        $count = 1;
        return array('count'=>$count,'data'=>$res);
    }
}
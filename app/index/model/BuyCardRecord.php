<?php

namespace app\index\model;
use think\Model;
use think\Db;
class BuyCardRecord extends Model{
    protected $table="buy_card_record";
    public function insert($data){
        $b = new BuyCardRecord();
        foreach($data as $key => $value){
            $b[$key] = $value;
        }
        return $b->save();
    }
    public function getRecords($o_man){   //获取运营人员所生成的积分卡数据
        return $this->where(['opeart_man'=>$o_man])->select();
    }
    public function getRecordsBytime($o_man,$start,$end){
        return $this->where(['opeart_man'=>$o_man,'time'=>['between'=>[$start,$end]]])->select();
    }
}

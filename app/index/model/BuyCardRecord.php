<?php

namespace app\index\model;
use think\Model;
use think\Db;
class BuyCardRecord{
    protected $table="buy_card_record";
    public function insert($data){
        $b = new BuyCardRecord();
        foreach($data as $key => $value){
            $b[$key] = $value;
        }
        return $b->save();
    }
}

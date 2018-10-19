<?php
namespace app\common\model;
use think\Model;
class RechargeRecord extends Model{
    protected $table = "recharge_record";
    public function insert($data){
        $c = new RechargeRecord();
        foreach($data as $key => $value){
            $c[$key] = $value;
        }
        return $c->save();
    }

    public function GetRecordByUser($uid){
        return $this->where(['userid'=>$uid])->select();
    }
        public function GetRecordById($id){
        return $this->where(['id'=>$id])->find();
    }
}
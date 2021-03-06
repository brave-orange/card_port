<?php
namespace app\index\model;
use think\Model;
class OrderRecord extends Model{
    protected $table = "order_record";
    public function insert($data){
        $c = new OrderRecord();
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
<?php
namespace app\chuanze\model;
use think\Model;
class HaochongOrder extends Model{
    protected $table = "haochong_order";
    public function insert($data){
        $h = new HaochongOrder();
        foreach($data as $key => $value){
            $h[$key] = $value;
        }
        return $h->save();
    }
}
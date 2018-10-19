<?php
namespace app\common\model;
use think\Model;

class CardUsed extends Model{
    protected $table="card_used";
    public function insert($data){
        $c = new CardUsed();
        foreach($data as $key => $value){
            $c[$key] = $value;
        }
        return $c->save();
    }
}
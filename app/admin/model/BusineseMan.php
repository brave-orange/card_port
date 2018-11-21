<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class BusineseMan extends Model{

    protected $table="businese_man";
    private function insert($data){

        $c = new BusineseMan();
        foreach($data as $key => $value){
            $c[$key] = $value;
        }
        return $c->save();
    }
}
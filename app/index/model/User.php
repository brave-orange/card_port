<?php
namespace app\index\model;
use think\Model;


class Card extends Model{

    protected $table="user";
    function __construct()
    {
        
    }
    public function findPerson($id){
        return $this->where(['id'=>$id])->find();
    }
}
<?php
namespace app\index\model;
use think\Model;


class User extends Model{

    protected $table="user";

    public function findPerson($id){
        return $this->where(['id'=>$id])->find();
    }
}
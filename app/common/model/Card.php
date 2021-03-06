<?php
namespace app\common\model;
use think\Model;
use think\Db;

class Card extends Model{

    protected $table="card";

    private function insert($data,$type,$comp_id,$buy_id){

        $c = new Card();
        foreach($data as $key => $value){
            $c[$key] = $value;
        }
        $c['type'] = $type;
        $c['comp_id'] = $comp_id;
        $c['buy_id'] = $buy_id;
        return $c->save();
    }
    public function insertAll($data,$type,$comp_id,$buy_id){
        $error = array();
        foreach ($data as $value) {
            $c = array("card_no"=>$value[0],"password"=>md6($value[1]));
            if(!$this->insert($c,$type,$comp_id,$buy_id)){
                $error[] = $c;
            }
        }
        if(count($error)){
            return json("error","部分数据存储失败！",$error);
        }else{
            return json("success","存储成功!");
        }
    }
    public function delete_card($card_no){
        return $this->where(['card_no'=>$card_no])->delete();
    }
    public function getcard($card_no){   //获取卡号和密码
        $res = $this->where(array('card_no'=>$card_no))->find();
        return $res;
    }
}
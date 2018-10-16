<?php
namespace app\index\service;
use think\Model;
use think\Session;
class Card extends Model{
    public function check_passwd($card_no,$password){    //充值卡校验
        $c = model('Card')->getcard($card_no);
        if(!$c){
            return false;
        }
        if($c['password'] == md6($password)){
            return $c;
        }else{
            return false;
        }
    }

    public function recharge($card){    //充值操作  
        $user = model('user')->findPerson(Session::get('userid'));
        $num = (int)substr($card,18,3);
        if(card_is_real($card)){
            $user['yu_e'] += $num;
            $use_card = model("Card")->getcard($card);
            $data = array();
            foreach($use_card->data as $key => $value){
                $data[$key] = $value;
            }
            if(model("CardUsed")->insert($data)){
                $use_card->delete();        //在未使用卡的表里删除记录
            }
            return $user->save();
        }else{
            return false;
        }
        
    }
}
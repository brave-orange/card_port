<?php
namespace app\index\service;
use think\Model;
use think\Session;
class Card extends Model{
    public function check_passwd($card_no,$password){    //充值卡充余额校验
        $c = model('Card')->getcard($card_no);
        if($c == md6($password)){
            return $c;
        }else{
            return false;
        }
    }

    public function recharge($card){    //充值操作  
        $user = model('user')->findPerson(Session::get('id'));
        $num = (int)strsub($card,18,3);
        $user['yu_e'] += $num;
        return $user->save();
    }
}
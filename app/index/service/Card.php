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
    public function check_type($card_no,$type){    //充值卡类型校验
        $c = model('Card')->getcard($card_no);
        if(!$c){
            return false;
        }
        if($c['type'] == $type){
            return true;
        }else{
            return false;
        }
    }

    public function recharge($card){    //充值操作  
        //$user = model('user')->findPerson(Session::get('userid'));
        //$num = (int)substr($card,18,3);
        if(card_is_real($card)){
            $use_card = model("Card")->getcard($card);
            $data = array();
            foreach($use_card->data as $key => $value){
                $data[$key] = $value;
            }
            $data['password']= create_token(10);
            if(model("CardUsed")->insert($data)){    //卡片冲完之后放到已经使用过的卡中去
                $use_card->delete();        //在未使用卡的表里删除记录
            }
            return true;
            //return $user->save();
        }else{
            return false;
        }
        
    }

    public function BuyCard($comp_id,$val,$number,$card_type,$opera_man){        //写入购卡记录
        model('BuyCardRecord')->insert(['card_val'=>$val,'number'=>$number,'card_type'=>$card_type,'company_code'=>$comp_id,'time'=>date("Y-m-d H:i:s"),'operat_ip'=>$_SERVER["REMOTE_ADDR"],'operat_man'=>$opera_man]);
    }
}
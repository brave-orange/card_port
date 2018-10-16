<?php
/***
生成卡号
***/
namespace app\common;

use think\Cache; 

class Cards{
    public function __construct($company_code){
        $this->company_code = $company_code;
        $this->coefficient = [7,9,12,5,3,25,4]; 
        $date = date("Y-m-d 23:59:59");
        $time = strtotime($date) - time();  //有效期为当天
        if(Cache::get("num") == null){

            $data = array();
            $data[$company_code] = 0;
            Cache::set('num',$data,$time);
        }else if(!isset(Cache::get("num")[$company_code])){
            
            $data = Cache::get("num");
            $data[$company_code] = 0;
            Cache::set('num',$data,$time);
        }
    }
    public function create_card_no($card_num,$money){      //生成连续的卡号
        $card_no = array();
        $card_cache = Cache::get("num");
        $num = $card_cache[$this->company_code];
        for ($i = $num; $i < $num+$card_num; $i++){
            $pici = sprintf("%06d",$i);
            $date = date("Ymd");
            $money = sprintf("%03d",$money);
            $no = $this->company_code.$date.$pici.$money;
            $sum = 0;
            foreach(str_split($no,3) as $key=>$value){
                if($key == 6)
                {
                    break;
                }else{
                    $sum += (int)$value * $this->coefficient[$key];
                }   
            }
            $check_no = sprintf("%02d",abs($sum%100));
            $no .= $check_no;
            $card_no[] = $no;
        }
        $card_cache[$this->company_code] = $num+$card_num;
        $date = date("Y-m-d 23:59:59");
        $time = strtotime($date) - time();
        $num = Cache::set('num',$card_cache,$time);
        return $card_no;
    }
    public function create_password($card_num,$psw_len){   //生成随机密码
        $str='abc7r1lmosvwxyfghtuijkq45zden6023p89';   //密码种子
        $str = str_shuffle($str);
        $len=strlen($str)-1;
        $p = array();
        for($s = 0;$s<=$card_num ;$s++)
        {
            $randstr='';
            for($i=0;$i<$psw_len;$i++){
                $num=rand(0,$len);
                $randstr .= $str[$num];    //随机顺序组成随机密码
            }
            $p[] = $randstr;
        }
        return $p;
    }
    public function getCache(){
        return Cache::get("num");
    }

}



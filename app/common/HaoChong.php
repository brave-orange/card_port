<?php
/**
 *好充接口类
 * 
 */
namespace app\common;
class HaoChong{
    static public $userid = "123";  //wait...
    static public $productid = "";
    static public $num = 1;
    static public $key = "4231414";    //wait... 
    static public $back_url = "https://card.onmycard.com.cn/ttt";
    public function __construct(){
        $this->spordertime=date("Y-m-d H:i:s");
        
    }
    public function recharge($phone,$value,$sporderid){
        $this->price = $value;
        $this->mobile = $phone;
        $this->sporderid = $sporderid;
        $this->url = "http://180.96.21.204:8082/onlinepay.do";
        $params = array();
        $sign = $this->getsign();
        $params['sign'] = $sign;
        $params['userid'] = self::$userid;
        $params['productid'] = self::$productid;
        $params['num'] = self::$num;
        $params['price'] = $this->price;
        $params['mobile'] = $this->mobile;
        $params['sporderid'] = $this->sporderid;
        $params['spordertime'] = $this->spordertime;
        $params['back_url'] = self::$back_url;
        $xml_res =  http($this->url,$params,"POST");
        return $xml_res;
        
    }
    public function getOrder($orderid){    //查询订单
        $this->url = "http://180.96.21.204:8082/searchpay.do";
        $params['userid'] = self::$userid;
        $params['sporderid'] = self::$orderid;
        $xml_res =  http($this->url,$params,"POST");
        return $xml_res;
    }

    public function getBalance(){     //查询余额
        $this->url = "http://180.96.21.204:8082/searchbalance.do";
        $sign = md5(self::$userid.self::$key);
        $params['sign'] = $sign;
        $params['userid'] = self::$userid;
        $xml_res =  http($this->url,$params,"POST");
        return $xml_res;
    }
    private function getsign(){
        return md5("userid=".self::$userid."&productid=".self::$productid."&price=".$this->price."&num=".self::$num."&mobile=".$this->mobile."&spordertime".$this->spordertime."&sporderid=".$this->sporderid."&key=".self::$key);
    }

}
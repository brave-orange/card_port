<?php
/**
 *好充接口类
 * 
 */
namespace app\common;
class HaoChong{
    static public $userid = "10001955";   //正式
    //static public $userid = "10001001";  //wait...（测试id）
    static public $productid = "";
    static public $num = 1;
    static public $key = "mxt7fjSDf7E55tG4AF3ZzpxpXAtdFjXz";    //正式
    //static public $key = "bb673528fe8082614f9c3a5bdf2703d7";    //wait... （测试key）
    static public $back_url = "http://card.onmycard.com.cn/backapi";
    public function __construct(){
        $this->spordertime=date("Y-m-d H:i:s");
        
    }
      /**
     * 充话费
     * @param  string  $phone 手机号
     * @param  int     $value 充值金额
     * @return array   $sporderid   商家订单号
     */
    public function recharge($phone,$value,$sporderid){   
        $this->price = $value;
        $this->mobile = $phone;
        $this->sporderid = $sporderid;
        //$this->url = "http://180.96.21.204:8082/onlinepay.do";    //正式
        $this->url = "http://121.40.152.174:28096/onlinepay.do";   //测试
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
        //$this->url = "http://180.96.21.204:8082/searchpay.do";
        $this->url = "http://121.40.152.174:28096/searchpay.do";
        $params['userid'] = self::$userid;
        $params['sporderid'] = self::$orderid;
        $xml_res =  http($this->url,$params,"POST");
        return $xml_res;
    }

    public function getBalance(){     //查询余额
        $this->url = "http://180.96.21.204:8082/searchbalance.do";
        //$this->url = "http://121.40.152.174:28096/searchbalance.do";
        $sign = md5("userid=".self::$userid."&key=".self::$key);
        $params['userid'] = self::$userid;
        $params['sign'] = $sign;
        $xml_res =  http($this->url,$params,"POST");
        return $xml_res;
    }
    private function getsign(){
        return md5("userid=".self::$userid."&productid=".self::$productid."&price=".$this->price."&num=".self::$num."&mobile=".$this->mobile."&spordertime".$this->spordertime."&sporderid=".$this->sporderid."&key=".self::$key);
    }

}
<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function json($status,$msg="",$data=array()){
  $result=array(
   'status'=>$status,
   'msg'=>$msg,
   'data'=>$data
  );
  //输出json
  return json_encode($result,JSON_UNESCAPED_UNICODE);
  exit;
}       //json格式化输出方法

function create_token($token_len){   //生成随机token
    $str='yc7r1avwxzdelmbnosjkq45fghtui6023p89';   //密码种子
    $str = str_shuffle($str);    //打乱字符串
    $len=strlen($str)-1;
    $randstr='';
    for($i=0;$i<$token_len;$i++){
        $num=rand(0,$len);
        $randstr .= $str[$num];    //随机顺序组成随机密码
    }
    return $randstr;
}

function md6($password){
    $key = "yesyouare";
    $m = md5($password).md5($key);
    $password = md5($m);
    return $password;    
    exit;    
}    //密码加密

function SendMessage($tel,$message){
    $SmsDemo = new SmsDemo();
    $response = $SmsDemo->SendSmsRequest($tel,$message);
    if($response==0){
      return 0;
    }else{
      return $response;
    }
}


function user_balance($userid){    //通过充值和消费计算用户余额
    $balance = array();
    $res = model("Card")->group('type')->field('type')->select();
    $type = array();
    foreach($res as $k=>$v){
        $type[] = $v;
        dump($v);
        //$balance[$v] = 0;
    }                   //取出各种类型

    unset($res);
    $res = model("RechargeRecord")->where(['userid'=>$userid])->group('type')->field('type,sum(money) as money')->select();
    foreach($res as $key=>$value){
        $balance[$value['type']] = (int)$value['money'];
    }                                  //充值的钱
    unset($res);
    $res = model("OrderRecord")->where(['userid'=>$userid])->group('order_type')->field('order_type,sum(money) as money')->select();
    dump($res);
    foreach($res as $key=>$value){
        $balance[$value['order_type']] -= (int)$value['money'];
    }                  //减去使用掉的钱
    return $balance;   

}


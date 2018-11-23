<?php

// 应用公共文件
// 
use lib\api_demo\sendFilePsw;
use lib\api_demo\sendFail;
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
    $str='yc7r1ambwjkxz6p8dqelnvos45f03uigh2t9';   //密码种子
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

function SendMessage($tel,$filename,$message){
    $SmsDemo = new sendFilePsw();
    $response = $SmsDemo->sendPasswd($tel,$filename,$message);
    return $response;

}
function SendWarring($tel,$time,$phone,$money){
    $SmsDemo = new sendFail();
    $response = $SmsDemo->sendWarring($tel,$time,$phone,$money);
    return $response;

}
function SendKey($tel,$name,$key){
    $SmsDemo = new sendFilePsw();
    $response = $SmsDemo->sendKey($tel,$name,$key);
    return $response;

}


function user_balance($userid){    //通过充值和消费计算用户余额
    $balance = array();
    $res = model("Card")->group('type')->field('type')->select();
    //$type = array();
    foreach($res as $k=>$v){
        //$type[] = $v;
        $balance[$v['type']] = 0;
    }                   //取出各种类型
    $balance['hf'] = 0;
    $balance['yk'] = 0;
    unset($res);
    $res = model("RechargeRecord")->where(['userid'=>$userid])->group('type')->field('type,sum(money) as money')->select();
    foreach($res as $key=>$value){
        $balance[$value['type']] = (int)$value['money'];
    }                                  //充值的钱
    unset($res);
    $res = model("OrderRecord")->where(['userid'=>$userid,'status'=>'1'])->group('order_type')->field('order_type,sum(money) as money')->select();

    foreach($res as $key=>$value){
        $balance[$value['order_type']] -= (int)$value['money'];
    }                  //减去使用掉的钱
    return $balance;   

}

function card_error_log($card_no,$msg = null){   //卡号存储错误计入日志
    $masg = "";
    for($i = 0 ; $i < count($card_no) ; $i++){
        $masg = '['.date("Y-m-d H:i:s").']'.'卡号：'.json_encode($card_no[$i]).$msg;

    }
            // 日志文件名：日期.txt
    $path = RUNTIME_PATH.DS.'cardNo_log'. DS .date("Ymd").'.txt';
    file_put_contents($path, $masg.PHP_EOL,FILE_APPEND);
}

function financial_log($data,$msg = null){   //存储财务审核日志
    $masg = '['.date("Y-m-d H:i:s").']        ';
    foreach ($data as $key => $value) {
        $masg .= $key.':'.$value."  |  ";
    }
            // 日志文件名：日期.txt
    $path = RUNTIME_PATH.DS.'log'. DS .'financial_log.txt';
    file_put_contents($path, $masg.PHP_EOL,FILE_APPEND);
}

  /**
     * 发送HTTP请求方法
     * @param  string $url    请求URL
     * @param  array  $params 请求参数
     * @param  string $method 请求方法GET/POST
     * @return array  $data   响应数据
     */
    function http($url, $params, $method = 'GET', $header = array(), $multi = false){
        $opts = array(
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => $header
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) throw new Exception('请求发生错误：' . $error);
        return  $data;
    }


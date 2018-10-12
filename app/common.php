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
    $str='abc7r1lmosvwxyfghtuijkq45zden6023p89';   //密码种子
    $str = str_shuffle($str);
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




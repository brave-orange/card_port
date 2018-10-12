<?php
function card_is_real($card_no){ //验证卡号是否可用
    if(config('card_out_time') != 0){     //是否设置过期时间
        $date = strtotime(substr($card_no,4,8));
        $t = config('card_out_time');
        if(time() - $date > $t*86400){
            return false;
        }else{
            $check = substr($card_no,-2);
            $str = substr($card_no,0,-2);
            $check_arr = config('check_num');
            $sum = 0;
            foreach(str_split($str,3) as $key=>$value){    //拆开求校验位
                if($key == 6)
                {
                    break;
                }else{
                    $sum += (int)$value * $check_arr[$key];
                }   
            }
            $check_no = sprintf("%02d",abs($sum%100));
            if($check_no == $check){
                return true;
            }else{
                return false;
            }
        }
    }
}


function card_error_log($card_no,$msg = null){
        //$msg = [2018-04-11 09:22:56]文件名：wxpay，第29行，[info]：日志信息 
    $masg = "";
    for($i = 0 ; $i < count($card_no) ; $i++){
        $masg = '['.date("Y-m-d H:i:s").']'.'卡号：'.json_encode($card_no[$i]).$msg;

    }
            // 日志文件名：日期.txt
    $path = RUNTIME_PATH.DS.'cardNo_log'. DS .date("Ymd").'.txt';
    file_put_contents($path, $masg.PHP_EOL,FILE_APPEND);
}
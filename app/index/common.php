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

<?php
namespace app\admin\service;
class Cardmanage{
    public function getApply($name){
        $res = model('BuyCardRecord')->alias('a')
            ->join('company_code b','a.company_code = b.comp_id')
            ->where(['operat_man'=>$name])
            ->order('is_pass','desc')
            ->order('time','desc')
            ->select();
        $result = array();
        foreach ($res as $key => $value) {
            $result[$key] = array('company_name'=>$value['name'],'time'=>$value['time'],'number'=>$value['number'],'face_value'=>$value['card_val'],'pay_money'=>$value['pay_money'],'is_pass'=>$value['is_pass']);
            if($value['is_pass'] == 1){
                $url = $_SERVER['SERVER_NAME'].'/givemefile?dfile='.$value['zip_file_name'];
                $result[$key]['download'] = $url;
            }
        }
        return $result;
    }
}
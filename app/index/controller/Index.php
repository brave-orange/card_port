<?php
namespace app\index\controller;
use PHPExcel;
use PHPExcel_IOFactory;
use app\common\Card;
use \think\Request;
use \think\Db;
use \think\Session;
class Index
{
    public function index()
    {
        if(Request::instance()->isGet()){
            $company_code = input('param.code');
            $num = input('param.num');
            $fvalue = input('param.face_value');
            $token = input('param.token');
            if(Session::get('token') == ""){
                return json('error','请先获取token!');
            }
            $key = md5($company_code.$num.$fvalue.Session::get('token'));
            if($company_code == '' || $num == '' || $fvalue == '' || $fvalue == ''){
                return json('error','参数错误！');
            }
            if($key != $token){
                return json('error','接口验证错误,请重试！');
            }
            $PHPExcel = new PHPExcel();
            $card = new Card($company_code);
            $path = $_SERVER['DOCUMENT_ROOT']."/download";
            $PHPSheet = $PHPExcel->getActiveSheet();
            $PHPSheet->setTitle('demo');
            $PHPSheet->setCellValue('A1','卡号');
            $PHPSheet->setCellValue('B1','密码');
            $c_no = $card->create_card_no($num,$fvalue);//生成卡号
            $c_psw = $card->create_password($num,6);//生成密码
            foreach ($c_no as $key => $value) {
                $s = 'A'.($key+2);
                $PHPSheet->setCellValue($s,$value);
                $s = 'B'.($key+2);
                $PHPSheet->setCellValue($s,$c_psw[$key]); 
            }
            $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//
            $filename = $company_code.date('_YmdHis_').$fvalue.'面值'.$num.'张.xlsx';
            $path = $path.'/'.$filename;
            $path =  (strtolower(substr(PHP_OS,0,3))=='win') ? mb_convert_encoding($path,'gbk','UTF-8') : $path;   //文件名编码问题
            $PHPWriter->save($path); 
            Session::set('token','');
            return $_SERVER['SERVER_NAME'].'/download/'.$filename;
        }
    }

    public function api_token(){           //动态token验证
        if(Request::instance()->isGet()){
            $comp_id = input('param.comp_id');
            $key = input('param.key');
            if($key == '' || $comp_id == ''){
                return json('error','参数错误！');
            }
            $t = Db::table('company_code');
            if($t->where(['comp_id'=>$comp_id,'key'=>md5($key)])->find()){
                $token = create_token(8);
                Session::set('token',$token);
                return $token;

            }else{
                return json('error','参数错误！');
            }
        }

    }


    public function card_recharge(){    //使用充值卡充值
        if(Request::instance()->isGet()){
            $card_no = input('param.card_no');
            $password = input('password');
            if(card_is_real("11012017100900010005067")){
                return "对的";
            }else{
                return json('error','该充值卡不存在，请检查卡号');
            }
        }
    }



}


<?php
namespace app\index\controller;
use PHPExcel;
use PHPExcel_IOFactory;
use app\common\Cards;
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
            $operat_man = input('param.operat_man');
            $card_type = input('param.card_type');
            if(Session::get('token') == ""){
                return json('error','请先获取token!');
            }
            $key = md5($company_code.$num.$fvalue.Session::get('token'));
            if($company_code == '' || $num == '' || $fvalue == '' || $fvalue == '' || $operat_man == ''){
                return json('error','参数不全！');
            }
            if($key != $token){
                return json('error','接口验证错误,请重试！');
            }
            $PHPExcel = new PHPExcel();
            $card = new Cards($company_code);
            $path = $_SERVER['DOCUMENT_ROOT']."/download";
            $PHPSheet = $PHPExcel->getActiveSheet();
            $PHPSheet->setTitle('demo');
            $PHPSheet->setCellValue('A1','卡号');
            $PHPSheet->setCellValue('B1','密码');
            $c_no = $card->create_card_no($num,$fvalue);//生成卡号
            $c_psw = $card->create_password($num,6);//生成密码
            $card_data = array();
            for($i = 0; $i < count($c_no) ; $i ++){    
                $card_data[] = [$c_no[$i],$c_psw[$i]]; 
            }
            $res = model("Card")->insertAll($card_data);//将卡号密码存入数据库
            if(json_decode($res)->status == "error"){     //如果有出现错误的重新存储一遍，若还是存储错误的写入日志
                $re = model("Card")->insertAll(json_decode($res).data);
                if(json_decode($re)->status == "error"){
                    card_error_log(json_decode($re).data,"数据库存储出错！");    //写入日志
                }
            }
            foreach ($c_no as $key => $value) {
                $s = 'A'.($key+2);
                $PHPSheet->setCellValue($s,$value);
                $s = 'B'.($key+2);
                $PHPSheet->setCellValue($s,$c_psw[$key]); 
            }

            //Set document security 设置文档安全
            $PHPExcel->getSecurity()->setLockWindows(true);
            $PHPExcel->getSecurity()->setLockStructure(true);
            $PHPExcel->getSecurity()->setWorkbookPassword("PHPExcel");

            //Set sheet security 设置工作表安全
            $PHPExcel->getActiveSheet()->getProtection()->setPassword('PHPExcel');
            $PHPExcel->getActiveSheet()->getProtection()->setSheet(true);// This should be enabled in order to enable any of the following!
            $PHPExcel->getActiveSheet()->getProtection()->setSort(true);
            $PHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
            $PHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);

            $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//
            $filename = $company_code.date('_YmdHis_').$fvalue.'面值'.$num.'张.xlsx';
            $path = $path.'/'.$filename;
            $path =  (strtolower(substr(PHP_OS,0,3))=='win') ? mb_convert_encoding($path,'gbk','UTF-8') : $path;   //文件名编码问题
            $PHPWriter->save($path); 
            model('Card','service')->BuyCard($company_code,$fvalue,$num,$card_type,$operat_man);    //保存购卡记录
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
        Session::set('userid','1');
        if(Request::instance()->isGet()){
            $card_no = input('param.card_no');
            $password = input('param.password');
            if(strlen($card_no) != 23){
                return json('error','该充值卡不存在，请检查卡号');
            }
            if(card_is_real($card_no)){
                $c = model('Card','service')->check_passwd($card_no,$password);
                if($c){
                    if(model('Card','service')->recharge($c['card_no'])){
                        model('RechargeRecord','service')->addRecord($card_no); //插入充值记录
                        return json('success','充值成功!');
                    }else{
                        return json('error','卡号异常，请检查后重试，如重复出现此情况请联系客服。');
                    }
                }else{
                    if(model("CardUsed")->where(['card_no'=>$card_no])->find()){
                        return json('error','该充值卡已使用过！');
                    }
                    return json('error','充值卡密码错误');
                }
            }else{
                return json('error','该充值卡不存在或已过期，请检查卡号');
            }
        }
    }

    
    public function test(){
        //return json_encode(user_balance(1));
        $PHPExcel = new PHPExcel();
        $PHPSheet = $PHPExcel->getActiveSheet();
        $PHPSheet->setTitle('demo');
        $PHPSheet->setCellValue('A1','卡号');
        $PHPSheet->setCellValue('B1','密码');
        $path = $_SERVER['DOCUMENT_ROOT']."/download";
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//
        $filename = '112.xlsx';
        $path = $path.'/'.$filename;
        
        $PHPWriter->save($path); 
        exec("zip -P ".$path." ".str_replace('.xlsx', '.zip', $path));
        return $_SERVER['SERVER_NAME'].'/download/'.str_replace('.xlsx', '.zip', $filename);
    }


}


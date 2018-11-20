<?php
namespace app\admin\controller;
use PHPExcel;
use PHPExcel_IOFactory;
use SimpleXMLElement;
use app\common\Cards;
use think\Db;

class Financial{    //财务控制器
    public function index(){
        $res = model('Financial','service')->getApply();
        if($res){
            return json('success','',$res);
        }else{
            return json('error','出错了');
        }
    }
    public function cardUsedCount(){
        return json('success','',model('Financial','service')->cardUsedCount());
    }
    public function not_pass_card_apply(){
        $id = input("param.apply_id");
        $rec = model("BuyCardRecord")->where(['id'=>$id])->find();
        if($rec){
            $rec['is_pass'] = 0;//修改状态为未审核
            if($rec->save()){
                return json('success','操作成功！');
            }
        } 
        return json('error','申请已操作或不存在！');
    }
    public function pass_card_apply(){      //审核通过生成卡的申请
        $id = input("param.apply_id");
        $rec = model("BuyCardRecord")->where(['id'=>$id])->find();
        if(2 == $rec['is_pass']){
            $num = $rec['number'];$fvalue = $rec['card_val'];
            $filename = $rec['zip_file_name'];
            $company_code = $rec['company_code'];
            $operat_man = $rec['operat_man'];
            $card_type = $rec['card_type'];
            $PHPExcel = new PHPExcel();
            $card = new Cards($company_code);
            $path = $_SERVER['DOCUMENT_ROOT']."/download";
            $PHPSheet = $PHPExcel->getActiveSheet();
            $PHPSheet->setTitle('卡号密码');
            $PHPSheet->setCellValue('A1','卡号');
            $PHPSheet->setCellValue('B1','密码');
            $PHPSheet->setCellValue('D1',date('Y-m-d H:i:s'));
            $c_no = $card->create_card_no($num,$fvalue);//生成卡号
            $c_psw = $card->create_password($num,6);//生成密码
            $card_data = array();
            for($i = 0; $i < count($c_no) ; $i ++){    
                $card_data[] = [$c_no[$i],$c_psw[$i]]; 
            }
            $ya_password = create_token(8);   //压缩文件密码
            $phone = Db::table("businese_man")->where(array('name'=>$operat_man))->field('phone')->find()['phone'];
            $check_num = create_token(2);//文件区分校验位
            $msg_status = SendMessage($phone,$company_code.'_'.$fvalue.'元'.$num.'张_'.$card_type."_".$check_num,$ya_password);
            if(1 == $msg_status){         
                
                foreach ($c_no as $key => $value) {
                    $s = 'A'.($key+2);
                    $PHPSheet->setCellValue($s,$value);
                    $s = 'B'.($key+2);
                    $PHPSheet->setCellValue($s,$c_psw[$key]); 
                }

                $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//
                $filename = $company_code."_".$fvalue.'元'.$num.'张_'.$card_type."_".$check_num."_".date("md").'.xlsx';
                $path = $path.'/'.$filename;
                $path =  (strtolower(substr(PHP_OS,0,3))=='win') ? mb_convert_encoding($path,'gbk','UTF-8') : $path;   //文件名编码问题
                $PHPWriter->save($path); 
                exec("cd download && zip -P ".$ya_password." ".str_replace('.xlsx', '.zip', $filename)." ".$filename);
                exec("rm -rf  ".$path);

                
                $res = model("Card")->insertAll($card_data,$card_type,$company_code,$id);//将卡号密码存入数据库
                if(json_decode($res)->status == "error"){     //如果有出现错误的重新存储一遍，若还是存储错误的写入日志
                    $re = model("Card")->insertAll(json_decode($res).data,$card_type,$company_code,$buy_id);
                    if(json_decode($re)->status == "error"){
                        card_error_log(json_decode($re).data,"数据库存储出错！");    //写入日志
                    }
                }
                $rec['is_pass'] = 1;
                $rec['start_no'] = $c_no[0];
                $rec['end_no'] = $c_no[$num-1];
                $rec['filename'] = str_replace('.xlsx', '.zip', $filename);
                if($rec->save()){
                    return json('success','审核成功，卡号已生成并通知运营。');
                }else{
                    return json('success','出了一些问题，请联系管理员。');
                }
                
            }
        }else{
            return json('error','该单已经操作过！');
        }
    }
}
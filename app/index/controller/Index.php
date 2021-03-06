<?php
namespace app\index\controller;
use PHPExcel;
use PHPExcel_IOFactory;
use SimpleXMLElement;
use app\common\Cards;
//use app\common\HaoChong;
use \think\Request;
use \think\Db;
use \think\Session;
use think\Cache; 
use think\Controller;
/***
接口验证实现
**/
class Index extends Controller
{
    public function index(){
        $this->redirect('/recharge');
    }

   
    public function zipapi()
    {
        if(Request::instance()->isPost()){
            $company_code = input('param.code');
            $num = input('param.num');
            $fvalue = input('param.face_value');
            $token = input('param.token');
            $operat_man = input('param.operat_man');
            $card_type = input('param.card_type');
            $pay_way = input('param.pay_way');
            $pay_money = input('param.pay_money');
            $ip = input("param.ip");
            if(Cache::get('token') == ""){
                return json('error','请先获取token!');
            }
            $key = md5($company_code.$num.$fvalue.Cache::get('token')[''.$operat_man]);
            if($company_code == '' || $num == '' || $fvalue == '' || $card_type == '' || $operat_man == ''){
                return json('error','参数不全！');
            }
            if($key != $token){
                return json('error','接口验证错误,请重试！');
            }
            $check_num = create_token(2);//文件区分校验位
            //$filename = $company_code."_".$fvalue.'元'.$num.'张_'.$card_type."_".$check_num."_".date("md").'.xlsx';
            $buy_id = model('Card','service')->BuyCard($company_code,$fvalue,$num,$card_type,$operat_man,"","","",$pay_way,2,$pay_money,$ip);    //保存购卡记录,待审核
            
            $t = Cache::get('token');
            $t[''.$operat_man] = "";
            Cache::set('token',$t);

                /*return $_SERVER['SERVER_NAME'].'/givemefile?dfile='.str_replace('.xlsx', '.zip', $filename);*/
            if(!$buy_id){
                return json('error','系统出错，请联系管理人员！',$msg_status);
            }else{
                return json('success','提交成功，请等待财务审核！');
            }
        }
    }
            
    
    

    public function api_token(){           //动态token验证 //加入运营人员登陆后改为cache存储token
        if(Request::instance()->isPost()){
            $comp_id = input('param.comp_id');
            $key = input('param.key');
            $operat_man=input("param.operat_man");
            if($key == '' || $comp_id == '' || $operat_man==''){
                return json('error','未传参数错误！');
            }
            $key=md5($key);
            $t = Db::table('company_code');
            if($t->where(['comp_id'=>$comp_id,'key'=>$key])->find()){
                $token =create_token(8);
                $token_arr = Cache::get('token'); 
                if(is_array($token_arr) && isset($token_arr[$operat_man])){
                    $token_arr[''.$operat_man] = $token;
                }else{
                    $token_arr = array(''.$operat_man => $token);
                }
                if(Cache::set('token',$token_arr,3600)){
                    return $token;
                }
            }else{
                return json('error','参数错误！');
            }
        }
    }

    public function card_recharge(){    //使用充值卡充值
        //Session::set('userid','1');      //测试用，登录功能完成后删除
        if(Request::instance()->isPost()){
            $card_no = input('param.card_no');
            $password = input('param.password');
            $type = input('param.type');
            if("" == Session::get('userid')){
                return json('error','当前为未登录状态！');
            }
            if(strlen($card_no) != 23){
                return json('error','该充值卡不存在，请检查卡号');
            }
            if(card_is_real($card_no)){
                $c = model('Card','service')->check_passwd($card_no,$password);
                if($c){
                    if(!model('Card','service')->check_type($card_no,$type)){
                        return json('error','此充值卡并非此用途，请查正后在进行充值!');
                    }
                    if( model('RechargeRecord','service')->addRecord($card_no,$type)){//插入充值记录
                        model('Card','service')->recharge($card_no);
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
    public function cardcheck(){
        if(Request::instance()->isPost()){
            $card_no = input("param.card_no");
            if(card_is_real($card_no)){
                return json('success','real');
            }else{
                return json('error','卡号错误！');
            }
        }
    }
    public function recharge_log(){
        if(Request::instance()->isPost()){
            $userid = Session::get("userid");
            $log = model('RechargeRecord','service')->getLog($userid);
            if($log){
                return json('success','查询成功！',$log);
            }else{
                return json('error','无记录！',$log);
            }

        }
    }

    public function getCompCard(){
        $comp_id = input('param.comp_id');
        $res = model('Card','service')->getCompanyCard($comp_id);
        if($res){
            return json('success','',$res);
        }
        
    }
    
   /* public function test(){
        //return json_encode(user_balance(1));
/*        $PHPExcel = new PHPExcel();
        $PHPSheet = $PHPExcel->getActiveSheet();
        $PHPSheet->setTitle('demo');
        $PHPSheet->setCellValue('A1','卡号');
        $PHPSheet->setCellValue('B1','密码');
        $path = $_SERVER['DOCUMENT_ROOT']."/download";
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//
        $filename = '112.xlsx';
        $path = $path.'/'.$filename;
        
        $PHPWriter->save($path); 
        exec("cd download && zip -P whatthefuck ".str_replace('.xlsx', '.zip', $filename)." ".$path,$out,$status);
        dump($out);
        dump($status);
        return "zip -P whatthefuck ".str_replace('.xlsx', '.zip', $filename)." ".$path.'     '.$_SERVER['SERVER_NAME'].'/download/'.str_replace('.xlsx', '.zip', $filename)."  1.";
         *//*
        //return SendMessage('18012776312','ss','wefdsgrf');
        //$hc = new HaoChong();
        //$res = $hc->recharge("18012776312",100,"00000000001");
        //$res = $hc->getBalance();
        //$xml_res = new SimpleXMLElement($res);
        //dump($xml_res->resultno);
        //dump(config('haochong_status')['0']);
        //return SendWarring("18012776312","hfudsighu","18012776312","123");
       //SendMessage("18012776312",date('Y-m-d H:i:s'),"阿里巴巴","测试文件","gas54g");
   }
   */

}
<?php
namespace app\admin\controller;
use app\common\controller\AdminController;
use think\Db;
use think\Session;
use think\Request;

class Cardmanage extends AdminController{    //卡组控制器
    public function index(){
        $company = Db::table('company_code')->select();
        $name = Session::get('admin_man');
        $res = model('Cardmanage','service')->getApply($name);
        return $this->assign(array('company'=>$company,'record'=>$res))->fetch();;
    }
    public function _initialize(){
        if (Request::instance()->isGet()){
            if(1 != Session::get('admin_type')) {
                
                $this->redirect('admin/admin/login');
                //没登陆，跳转到登陆页
            }
        }else if (Request::instance()->isPost()){
            if(1 != Session::get('admin_type')) {             
                $this->error(["code"=>0,"msg"=>"未登录状态无法调用！"]);

            }
        }
    }
    public function addCompany(){
        if(Request::instance()->isPost()){
            $company_name = input('param.company_name');
            $res = Db::table('company_code')->insert(array('name'=>$company_name,'key'=>""));
            if($res){
                return json('success','添加成功！');
            }else{
                return json('success','添加失败，请重试！');
            }
        }
    }
    public function getKey(){   //随机获取一个公司的Key，更新，并发送到操作人的手机上
         if(Request::instance()->isPost()){
            //$operat_man = input('param.operat_man');
             //Session::set('admin_phone','18012776312');
            $phone = Session::get('admin_phone');
            $key = create_token(4);
            $comp_id = input('param.comp_id');
            if("" == $comp_id){
                return json('error','参数不全');
            }
            if("" == $phone){
                return json('error','发送出错！');
            }
            $t = Db::table('company_code');
            $comp_name = $t->where(['comp_id'=>$comp_id])->field('name')->find()['name'];
            $r = Db::table('company_code')->where(['comp_id'=>$comp_id])->update(['key'=>md5($key)]);
            if($r){
                if(!$phone){
                     return json('error','获取失败，请重试！');
                }
                if(sendKey($phone,$comp_name,$key)){
                    return json('success','请稍等短信通知。');
                }else{
                    return json('error','获取失败，请重试！');
            }

                
            }else{
                return json('error','获取失败，请重试！');
            }
        }
    }
    public function token(){
        if(Request::instance()->isPost()){
            $operat_man =Session::get('admin_man');
            $company_code = input('param.code');
            $num = input('param.num');
            $fvalue = input('param.card_val');
            $token = input('param.token');
            $company_key = input('param.company_key');
            $card_type = input('param.card_type');
            $pay_way = input('param.pay_way');
            $new_money = input('param.new_money');
            $ip = $_SERVER["REMOTE_ADDR"];

            if($company_code == '' || $num == '' || $fvalue == '' || $card_type == '' || $operat_man == '' || $company_key == '' || $new_money == ''){
                return json('error',$msg="参数不全！");
            }
            $url="card.onmycard.com.cn/index/index/api_token";
            $data=['comp_id'=>$company_code,'key'=>$company_key,'operat_man'=>$operat_man];
            $tokendata=http($url,$data,"POST");

            $token=trim($tokendata);
            if(!$token){
                return json('error','系统出错！');
            }
            
            $key = md5($company_code.$num.$fvalue.$token);
            $str=$company_code.$num.$fvalue.$token;
            $url="card.onmycard.com.cn/zipapi";
            $params=['code'=>$company_code,'num'=>$num,'face_value'=>$fvalue,'operat_man'=>$operat_man,'card_type'=>$card_type,'token'=>$key,'pay_money'=>$new_money,'pay_way'=>$pay_way,'ip'=>$ip];
            $retudata=http($url,$params,"POST");
            return $retudata;
            

        }
    }
    protected function is_json($data){
        if(json_decode($data,true)){
            return json_decode($data,true);
        }else{
            $token= trim($data);
            // $data=json_decode($data,true);
            return $token;
        }
    }

    // public function getApply(){ //获取运营的申请情况
    //     $name = Session::get('admin_man');
    //     $res = model('Cardmanage','service')->getApply($name);
    //     return json_encode($res,JSON_UNESCAPED_UNICODE);
    // }
}
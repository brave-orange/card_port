<?php
namespace app\admin\controller;
use app\common\controller\AdminController;
use think\Db;
use think\Request;

class Cardmanage extends AdminController{    //卡组控制器
    public function index(){
        $company = Db::table('company_code')->select();
        return $this->assign(array('company'=>$company))->fetch();;
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
            $t = Db::table('company_code');
            $comp_name = $t->where(['comp_id'=>$comp_id])->field('name')->find()['name'];
            $r = Db::table('company_code')->where(['comp_id'=>$comp_id])->update(['key'=>md5($key)]);
            if($r){
                if(!$phone){
                     return json('error','获取失败，请重试！');
                }
                sendKey($phone,$comp_name,$key);
                return json('success','请稍等短信通知。');
            }else{
                return json('error','获取失败，请重试！');
            }
        }
    }
    public function token(){
        if(Request::instance()->isPost()){
            $company_code = input("param.code");
            $num =input("param.num");
            $fvalue =input("param.face_value");
            $operat_man =input("param.operat_man");
            $card_type =input("param.card_type");
            $company_key=input("param.company_key");
            $new_money=input("param.new_money");
            if($company_code == '' || $num == '' || $fvalue == '' || $card_type == '' || $operat_man == '' || $company_key == '' || $new_money == ''){
                return json('error',$msg="参数不全！");
            }
            $url="card.onmycard.com.cn/index/index/api_token";
            $data=['comp_id'=>$company_code,'key'=>$company_key,'operat_man'=>$operat_man];
            $tokendata=http($url,$data,"POST");
            $token=$this->is_json($tokendata);
            if(is_array($token)){
                $str=$token['msg'];
                echo $str;
                die();
            }
            $key = md5($company_code.$num.$fvalue.$token);
            $str=$company_code.$num.$fvalue.$token;
            $urls="http://card.onmycard.com.cn/zipapi";
            $params=['code'=>$company_code,'num'=>$num,'face_value'=>$fvalue,'operat_man'=>$operat_man,'card_type'=>$card_type,'token'=>$key];
            $retudata=http($urls,$params,"POST");
            $ysb=$this->is_json($retudata);
            if(is_array($ysb)){
                $str=$ysb['msg'];
                echo $str;
                die();
            }
            echo "压缩文件路径为".$retudata;
            exit;
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
}
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
}
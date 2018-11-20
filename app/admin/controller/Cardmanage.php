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
}
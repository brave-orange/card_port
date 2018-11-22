<?php
namespace app\common\controller;

use think\Controller;
use think\Request; 
use think\Session; 
class AdminController extends Controller   //后台过滤器
{
    public function _initialize()
    {
        if (Request::instance()->isGet()){
            if("" == Session::get('admin_phone')) {
                
                $this->redirect('admin/admin/login');
                //没登陆，跳转到登陆页
            }
        }else if (Request::instance()->isPost()){
            if("" == Session::get('admin_phone')) {             
                $this->error(["code"=>0,"msg"=>"未登录状态无法调用！"]);

            }
        }

    }
}

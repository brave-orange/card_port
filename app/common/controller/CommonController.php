<?php


namespace app\common\controller;

use think\Controller;
use think\Request; 
use think\Session; 
class CommonController extends Controller
{
    public function _initialize()
    {
        if (Request::instance()->isGet()){
            if(NULL == Session::get('userid')) {
                
                $this->redirect('login');
                //没登陆，跳转到登陆页
            }
        }else if (Request::instance()->isPost()){
            if(NULL == Session::get('userid')) {             
                $this->error(["code"=>0,"msg"=>"未登录状态无法调用！"]);

            }
        }

    }
}

<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Request;

class Admin extends Controller{    //后台人登录控制器
    public function login(){
        return view();
    }

    public function dologin(){
        if(Request::instance()->isPost()){
            $phone = input('param.phone');
            $password = input('param.password');

            $t = model('BusineseMan')->where(['phone'=>$phone])->find();
            if($t['password'] == md6($password)){
                Session::set('admin_man',$t['name']);
                Session::set('admin_phone',$t['phone']);
                Session::set('admin_type',$t['type']);
                return json('success','登陆成功！');
            }
        }
    }
    public function into(){
        if(Request::instance()->isGet()){
            $type = Session::get('admin_type');
            if($type){
                switch ($type) {
                    case 1:    ///运营
                        $this->redirect('/Cardmanage');
                        break;
                    case 2:    //财务
                        $this->redirect('/Financial');
                        break;
                    case 3:    //客服
                        $this->redirect('/Customerservice');
                        break;

                }
            }else{
                return "error";
            }
        }
    }

}
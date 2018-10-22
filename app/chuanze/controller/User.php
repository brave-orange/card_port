<?php
namespace app\chuanze\controller;
use \think\Request;
use \think\Session;

// use \think\Controller;
class User 
{   
        //登录
    public function login(){
        if(Request::instance()->isGet()){
            return view();
        }
    }
    //注册
    public function register(){
        if (Request::instance()->isGet()) {
            return view();
        }
    }
    //修改密码
    public function forgetPsw(){
         if (Request::instance()->isGet()) {
            return view();
        }
    }
   	//登录
 	public function dologin(){
 		if(Request::instance()->isPost()){
 			$phone=input('param.phone');
 			$password=md6(input("param.password"));
 			$user=model('user','model')->seluser($phone,$password);
 			if($user){
 				  Session::set('phone',$user['phone']);
                  Session::set('id',$user['id']);
                  return json($status="success",$msg="登录成功！");
 			}else{
                  return json($status="error",$msg="账号或密码错误！");
            }

 		}
 	}
 	   //短信验证
    public function message(){
    	 if(request()->isPost()){
              return model('user','service')->message();
    	 }
    }
     //修改密码
    public function forgetps(){
        if(request()->isPost()){
            return model('user','service')->forgetps();
        }
    }
    //用户添加
    public function adduser(){
        if(Request::instance()->isPost()){
            return model('user','service')->register();
        }
    }
} 
?>
<?php
namespace app\chauzne\controller;

use \think\Request;
use \think\controller;
class Login extends controller
{
   	//登录
 	public function dologin(){
 		if(Request::param()){
 			$phone=Request::param('phone');
 			$password=md6(Request::param('password'));
 			$user=model('user','model')->seluser($phone,$password);
 			if($user){
 				Session::set('phone',$user['phone']);
                Session::set('userid',$user['userid']);
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
        if(request()->isPost()){
            return model('user','service')->selectname();
        }
    }
} 
?>
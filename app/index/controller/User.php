<?php
namespace app\index;

use think\Request;
use think\Controller;
class User extends Controller
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
 		if(Request::param()){
 			$phone=Request::param('phone');
 			$password=md6(Request::param('password'));
 			$user=model('user','model')->seluser($phone,$password);
 			if($user){
 				Session::set('phone',$user['phone']);
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
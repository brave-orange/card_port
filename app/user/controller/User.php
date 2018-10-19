<?php
/*******
 *登录控制器
 * *****/
 namespace app\user\controller;

 use \think\Request;
 use \think\Controller;
 use \think\Session;
 class user extends Controller{
 	//登录
 	public function login(){
 		if(Request::instance()->isGet()){
 			return view();
 		}
 		
 	}
 	//注册
 	public function	register(){
 		if (Request::instance()->isGet()) {
            return view();
        }
 	}
 	//修改密码
 	public function	forgetPsw(){
 		 if (Request::instance()->isGet()) {
            return view();
        }
 	}
 }
<?php
namespace app\chuanze\service;
// use think\Model;
use lib\api_demo\SmsDemo;
use app\chuanze\model\User as UserModel;
class User extends UserModel{	
	//注册
	public function	register(){
		
		if(input("param.tel") && input("param.password")){
			$phone=input("param.tel");
			$password=md6(input("param.password"));
			$user=new User();
			if(!$user->selname($phone)){
				if($user->adduser($phone,$password)){
				 return 1;
				}
			}else{
				 return 0;
			}
		}
	}
	//修改密码
	public function forgetps(){
		if(input("param.phone") && input("param.password")){
			$phone=input("param.phone");
			$password=md6(input("param.password"));
			$user=new UserModel();
			if($user->selname($phone)){
				if($user->updateuser($phone,$password)){
					return "修改成功";
				}
			}else{
				return "没有此用户!";
			}
		}		
	}
	//短信验证
	public function message(){
		if(!empty(input('param.tel'))){
		 $tel=input("param.tel");
		 $SmsDemo = new SmsDemo();
		 $response = $SmsDemo->sendSms($tel);
			if($response==0){
				return 0;
			}else{
				return $response;
			}
		
		}
				
	}
}

?>
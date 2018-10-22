<?php
namespace app\chuanze\model;
use think\Model;
use think\Db;
class User extends Model 
{
	protected $table='user';
   //登录验证
	public function seluser($phone,$password){
		return Db::name($this->table)->
		where(array('phone'=>$phone,'password'=>$password))->
		find();
	}
	//查询
	public function selname($phone){
		return Db::name($this->table)->
		where('phone',$phone)->
		find();
	}
	//注册
	public function adduser($phone,$password){
		return Db::name($this->table)->
		insert(array('phone'=>$phone,'password'=>$password));
	}
	//修改密码
	public function updateuser($phone,$password){
		return Db::name($this->table)->
		where('phone',$phone)->
		update(['password',$password]);
	}
}
?>
<?php
namespace app\index\model;
use think\Model;
use think\Db;
class User extends Model
{
	protected $table='user';
   //登录验证
	public function seluser($phone,$password){
		return Db::name($table)->
		where(array('phone'=>$phone,'password'=>$password))->
		find();
	}
	//查询
	public function selname($phone){
		return Db::name($table)->
		where('phone',$phone)->
		find();
	}
	//注册
	public function adduser($phone,$password){
		return Db::name($table)->
		insert(array('phone'=>$phone,'password'=>$password));
	}
	//修改密码
	public function updateuser($phone,$password){
		return Db::name($table)->
		where('phone',$phone)->
		update(['password',$password]);
	}
	public function findPerson($userid){
		return $this->where(['id'=>$userid])->find();

	}
    public function getBalance($userid){
        return user_balance($userid);
    }
}
?>
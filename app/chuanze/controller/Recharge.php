<?php

namespace app\chuanze\controller;
use \think\Request;
use \think\Db;
use \think\Session;
use \think\Controller;

class Recharge extends Controller{
    public function index(){
         if(Request::instance()->isGet()){
            Session::set("userid",1); //测试用，记得删除！
            $userid = Session::get("userid");

            $balance = user_balance($userid);
            return $this->assign(array("balance"=>$balance))->fetch("index");

            //return view();
        }
    }
}
<?php

namespace app\chuanze\controller;
use \think\Request;
use \think\Session;
use \think\Controller;
use \app\common\controller\CommonController;
class Recharge extends CommonController{
    public function index(){
         if(Request::instance()->isGet()){
            
            $userid = Session::get("userid");

            $balance = user_balance($userid);
            return $this->assign(array("balance"=>$balance))->fetch("index");
        }
    }
}
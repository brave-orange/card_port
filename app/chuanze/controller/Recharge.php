<?php

namespace app\chuanze\controller;
use \think\Request;
use \think\Db;
use \think\Session;

class Recharge{
    public function index(){
         if(Request::instance()->isGet()){
            Session::set("userid",1); //测试用，记得删除！
            $userid = Session::get("userid");

            $balance = user_balance($userid);
            dump($balance);
            //return view();
         }
    }
}

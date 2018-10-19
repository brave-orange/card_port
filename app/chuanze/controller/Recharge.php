<?php

namespace app\chuanze\controller;
use \think\Request;
use \think\Db;
use \think\Session;
class Recharge{
    public function index(){
         if(Request::instance()->isGet()){
            return view();
         }
    }
}

<?php
namespace app\chuanze\controller;
use \think\Request;
use \think\Session;
use \think\Controller;
use \app\common\controller\CommonController;
class Shopping extends CommonController{
    public function index(){
        if(Request::instance()->isGet()){
            //return view();
        }
    }
    public function tel_recharge(){    //充话费
        if(Request::instance()->isGet()){
            $userid = Session::get("userid");
            $balance = user_balance($userid);

            return $this->assign(array('balance'=>$balance['hf']))->fetch();
        }
    }
    public function oil_recharge(){    //充话费
        if(Request::instance()->isGet()){
            $userid = Session::get("userid");
            $balance = user_balance($userid);

            return $this->assign(array('balance'=>$balance['yk']))->fetch();
        }
    }
}
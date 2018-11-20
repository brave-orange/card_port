<?php
namespace app\admin\controller;
use PHPExcel;
use PHPExcel_IOFactory;
use SimpleXMLElement;
use app\common\Cards;
use think\Db;

class Cardmanage{    //卡组控制器
    public function index(){
        return view();
    }
}
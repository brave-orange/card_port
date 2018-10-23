<?php
namespace app\chuanze\model;
use think\Model;
use think\Db;
class Download{
    public function download($filename){

        $download_num = model('DownloadRecord')->getDownload_num($filename);
        if($download_num>5){
            return false;
        }else{
            return true;
        }
  
        
    }
}
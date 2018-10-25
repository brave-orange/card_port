<?php
/*
***下载控制器***
 */
namespace app\chuanze\controller;
use \think\Request;
use \think\Session;
use \think\Controller;

//use \app\common\controller\BusineseController;
class Download{
    public function download(){
        if(Request::instance()->isGet()){
            $filename = input('param.dfile');
            $ip = $_SERVER["REMOTE_ADDR"];
            //$businese_man = Session::get("businese_man");
            $businese_man = input('param.man');
            $time = date("Y-m-d H:i:s");
            if($filename == ""){
                return json('error','文件名为空');
            }
            $file_dir = ROOT_PATH . 'public' . DS . 'download' . '/' . "$filename";
            if (! file_exists($file_dir) ) {
                return json('error','没有此文件');
            }else{
                if(model('Download','service')->download($filename)){
                    // 打开文件
                    $file1 = fopen($file_dir, "r");
                    if($file1){
                        model('DownloadRecord')->insert(['file_name'=>$filename,'businese_man'=>$businese_man,'ip'=>$ip,'time'=>$time]);
                        Header("Content-type: application/octet-stream");
                        Header("Accept-Ranges: bytes");
                        Header("Accept-Length:".filesize($file_dir));
                        Header("Content-Disposition: attachment;filename=" . $filename);
                        ob_clean();     // 重点！！！
                        flush();        // 重点！！！！可以清除文件中多余的路径名以及解决乱码的问题：
                        
                        echo fread($file1, filesize($file_dir));
                        fclose($file1);
                        
                        return json('success','下载完成。');
                    }else{
                        return json('error','系统出错！');
                    }
                }else{
                    return json('error','下载次数超过限制，请联系管理员。');
                }
            }
                
        }
    }
    public function test(){
        
        return model('DownloadRecord')->getDownloadIP_num("aa.txt");
    }
}
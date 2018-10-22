<?php

namespace app\user\model;
use think\Model;
use think\Db;
class DownloadRecord extends Model{
    protected $table="download_record";
    public function insert($data){
        $d = new DownloadRecord();
        foreach($data as $key => $value){
            $d[$key] = $value;
        }
        return $d->save();
    }
    public function getDownload_num($filename){     //下载次数
        return $this->where(['file_name'=>$filename])->count();
    }

    


}
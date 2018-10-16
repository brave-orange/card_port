<?php

class ConsumeRecord{
    protected $table = "consume_record";
    public function insert($data){
        $c = new ConsumeRecord();
        foreach($data as $key => $value){
            $c[$key] = $value;
        }
        return $c->save();
    }

    public function GetRecordByUser($uid){
        return $this->where(['userid'=>$uid])->select();
    }
        public function GetRecordById($id){
        return $this->where(['id'=>$id])->find();
    }
}
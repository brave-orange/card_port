<?php
namespace app\admin\service;

class Financial{

    public function getApply($start,$limit){
        $t =  model('BuyCardRecord')
            ->alias('a')->where(['is_pass'=>2])
            ->join('company_code b','a.company_code = b.comp_id')
            ->field('a.id,a.operat_man as apply_man,b.name as company_name,a.pay_money,a.time,a.pay_way')
            ->order('a.time desc')
            ->limit($start.','.$limit)
            ->select();
        $c = model('BuyCardRecord')
            ->alias('a')->where(['is_pass'=>2])
            ->join('company_code b','a.company_code = b.comp_id')
            ->field('a.id,a.operat_man as apply_man,b.name as company_name,a.pay_money,a.time,a.pay_way')
            ->order('a.time desc')
            ->limit($start.','.$limit)
            ->count();
        $res = array('count'=>$c,'data'=>$t);
        return $res;
    }

    public function cardUsedCount($start=null,$end=null,$comp_id=null){        //获取财务需要的数据（以用/未用）卡（数量/金额）
        $where = array();
        $res = model('Card')->alias('a')
            ->join('buy_card_record b','a.buy_id = b.id','left')
            ->join('company_code c','a.comp_id = c.comp_id','left')
            ->group('a.comp_id,a.is_used')
            ->field('c.name as company_name,a.comp_id,sum(card_val) as money,a.is_used,count(card_no) as count');
        if(null != $start && null != $end){
            $where['b.time'] = ['between',[$start,$end]];
            if(null != $comp_id){
                $where['a.comp_id'] = $comp_id;
            }
            $res->where($where);
        }
        $count = $res->select();
        $result = array();
        $n=0;
        $company= array();
        if(count($count)%2 == 1){
            $count[count($count)] = array('company_name'=>$count[count($count)-1]['company_name'],'comp_id'=>$count[count($count)-1]['company_name'],'money'=>0,'is_used'=>1,'count'=>0);
        }
        for ($i = 0 ; $i < count($count) ; $i+=2) {
            $result[$n]['company_name'] = $count[$i]['company_name'];
            $company[] = $count[$i]['comp_id'];
            if($count[$i]['is_used'] == 0){
                $result[$n]['not_used'] = $count[$i]['count'];
                $result[$n]['not_used_money'] = $count[$i]['money'];
            }else{
                $result[$n]['used'] = $count[$i]['count'];
                $result[$n]['used_money'] = $count[$i]['money'];
            }

            if($count[$i+1]['is_used'] == 0){
                $result[$n]['not_used'] = $count[$i+1]['count'];
                $result[$n]['not_used_money'] = $count[$i+1]['money'];
            }else{
                $result[$n]['used'] = $count[$i+1]['count'];
                $result[$n]['used_money'] = $count[$i+1]['money'];
            }
            $result[$n]['card_count'] = $result[$n]['used']+$result[$n]['not_used'];
            $n++;
        }

        return $result;
        //return $res->getLastSql();
    }
}
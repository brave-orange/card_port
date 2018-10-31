<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'test'        => 'index/index/test',
    'recharge'    => 'chuanze/recharge/index',
    'tel_recharge'=> 'chuanze/shopping/tel_recharge',
    'oil_recharge'=> 'chuanze/shopping/oil_recharge',
    'user_center' => 'chuanze/shopping/user_center',

    'givemefile'  => 'chuanze/download/download',    //下载文件路由
    'login'       => 'chuanze/user/login',
    'register'    => 'chuanze/user/register',
    'forgetPsw'   => 'chuanze/user/forgetPsw',
    'telpay'      => 'chuanze/Haochongapi/tel_pay',
    'backapi'     => 'chuanze/Haochongapi/backapi',
    'haochongbalance' => 'chuanze/Haochongapi/HaochongBalance'


];

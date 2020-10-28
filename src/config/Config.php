<?php
return [
    'debug' => 'dev',               // 调试模式
    'key' => 'lang',                // redis的key
    'lang' => ['cn', 'hk', 'en'],
    'baidu' => [
        //接口地址
        'url'=>'http://api.fanyi.baidu.com/api/trans/vip/translate',
        //appid
        'appid'=>'',
        //秘钥
        'serectid'=>'',
        //类型0为标准版 QPS=1,  1为高级版 QPS=10,  2为尊享版 QPS=100
        'type'=>0,
    ],
    'youdao' => [
        'url'=>'http://openapi.youdao.com/api',
        //appid
        'appid'=>'',
        //秘钥
        'serectid'=>'',
    ],
];
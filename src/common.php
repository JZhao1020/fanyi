<?php

/**
 * 获取译文
 * @param string $text 待译文案
 * @param string $lang 语言版本
 * @param string $group 分组
 * @param array $config
 * @param array $redis_config
 */
function getTextLang($text, $lang = 'cn', $group = 'default', $config = [], $redis_config = []){
    if(!$text || is_numeric($text))
        return $text;

    $file = __DIR__ . '/config/Config.php';
    $arr_config = include $file;
    $config = array_merge($arr_config, $config);

    try{
        if(!in_array($lang, $config['lang'])){
            return $text;
        }

        $redis = new \fanyi\lib\Redis($redis_config);
        $redis_key = $config['key'] .':'. $lang;
        // 查询是否存在
        $result = $redis->hGet($redis_key, $group);

        // 不存在，且正式环境，则直接返回原文
        if(!$result && $config['debug'] !== 'dev')
            return $text;

        if($result){
            $result = json_decode($result, true);
            if(isset($result[$text])){
                return $result[$text];
            }
        }

        $res_fanyi = fanyiSend($config, $text, $config['lang']);
        if(!$res_fanyi){
            // 翻译失败
            return false;
        }

        $fanyi = '';
        foreach ($res_fanyi as $key => $value){
            if($key == $lang)
                $fanyi = $value;

            $kk = $config['key'] .':'. $key;
            $res = $redis->hGet($kk, $group);
            $arr = [];
            if($res){
                $arr = json_decode($res, true);
            }
            $arr[$text] = $value;
            $redis->hSet($kk, $group, json_encode($arr));
        }

        return $fanyi;
    }catch (\Exception $e){
        if($config['debug'] == 'dev'){
            return $e->getMessage();
        }

        return $text;
    }
}

/**
 * 调用第三方接口翻译
 * @param $config
 * @param $text
 * @param $lang
 * @return bool|array
 */
function fanyiSend($config, $text, $langs){
    $fanyi = new \fanyi\Fanyi($config);

    foreach ($langs as $lang) {
        if($lang == 'cn'){
            // 中文则跳过
            $result['cn'] = $text;
            continue;
        }

        sleep(1);
        $result[$lang] = $fanyi->gateway('baidu')->send($text, $lang);
        if (!$result[$lang]) {
            $result[$lang] = $fanyi->gateway('youdao')->send($text, $lang);
        }

        if(!$result[$lang])
            return false;
    }

    return $result;
}

/**
 * 批量导入单种语言对应翻译数据
 * 示例：
  $arr = [
    'default' => [
        '今天' => '今天',
    ],
    'address' => [
        '地址错误' => '地址错误',
    ],
  ];
 * @param $array
 * @param string $lang 语言版本
 * @param string $key redis对应的key
 * @param array $redis_config redis配置
 * @return bool
 */
function importLang($array, $lang = 'cn', $key = 'lang', $redis_config = []){
    if(!$array)
        return false;

    $redis = new \fanyi\lib\Redis($redis_config);
    $redis_key = $key .':'. $lang;
    foreach ($array as $key => $val){
        $arr = is_array($val) ? json_encode($val) : $val;
        $redis->hSet($redis_key, $key, $arr);
    }
    return true;
}
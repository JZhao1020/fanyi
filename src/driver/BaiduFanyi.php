<?php
namespace fanyi\driver;

/**
 * 百度翻译类
 */
class BaiduFanyi
{

    protected $options = [
        //接口地址
        'url'=>'http://api.fanyi.baidu.com/api/trans/vip/translate',
        //appid
        'appid'=>'',
        //秘钥
        'serectid'=>'',
        //类型0为标准版 QPS=1,  1为高级版 QPS=10,  2为尊享版 QPS=100
        'type'=>0,
    ];
    //上次请求时间
    protected $requestData = [
        'requestTime'=>0,
        'requestNum'=>0,
    ] ;

    /**
     * 构造函数
     * @access public
     */
    public function __construct($con = [])
    {
        if(!empty($con))
            $this->options = array_merge($this->options,$con);
    }

    /**
     * 百度翻译语种列表 详情请参考 http://api.fanyi.baidu.com/product/113
     * +---------------------------------------------------------+
    |    名称  |   代码  |   名称  |   代码 |    名称  |   代码  |
    自动检测	    auto	中文	        zh	    英语	        en
    粤语	        yue	    文言文	    wyw	    日语	        jp
    韩语	        kor	    法语	        fra	    西班牙语	    spa
    泰语          th	    阿拉伯语	    ara	    俄语	        ru
    葡萄牙语	    pt	    德语	        de	    意大利语	    it
    希腊语	    el	    荷兰语	    nl	    波兰语	    pl
    保加利亚语	bul	    爱沙尼亚语	est	    丹麦语	    dan
    芬兰语	    fin	    捷克语	    cs	    罗马尼亚语	rom
    斯洛文尼亚语	slo	    瑞典语	    swe	    匈牙利语	    hu
    繁体中文	    cht	    越南语	    vie
     */

    public function send($text = '', $lang = 'cn')
    {
        $langTypeList = [
            'cn' => 'zh',
            'en' => 'en',
            'yue' => 'yue',
            'wyw' => 'wyw',
            'ja' => 'jp',
            'ko' => 'kor',
            'fr' => 'fra',
            'es' => 'spa',
            'th' => 'th',
            'ar' => 'ara',
            'ru' => 'ru',
            'pt' => 'pt',
            'de' => 'de',
            'it' => 'it',
            'el' => 'el',
            'nl' => 'nl',
            'pl' => 'pl',
            'bg' => 'bul',
            'et' => 'est',
            'da' => 'dan',
            'fi' => 'fin',
            'cs' => 'cs',
            'ro' => 'rom',
            'sl' => 'slo',
            'sv' => 'swe',
            'hu' => 'hu',
            'hk' => 'cht',
            'vi' => 'vie',
        ];

        //类型不在这里面,直接返回对应的数据,没有办法百度不开放接口让你去翻译
        if(!isset($langTypeList[$lang]) || empty($text))
            return false;

        $param = [
            'q' => $text,
            'from' => 'auto',
            'to' => $langTypeList[$lang],
            'appid' => $this->options['appid'],
            'salt' => time(),
        ];
        $param['sign'] = $this->setSign($param);
        if(empty($param['sign']))
            return false;

        //同一秒内的数据
        if($this->requestData['requestTime'] == time())
        {
            if(
                ($this->options['type'] == 1 && $this->requestData['requestNum'] >= 10) ||
                ($this->options['type'] == 2 && $this->requestData['requestNum'] >= 100) ||
                (!in_array($this->options['type'],[1,2]) && $this->requestData['requestNum'] >= 1)
            )
                //休眠1秒调整对应类型的QPS
                sleep(1);
        }
        $result = $this->curlFun($param,$this->options['url']);
        try {
            if(!empty($result['trans_result'][0]['dst']))
            {
                $time = time();
                if($this->requestData['requestTime'] == $time)
                {
                    $this->requestData['requestNum'] += 1;
                }else{
                    $this->requestData['requestTime'] = $time;
                    $this->requestData['requestNum'] = 1;
                }
                return $result['trans_result'][0]['dst'];
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 请求方法
     * @param  $rdata array  post请求的参数
     * @param $url string 请求地址
     * @return  array or string 返回的结果
     * @auther Peng  Create at 2020/9/3
     */
    protected function curlFun($rdata , $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rdata);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result,true);
    }

    /**
     * 加密
     * @param   $param array 需要加密的请求参数
     * @return  string  加密后的数据
     * @auther Peng  Create at 2020/9/3
     */
    protected function setSign($param = [])
    {
        if(empty($param))
            return '';
        if( empty($this->options['appid']) ||
            empty($this->options['serectid']) ||
            (!isset($param['q']) && empty($param['q'])) ||
            (!isset($param['salt']) && empty($param['salt'])))
            return '';
        $str = $this->options['appid'] . $param['q'] . $param['salt'] . $this->options['serectid'];
        return  md5($str);
    }

//类后括号
}


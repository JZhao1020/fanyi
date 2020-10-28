<?php
namespace fanyi\driver;

/**
 * 百度翻译类
 */
class YoudaoFanyi
{

    protected $options = [
        //接口地址
        'url'=>'https://openapi.youdao.com/api',
        //appid
        'appid'=>'',
        //秘钥
        'serectid'=>'',
    ];

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
     * 有道翻译语种列表 详情请参考 http://ai.youdao.com/DOCSIRMA/html/%E8%87%AA%E7%84%B6%E8%AF%AD%E8%A8%80%E7%BF%BB%E8%AF%91/API%E6%96%87%E6%A1%A3/%E6%96%87%E6%9C%AC%E7%BF%BB%E8%AF%91%E6%9C%8D%E5%8A%A1/%E6%96%87%E6%9C%AC%E7%BF%BB%E8%AF%91%E6%9C%8D%E5%8A%A1-API%E6%96%87%E6%A1%A3.html#section-9
     * 中文	zh-CHS
    英文	en
    日文	ja
    韩文	ko
    法文	fr
    西班牙文	es
    葡萄牙文	pt
    意大利文	it
    俄文	ru
    越南文	vi
    德文	de
    阿拉伯文	ar
    印尼文	id
    南非荷兰语	af
    波斯尼亚语	bs
    保加利亚语	bg
    粤语	yue
    加泰隆语	ca
    克罗地亚语	hr
    捷克语	cs
    丹麦语	da
    荷兰语	nl
    爱沙尼亚语	et
    斐济语	fj
    芬兰语	fi
    希腊语	el
    海地克里奥尔语	ht
    希伯来语	he
    印地语	hi
    白苗语	mww
    匈牙利语	hu
    斯瓦希里语	sw
    克林贡语	tlh
    拉脱维亚语	lv
    立陶宛语	lt
    马来语	ms
    马耳他语	mt
    挪威语	no
    波斯语	fa
    波兰语	pl
    克雷塔罗奥托米语	otq
    罗马尼亚语	ro
    塞尔维亚语(西里尔文)	sr-Cyrl
    塞尔维亚语(拉丁文)	sr-Latn
    斯洛伐克语	sk
    斯洛文尼亚语	sl
    瑞典语	sv
    塔希提语	ty
    泰语	th
    汤加语	to
    土耳其语	tr
    乌克兰语	uk
    乌尔都语	ur
    威尔士语	cy
    尤卡坦玛雅语	yua
    阿尔巴尼亚语	sq
    阿姆哈拉语	am
    亚美尼亚语	hy
    阿塞拜疆语	az
    孟加拉语	bn
    巴斯克语	eu
    白俄罗斯语	be
    宿务语	ceb
    科西嘉语	co
    世界语	eo
    菲律宾语	tl
    弗里西语	fy
    加利西亚语	gl
    格鲁吉亚语	ka
    古吉拉特语	gu
    豪萨语	ha
    夏威夷语	haw
    冰岛语	is
    伊博语	ig
    爱尔兰语	ga
    爪哇语	jw
    卡纳达语	kn
    哈萨克语	kk
    高棉语	km
    库尔德语	ku
    柯尔克孜语	ky
    老挝语	lo
    拉丁语	la
    卢森堡语	lb
    马其顿语	mk
    马尔加什语	mg
    马拉雅拉姆语	ml
    毛利语	mi
    马拉地语	mr
    蒙古语	mn
    缅甸语	my
    尼泊尔语	ne
    齐切瓦语	ny
    普什图语	ps
    旁遮普语	pa
    萨摩亚语	sm
    苏格兰盖尔语	gd
    塞索托语	st
    修纳语	sn
    信德语	sd
    僧伽罗语	si
    索马里语	so
    巽他语	su
    塔吉克语	tg
    泰米尔语	ta
    泰卢固语	te
    乌兹别克语	uz
    南非科萨语	xh
    意第绪语	yi
    约鲁巴语	yo
    南非祖鲁语	zu
    自动识别	auto
     */

    public function send($text = '', $lang = 'cn')
    {
        $langTypeList = [
            'cn' => 'zh-CHS',
            'en' => 'en',
            'ja' => 'ja',
            'ko' => 'ko',
            'fr' => 'fr',
            'es' => 'es',
            'pt' => 'pt',
            'it' => 'it',
            'ru' => 'ru',
            'vi' => 'vi',
            'de' => 'de',
            'ar' => 'ar',
            'id' => 'id',
            'af' => 'af',
            'bs' => 'bs',
            'bg' => 'bg',
            'yue' => 'yue',
            'ca' => 'ca',
            'hr' => 'hr',
            'cs' => 'cs',
            'da' => 'da',
            'nl' => 'nl',
            'et' => 'et',
            'fj' => 'fj',
            'fi' => 'fi',
            'el' => 'el',
            'ht' => 'ht',
            'he' => 'he',
            'hi' => 'hi',
            'mww' => 'mww',
            'hu' => 'hu',
            'sw' => 'sw',
            'tlh' => 'tlh',
            'lv' => 'lv',
            'lt' => 'lt',
            'ms' => 'ms',
            'mt' => 'mt',
            'no' => 'no',
            'fa' => 'fa',
            'pl' => 'pl',
            'otq' => 'otq',
            'ro' => 'ro',
            'sr-Cyrl' => 'sr-Cyrl',
            'sr-Latn' => 'sr-Latn',
            'sk' => 'sk',
            'sl' => 'sl',
            'sv' => 'sv',
            'ty' => 'ty',
            'th' => 'th',
            'to' => 'to',
            'tr' => 'tr',
            'uk' => 'uk',
            'ur' => 'ur',
            'cy' => 'cy',
            'yua' => 'yua',
            'sq' => 'sq',
            'am' => 'am',
            'hy' => 'hy',
            'az' => 'az',
            'bn' => 'bn',
            'eu' => 'eu',
            'be' => 'be',
            'ceb' => 'ceb',
            'co' => 'co',
            'eo' => 'eo',
            'tl' => 'tl',
            'fy' => 'fy',
            'gl' => 'gl',
            'ka' => 'ka',
            'gu' => 'gu',
            'ha' => 'ha',
            'haw' => 'haw',
            'is' => 'is',
            'ig' => 'ig',
            'ga' => 'ga',
            'jw' => 'jw',
            'kn' => 'kn',
            'kk' => 'kk',
            'km' => 'km',
            'ku' => 'ku',
            'ky' => 'ky',
            'lo' => 'lo',
            'la' => 'la',
            'lb' => 'lb',
            'mk' => 'mk',
            'mg' => 'mg',
            'ml' => 'ml',
            'mi' => 'mi',
            'mr' => 'mr',
            'mn' => 'mn',
            'my' => 'my',
            'ne' => 'ne',
            'ny' => 'ny',
            'ps' => 'ps',
            'pa' => 'pa',
            'sm' => 'sm',
            'gd' => 'gd',
            'st' => 'st',
            'sn' => 'sn',
            'sd' => 'sd',
            'si' => 'si',
            'so' => 'so',
            'su' => 'su',
            'tg' => 'tg',
            'ta' => 'ta',
            'te' => 'te',
            'uz' => 'uz',
            'xh' => 'xh',
            'yi' => 'yi',
            'yo' => 'yo',
            'zu' => 'zu'
        ];

        //类型不在这里面,直接返回对应的数据,没有办法百度不开放接口让你去翻译
        if(!isset($langTypeList[$lang]) || empty($text))
            return false;

        $salt = $this->create_guid();
        $curtime = time();
        $sign = hash('sha256',$this->options['appid'] . $this->truncate($text) . $salt . $curtime . $this->options['serectid']);
        $param = [
            'q' => $text,
            'appKey' => $this->options['appid'],
            'salt' => $salt,
            'from' => 'auto',
            'to' => $langTypeList[$lang],
            'signType' => 'v3',
            'curtime' => $curtime,
            'sign' => $sign,
        ];
        $ret = $this->call($this->options['url'], $param);
        return $ret;
    }

    private function call($url, $args=null, $method="post", $testflag = 0, $timeout = 2000, $headers=array())
    {
        $ret = false;
        $i = 0;
        while($ret === false)
        {
            if($i > 1)
                break;
            if($i > 0)
            {
                sleep(1);
            }
            $ret = $this->callOnce($url, $args, $method, false, $timeout, $headers);
            $i++;
        }

        $ret = json_decode($ret,true);
        $result = '';
        if(!empty($ret))
        {
            if(!isset($ret['errorCode']) || $ret['errorCode'] != 0)
            {
                $error = isset($ret['errorCode']) ? $ret['errorCode'] : " i don't know what error ,please check it ";
                throw new \think\Exception('youdao call error code : '.$error."\n");
            }

            if(is_array($ret['translation']))
                return $ret['translation'][0];
            if(is_string($ret['translation']))
                return $ret['translation'];
        }
        return $result;
    }

    private function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = 2000, $headers=array())
    {
        $ch = curl_init();
        if($method == "post")
        {
            $data = $this->convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        else
        {
            $data = $this->convert($args);
            if($data)
            {
                if(stripos($url, "?") > 0)
                {
                    $url .= "&$data";
                }
                else
                {
                    $url .= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!empty($headers))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if($withCookie)
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    private function convert(&$args)
    {
        $data = '';
        if (is_array($args))
        {
            foreach ($args as $key=>$val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k=>$v)
                    {
                        $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                    }
                }
                else
                {
                    $data .="$key=".rawurlencode($val)."&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }

    // uuid generator
    private function create_guid(){
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec* 1000000);
        $sec_hex = dechex($a_sec);
        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);
        return $guid;
    }

    private function ensure_length(&$string, $length){
        $strlen = strlen($string);
        if($strlen < $length)
        {
            $string = str_pad($string, $length, "0");
        }
        else if($strlen > $length)
        {
            $string = substr($string, 0, $length);
        }
    }

    private function create_guid_section($characters){
        $return = "";
        for($i = 0; $i < $characters; $i++)
        {
            $return .= dechex(mt_rand(0,15));
        }
        return $return;
    }

    private function truncate($q) {
        $len = $this->abslength($q);
        return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
    }

    private function abslength($str)
    {
        if(empty($str)){
            return 0;
        }
        if(function_exists('mb_strlen')){
            return mb_strlen($str,'utf-8');
        }
        else {
            preg_match_all("/./u", $str, $ar);
            return count($ar[0]);
        }
    }

//类后括号
}


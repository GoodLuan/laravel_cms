<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 * 访问内部API的类库
 */
namespace Org\Net;

class Api 
{
    private static $reg_shutdown = array();

    public static function call($app_name,$url_path,$params=array(),$isDecode=true,$cacheExpire=0,$cacheKey='',$cacheNs='',$timeout=60) {
        $apiUrls = C('API_URLS');
        if(empty($apiUrls[$app_name])) {
            E('配置API_URLS['.$app_name.']不存在');
        }
        $api_url =  $apiUrls[$app_name];
        $serverUrl = rtrim($api_url,'/') . '/' . ltrim($url_path,'/');
        $apiSecret  = C('API_SECRET');
        if(empty($apiSecret)) {
            E('配置API_SECRET不能为空');
        }
        empty($params) && $params=array();
        $params['_sign'] = self::getSign($params, $apiSecret);
        $params['_from_url'] = $_SERVER['REQUEST_URI'];
        $rs = self::curlPost($serverUrl, $params,$cacheExpire,$cacheKey,$cacheNs, $timeout);
        if(!is_numeric($rs) && empty($rs)) {
            return $isDecode===true ? array('msg'=>'网络延迟，请重试','status'=>0,'data'=>'') : '网络延迟，请重试';
        }
        else {
            if (is_string($rs) && 1 === preg_match('/^SQLSTATE\[\d+\]:[^:]+/', $rs, $match)) {
                return $isDecode ? ['msg' => $match[0], 'status' => 0, 'data' => ''] : $match[0];
            }
            return $isDecode===true && !is_array($rs) ? json_decode($rs, true) : $rs;
        }
    }
    /**
     * [getSign description]
     * @param  [array] $params    [description]
     * @param  [string] $apiSecret [description]
     * @return [string]            [description]
     */
    public static function getSign($params=array(), $apiSecret='') {
        unset($params['_sign']);
        ksort($params);
        return sha1(http_build_query($params) . $apiSecret);
    }

    /**
     * curl POST请求
     * @param string $url
     * @param array  $arr
     * @param integer $cacheExpire
     * @param string $cacheKey
     * @param string $cacheNs
     * @param integer $timeout
     * @return string
     */
    public static function curlPost($url, $arr=array(),$cacheExpire=0,$cacheKey='',$cacheNs='', $timeout = 60) {
        $result = '';
        if($cacheExpire>0) {
            $cacheKeyPostfix = '_'.( $cacheKey? $cacheKey : md5(serialize(func_get_args())) );
            $cacheKey = self::getCacheKey('api_', $cacheKeyPostfix,$cacheNs);
            $result = S($cacheKey);
        }
        if(empty($result)) {
            $useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/13.0.1";
            $fields_string = http_build_query($arr);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($arr));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_REFERER, 'http://' . $_SERVER['HTTP_HOST'] . '');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            $result = curl_exec($ch);
            if($result && $cacheExpire>0) {
                S($cacheKey,$result,$cacheExpire);
            }
        }
        return $result;
    }

    public static function getCacheNs($cacheNs='',$expire = 0) { //获取缓存键时间戳 zqf 2016-09-14
        $ns = S('API_CACHE_NS'.$cacheNs);
        if (!$ns) {
            $ns = microtime(true);
            self::setCacheNs($ns,$cacheNs,$expire);
        }
        return $ns;
    }

    public static function setCacheNs($ns=null,$cacheNs='',$expire = 0) { //设置缓存键时间戳  zqf 2016-09-14
        if (!$ns) $ns = microtime(true);
        return S('API_CACHE_NS'.$cacheNs, $ns, $expire>0?$expire:null);
    }

    /**
     * 获取缓存键 peak.cha 2017-07-12
     */
    public static function getCacheKey($prefix='',$postfix='',$cacheNs='',$expire = 0) {
        $ns = self::getCacheNs($cacheNs,$expire);
        /*$nsKey = 'API_CACHE_NS'.$cacheNs;
        $hnsKey = 'wst3_HASH_API_CACHE_NS';
        $hns = getRedis('nosql')->hget($hnsKey, $nsKey);
        if($hns!=$ns){ //有改动
            if(!in_array($nsKey, self::$reg_shutdown)) {
                register_shutdown_function(function($hnsKey, $nsKey,$ns) {
                    getRedis('nosql')->hset($hnsKey, $nsKey,$ns);
                },$hnsKey, $nsKey,$ns);
                array_push(self::$reg_shutdown, $nsKey);
            }
        }*/
        $cacheKey = $prefix.$ns.$postfix;
        /*$zsetKey = 'wst3_ZSET_KEYS_OF_'.$nsKey;
        $zr = getRedis('nosql')->zscore($zsetKey, $cacheKey); //$cacheKey是否存在
        if(empty($zr)) getRedis('nosql')->zadd($zsetKey, $ns, $cacheKey);*/
        return $cacheKey;
    }

}

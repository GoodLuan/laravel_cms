<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 */

use Illuminate\Support\Facades\Response;
use \Illuminate\Support\Facades\Session;

function reSuccess($message = '', $data = array(), $status = 1)
{
    $data = array('status' => $status, 'msg' => $message, 'data' => $data);
    return Response::json($data);
}

function reError($message = '', $data = array(), $status = 0, $code = 0)
{
    $data = array('status' => $status, 'code' => $code, 'msg' => $message, 'data' => $data);
    return Response::json($data);
}
function toastError($message = '', $data = array(), $status = 0, $code = 0)
{
    $data = array('status' => $status, 'code' => $code, 'msg' => $message, 'data' => $data);
    exit(json_encode($data));
}

/**
 * 获取登陆用户uid Jungle 2018-01-19
 */
if (!function_exists('getLoginUid')) {
    function getLoginUid()
    {
        return Session::get('USER_AUTH_KEY');
    }
}

/**
 * 获取登陆用户信息 Jungle 2018-01-19
 */
if (!function_exists('getLoginInfo')) {
    function getLoginInfo()
    {
        return Session::get('USER_AUTH_INFO');
    }
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}


/**
 * 对树数组每个元素进行回调处理，支持递归
 * @param $lists
 * @param $callBack
 * @param int $repeat
 * @param null $otherParam
 * @param null $otherParam2
 */
function treeCallBack($lists, $callBack, $repeat = 0, &$otherParam = null, &$otherParam2 = null)
{
    if (!empty($lists) && $callBack !== null) {
        foreach ($lists as $k => $v) {
            $callBack($v, $repeat, $otherParam, $otherParam2);
            if (!empty($v['_child'])) {
                treeCallBack($v['_child'], $callBack, $repeat + 1, $otherParam, $otherParam2);
            }
        }

    }
}


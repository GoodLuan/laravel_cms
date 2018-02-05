<?php

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

//对树数组每个元素进行回调处理，支持递归 Peak 2016-05-12
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


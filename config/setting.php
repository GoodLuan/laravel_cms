<?php
/**
 * Created by PhpStorm.
 * User: jungle
 * Date: 2018/1/4
 * Time: 9:30
 */
return [
    //标题栏
    'APP_TITLE' => 'JUNGLE',
    //不需要验证的路由
    'NOT_AUTH_URL' => [
        'home/login',
        'home/logout',
        'home/welcome',
    ],
    //默认赋予的路由
    'DEFAULT_AUTH_URL' => [
        'home/index',
        'home/editpwd',
    ],

    'VAR_JSONP_HANDLER' => 'callback',
    // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_AJAX_RETURN' => 'JSON',
];

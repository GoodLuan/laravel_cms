<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 */

namespace App\Http\Controllers;

use App\Models\Admin\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct(){
        //获取路由
        $urlInfo = parse_url($_SERVER['REQUEST_URI']);
        $url = strtolower(trim($urlInfo['path'], '/'));

        if (!in_array($url, config('setting')['NOT_AUTH_URL'])) {
            if (!getLoginUid()) {
                if (empty($_POST) || (!empty($_POST) && empty($_POST['dosubmit']))) {
                    exit('<script charset="utf-8">location.href="/";</script>');
                } else {
                    toastError('请重新登陆！', 'need login');
                }
            } else {
                //实时验证账号是否禁用
                $auth = Auth::Detail(['id' => getLoginUid()]);
                if ($auth['is_open']) {
                    if (empty($_POST)) {
                        exit('<script charset="utf-8">msgShow("账号已被禁用，请联系管理员！");</script>');
                    } else {
                        toastError('账号已被禁用，请联系管理员！');
                    }
                }
                $USER_AUTH_MENU = Session::get('USER_AUTH_MENU');
                //验证权限
                if (empty($USER_AUTH_MENU) || !in_array($url, $USER_AUTH_MENU)) {
                    if (!empty($_POST['return_data_type'])&& $_POST['return_data_type'] == 'html') {
                        exit('没有操作权限！');
                    } else if (empty($_POST)) {
                        exit('<script charset="utf-8">msgShow("没有操作权限！");</script>');
                    } else {
                        toastError('没有操作权限！');
                    }
                }
            }
        }
    }

}






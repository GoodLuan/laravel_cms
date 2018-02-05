<?php

namespace App\Http\Controllers\Home;

use App\Models\Admin\Auth;
use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use App\Org\Util\Arrays;
use Germey\Geetest\GeetestLib;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validate;
use Validator;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //验证登录规则
    private $field_login = array(
        'username'           => array('name'=>'用户名',   '_validate'=>'require|maxLength:255'),
        'password'           => array('name'=>'密码',     '_validate'=>'require|minLength:6'),
//        'geetest_challenge'  => array('name'=>'验证',     '_validate'=>'require'),
//        'geetest_validate'   => array('name'=>'验证',     '_validate'=>'require'),
//        'geetest_seccode'    => array('name'=>'验证',     '_validate'=>'require'),
    );

    //验证登录规则
    private $field_edit = array(
        'id'               => array('name'=>'用户ID',     '_validate'=>'require|maxLength:11'),
        'pwd_old'          => array('name'=>'旧密码',     '_validate'=>'require|minLength:6'),
        'password'         => array('name'=>'新密码',     '_validate'=>'require|minLength:6'),
        'password1'        => array('name'=>'确认密码',    '_validate'=>'require|minLength:6'),
    );

    public function anyWelcome() {



        //select 指定要操作的数据库
        Redis::select(2);


        //hash存取
        $redis = Redis::hset('hash1', 'key3', 'v3');
        dump($redis);exit;

        //返回整个hash表元素
        $redis = Redis::hgetall('hash1');
        dump($redis);exit;


        //-------------------------------------//

        //将数据存入队列池
        $redis1 = Redis::lpush('jungle',1);//插入到队列的头部
        $redis2 = Redis::rpush('jungle',2);//有序列表操作,从队列后插入元素
        $redis3 = Redis::rpushx('jungle',3);//同上，rpushx只对已存在的队列做添加,否则返回 0
        dump($redis1);exit;


        //两种遍历数据的方法
        //1.一次性遍历数据
        $resule = Redis::lrange ('jungle',0,-1);
        foreach ($resule as $v){
            $v = json_decode($v,true);
            dump($v);

        }
        exit;

        //2.循环遍历数据
        while($resule = Redis::lpop ('jungle')){
            dump($resule);return;
        }
    }



    public function anyIndex(){

        $data['userInfo'] = Session::get('USER_AUTH_INFO');

        $data['accesss'] = array();
        if (getLoginUid()==1){
            $data['accesss'] = Menu::getList(['level'=>3]);
        }else if (!empty($data['userInfo']['menu_id'])){
            $data['accesss'] = Menu::getList(['level'=>3,'ids' => explode(',',$data['userInfo']['menu_id'])]);
        }
        $data['accesss'] = Arrays::listToTree($data['accesss']);
        return view('home.index.index',$data);
    }
    /**
     * 登录
     */
    public function postLogin(){

        //验证
        $r = Validate::validParams($_POST, $this->field_login);
        if ($r !== true) return reError($r);

        //服务端二次验证码
//        $r2 = $this->geetest($_POST);
//        if (!$r2) reError(config('geetest.server_fail_alert'));

        //查询帐号是否存
        $member = Auth::Detail(['username' => $_POST['username']]);
        if (empty($member)) return reError('帐号不存在');

        //查询用户名密码是否正确
        $_POST['password'] = md5($_POST['password']);
        if ($_POST['password'] != $member['password']) return reError('用户名或密码错误');

        //获取角色信息
        $role = Role::Detail(['id'=>$member['role_id']]);
        //合并用户与角色权限ID
        $member['menu_id'] = implode(',',array_unique(array_filter(array_merge(explode(',',$role['menu_id']),explode(',',$member['menu_id'])))));
        //获取权限信息
        $params = ($member['id']==1)?[]:explode(',',$member['menu_id']);
        $menu = Menu::getList(['ids'=>$params]);
        //添加默认路由(免权限验证)
        $menu = array_merge(array_filter(array_column($menu,'url')),config('setting')['DEFAULT_AUTH_URL']);

        //去除密码
        unset($member['password']);
        //验证正确存入SESSION的值
        Session::put('USER_AUTH_KEY', $member['id']);
        Session::put('USER_AUTH_INFO', $member);
        Session::put('USER_AUTH_MENU', $menu);

        return reSuccess('登陆成功');
    }


    /**
     * 退出
     */
    public function anyEditpwd(){

        if (empty($_POST['dosubmit'])){
            $data['uid'] = empty($_POST['id'])?'':$_POST['id'];
            return view('home.index.editpwd',$data);
        }

        //验证
        $r = Validate::validParams($_POST, $this->field_edit);
        if ($r !== true) return reError($r);

        if (getLoginUid() != $_POST['id']) return reError('登录用户异常');
        if ($_POST['pwd_old'] == $_POST['password']) return reError('旧密码与新密码一致');
        if ($_POST['password'] != $_POST['password1']) return reError('新密码与确认密码不一致');

        $where['id'] = $_POST['id'];
        $r = Auth::Detail($where);
        //加密旧密码
        $_POST['pwd_old'] = md5($_POST['pwd_old']);

        if ($r['password'] != $_POST['pwd_old']) return reError('旧密码错误');
        //修改
        $r = Auth::Edit($where,['password'=>md5($_POST['password'])]);

        if($r)return reSuccess('修改成功');
        else return reError('修改失败');
    }


    /**
     * 退出
     */
    public function anyLogout(){
        Session::forget('USER_AUTH_KEY');
        Session::forget('USER_AUTH_INFO');
        return redirect('/');
    }


    /**
     * 验证登录状态
     */
    public function postIslogin(){
        $is_login = 0;
        if (getLoginUid())$is_login = 1;

        return reSuccess('',['isLogin'=>$is_login]);
    }

    /**
     *极验服务端二次验证
     * @param $request
     * @return int
     */
    public function geetest($request)
    {
        $data = array(
            "user_id" => 0, # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
        );
        return (new GeetestLib())->successValidate($request['geetest_challenge'], $request['geetest_validate'], $request['geetest_seccode'], $data);
    }
}

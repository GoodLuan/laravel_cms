<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Auth;
use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use App\Org\Util\Arrays;
use Illuminate\Validation\Validate;
use Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    //验证规则-添加
    private $field_add = array(
        'username'            => array('name'=>'帐号名称',   '_validate'=>'require|isManagerName|maxLength:50'),
        'name'                => array('name'=>'帐号姓名',   '_validate'=>'require|maxLength:50'),
        'phone'               => array('name'=>'手机号',     '_validate'=>'require|isMobilePhone|maxLength:11'),
        'password'            => array('name'=>'密码',      '_validate'=>'require|maxLength:255'),
        'email'               => array('name'=>'邮箱',      '_validate'=>'require|isEmail|maxLength:255'),
        'role_id'             => array('name'=>'角色ID',    '_validate'=>'require|maxLength:2'),
        'is_open'             => array('name'=>'是否启动',   '_validate'=>'isInt|maxLength:2'),
    );

    //验证规则-编辑
    private $field_edit = array(
        'id'                  => array('name'=>'ID',       '_validate'=>'isIntId|maxLength:11'),
        'ids'                 => array('name'=>'ID',       '_validate'=>'isIntIds|maxLength:3000'),
        'name'                => array('name'=>'帐号姓名',   '_validate'=>'maxLength:50'),
        'phone'               => array('name'=>'手机号',    '_validate'=>'isMobilePhone|maxLength:11'),
        'password'            => array('name'=>'密码',      '_validate'=>'maxLength:255'),
        'email'               => array('name'=>'邮箱',      '_validate'=>'isEmail|maxLength:255'),
        'role_id'             => array('name'=>'角色ID',    '_validate'=>'isInt|maxLength:2'),
        'is_open'             => array('name'=>'是否启动',   '_validate'=>'isInt|maxLength:2'),
        'menu_id'             => array('name'=>'菜单权限',   '_validate'=>''),
    );

    /**
     *头部
     */
    public function anyIndex() {

        $where['is_open'] = 0;//选择已开启的
        $where['show_all'] = 1;//查询全部
        $where['field'] = ['id','name'];//需要的字段名
        $data = Role::getList($where);
        return view('admin.auth.index',['role'=>$data]);
    }

    /**
     *列表
     */
    public function anyLists()
    {

        $where['show_all'] = 1;//查询全部
        $auth = Auth::getList($where);
        $data['rows'] = $auth;

        $where['is_open'] = 0;//选择已开启的
        $where['show_all'] = 1;//查询全部
        $where['field'] = ['id','name'];//需要的字段名
        $role = Role::getList($where);

        foreach ($role as $r)$data['role'][$r['id']] = $r;

        return view('admin.auth.lists',$data);
    }

    /**
     *添加
     */
    public function anyAdd()
    {
        if (empty($_POST['dosubmit'])) {
            //列表
            $where['is_open'] = 0;//选择已开启的
            $where['show_all'] = 1;//查询全部
            $where['field'] = ['id','name'];//需要的字段名
            $data['role'] = Role::getList($where);

            return view('admin.auth.add',$data);
        }
        //验证
        $r = Validate::validParams($_POST, $this->field_add);
        if ($r !== true) return reError($r);

        $_POST['password'] = md5($_POST['password']);
        $r = Auth::Add($_POST);

        if (!$r) return reError('添加失败');
        else return reSuccess('添加成功');
    }

    /**
     *添加
     */
    public function anyEdit()
    {
        if (empty($_POST['dosubmit'])) {
            //列表
            $where['is_open'] = 0;//选择已开启的
            $where['show_all'] = 1;//查询全部
            $where['field'] = ['id','name'];//需要的字段名
            $data['role'] = Role::getList($where);
            //详情
            $data['detail'] = Auth::Detail(['id'=>$_POST['id']]);

            return view('admin.auth.edit',$data);
        }

        //验证
        $r = Validate::validParams($_POST, $this->field_edit);
        if ($r !== true) return reError($r);
        if (empty($_POST['id']) && empty($_POST['ids']))return reError('ID不能为空');

        $where = array();
        //id和ids至少有一个
        if (!empty($_POST['id'])){
            $where = ['id'=>$_POST['id']];unset($_POST['id']);
        }elseif (!empty($_POST['ids'])){
            $where = ['ids'=>explode(',',$_POST['ids'])];unset($_POST['ids']);
        }

        //转义菜单数组为ids
        if (!empty($_POST['menu_id'])){
            $_POST['menu_id'] = implode(',',array_keys($_POST['menu_id']));
        }

        //密码不存在就不改变密码
        if (empty($_POST['password']))unset($_POST['password']);

        $r = Auth::Edit($where,$_POST);

        if (!$r) return reError('编辑失败');
        else return reSuccess('编辑成功');
    }

    /**
     *设置权限
     */
    public function anyPermissions(){

            if (empty($_POST['id']))return reSuccess('用户ID不能为空');

            $data['uid'] = $_POST['id'];
            //获取用户详情
            $detail = Auth::Detail(['id'=>$data['uid']]);

            //获取用户组详情
            $role = Role::Detail(['id'=>$detail['role_id']]);

            //用户权限
            $data['user'] = empty($detail['menu_id'])?[]:explode(',',$detail['menu_id']);
            //角色权限
            $data['role'] = empty($role['menu_id'])?[]:explode(',',$role['menu_id']);

            //列表
            $where['flag'] = 0;//选择已开启的
            $data['menu'] = Menu::getList($where);
            $data['menu'] = Arrays::listToTree($data['menu']);

            return view('admin.auth.permissions',$data);

    }

}

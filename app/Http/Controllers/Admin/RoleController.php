<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use App\Org\Util\Arrays;
use Illuminate\Validation\Validate;
use Validator;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    //验证规则-添加
    private $field_add = array(
        'name'            => array('name'=>'角色名称',   '_validate'=>'require|maxLength:255'),
        'remarks'         => array('name'=>'备注',       '_validate'=>'require|maxLength:255'),
    );

    //验证规则-编辑
    private $field_edit = array(
        'id'                  => array('name'=>'ID',        '_validate'=>'require|isIntId|maxLength:11'),
        'name'                => array('name'=>'角色名称',   '_validate'=>'maxLength:255'),
        'remarks'             => array('name'=>'备注',       '_validate'=>'maxLength:255'),
        'is_open'             => array('name'=>'是否启动',   '_validate'=>'isInt|maxLength:2'),
        'menu_id'             => array('name'=>'菜单权限',   '_validate'=>''),
    );

    /**
     *头部
     */
    public function anyIndex() {

        return view('admin.role.index');
    }

    /**
     *列表
     */
    public function anyLists()
    {
        $role = Role::getList();
        $data['rows'] = $role['data'];
        return view('admin.role.lists',$data);
    }

    /**
     *添加
     */
    public function anyAdd()
    {
        if (empty($_POST['dosubmit'])) {
            return view('admin.role.add');
        }
        //验证
        $r = Validate::validParams($_POST, $this->field_add);
        if ($r !== true) return reError($r);

        $r = Role::Add($_POST);

        if (!$r) return reError('添加失败');
        else return reSuccess('添加成功');
    }

    /**
     *添加
     */
    public function anyEdit()
    {
        if (empty($_POST['dosubmit'])) {
            //详情
            $data['detail'] = Role::Detail(['id' => $_POST['id']]);

            return view('admin.role.edit', $data);
        }

        //验证
        $r = Validate::validParams($_POST, $this->field_edit);
        if ($r !== true) return reError($r);

        $where = ['id' => $_POST['id']];
        unset($_POST['id']);

        //转义菜单数组为ids
        if (!empty($_POST['menu_id'])) {
            $_POST['menu_id'] = implode(',', array_keys($_POST['menu_id']));
        }

        $r = Role::Edit($where, $_POST);

        if (!$r) return reError('编辑失败');
        else return reSuccess('编辑成功');
    }

    /**
     *设置权限
     */
    public function anyPermissions(){

            if (empty($_POST['id']))return reSuccess('角色ID不能为空');

            $data['id'] = $_POST['id'];

            //获取用户组详情
            $role = Role::Detail(['id'=>$_POST['id']]);

            //角色权限
            $data['role'] = empty($role['menu_id'])?[]:explode(',',$role['menu_id']);

            //列表
            $where['flag'] = 0;//选择已开启的
            $data['menu'] = Menu::getList($where);
            $data['menu'] = Arrays::listToTree($data['menu']);

            return view('admin.role.permissions',$data);

    }

}

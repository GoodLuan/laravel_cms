<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 */

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Menu;
use App\Org\Util\Arrays;
use Illuminate\Validation\Validate;
use Validator;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{

    //验证规则-添加
    private $field_add = array(
        'pid'                 => array('name'=>'父级ID',    '_validate'=>'default:0|maxLength:255'),
        'name'                => array('name'=>'菜单名称',   '_validate'=>'require|maxLength:255'),
        'url'                 => array('name'=>'菜单地址',   '_validate'=>'maxLength:255'),
        'flag'           => array('name'=>'是否启动',   '_validate'=>'default:0|maxLength:2'),
        'order_by'            => array('name'=>'排序',       '_validate'=>'default:0|maxLength:11'),
    );
    //验证规则-编辑
    private $field_edit = array(
        'id'                  => array('name'=>'ID',       '_validate'=>'maxLength:11'),
        'ids'                  => array('name'=>'ID',       '_validate'=>'maxLength:3000'),
        'pid'                 => array('name'=>'父级ID',    '_validate'=>'maxLength:11'),
        'name'                => array('name'=>'菜单名称',   '_validate'=>'maxLength:255'),
        'url'                 => array('name'=>'菜单地址',   '_validate'=>'maxLength:255'),
        'flag'                => array('name'=>'是否启动',   '_validate'=>'maxLength:2'),
        'order_by'            => array('name'=>'排序',       '_validate'=>'maxLength:11'),
    );

    /**
     *头部
     */
    public function anyIndex() {
        return view('admin.menu.index');
    }

    /**
     *列表
     */
    public function anyLists()
    {
        $data = Menu::getList();
        $data = json_decode(json_encode($data),true);
        $menus = Arrays::listToTree($data);
        return view('admin.menu.lists',['menus'=>$menus]);
    }


    /**
     *添加
     */
    public function anyAdd()
    {
        if (empty($_POST['dosubmit'])) {
            //列表
            $list = Menu::getList(['flag'=>0]);
            $list = json_decode(json_encode($list),true);
            //转多维数组
            $menus = Arrays::listToTree($list);
            $pid = empty($_POST['pid'])?0:$_POST['pid'];
            return view('admin.menu.add',['menus'=>$menus,'pid'=>$pid]);
        }
        //验证
        $r = Validate::validParams($_POST, $this->field_add);
        if ($r !== true) return reError($r);

        $r = Menu::Add($_POST);

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
            $list = Menu::getList(['flag'=>0]);
            $list = json_decode(json_encode($list),true);
            $data['menus'] = Arrays::listToTree($list);
            //详情
            $data['detail'] = Menu::Detail(['id'=>$_POST['id']]);
            return view('admin.menu.edit',$data);
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

        $r = Menu::Edit($where,$_POST);

        if (!$r) return reError('编辑失败');
        else return reSuccess('编辑成功');
    }


}

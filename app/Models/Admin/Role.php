<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 */

namespace App\Models\Admin;



use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $connection = 'admin';
    protected $table = 'role';
    public $timestamps = false;
    public $PAGESIZE = 10;

    /**
     * 查询列表
     */
    protected function getList($params=array()){
        $where = array();
        //查询条件
        !empty($params['id'])      && $where['id'] = $params['id'];
        isset($params['is_open'])  && $where['is_open'] = $params['is_open'];

        //查询字段
        $field = empty($params['field'])?'*':$params['field'];

        //默认分页
        if (empty($params['show_all'])){
            $page_size = empty($params['page_size'])?$this->PAGESIZE:$params['page_size'];
            $data = self::where($where)->select($field)->paginate($page_size);
        }else{
            $data = self::where($where)->select($field)->get();
        }
        return $data;
    }
    /**
     * 添加
     */
    protected function Add($params){
        return self::insertGetId($params);
    }

    /**
     * 修改
     */
    protected function Edit($where, $params){
        return self::where(function ($query)use($where) {

            if (!empty($where['ids'])){
                $query->whereIn('id', $where['ids']);
                unset($where['ids']);
            }

            if (!empty($where))$query->where($where);
        })->update($params);
    }

    /**
     * 查询详情
     */
    protected function Detail($params){
        return self::where($params)->first();
    }

}
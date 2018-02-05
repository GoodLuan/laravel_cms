<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 17:03
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

        $field = empty($params['field'])?'*':$params['field'];
        unset($params['field']);

        //默认分页
        if (empty($params['show_all'])){
            $page_size = empty($params['page_size'])?$this->PAGESIZE:$params['page_size'];
            $data = self::where($params)->select($field)->paginate($page_size);
        }else{
            unset($params['show_all']);
            $data = self::where($params)->select($field)->get();
        }
        return empty($data)?[]:json_decode(json_encode($data),true);
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
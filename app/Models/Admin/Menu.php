<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 */

namespace App\Models\Admin;



use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $connection = 'admin';
    protected $table = 'menu';
    public $timestamps = false;

    /**
     * 查询列表
     */
    protected function getList($params=[]){
        $where = array();
        //查询条件
        !empty($params['id'])      && $where['id'] = $params['id'];
        isset($params['is_open'])  && $where['is_open'] = $params['is_open'];

        //查询字段
        $field = empty($params['field'])?'*':$params['field'];

         $data = self::where(function ($query)use($params) {

             if (isset($params['ids'])) {
                 if (!empty($params['ids']))$query->whereIn('id', $params['ids']);
             }

             if (isset($params['level'])) {
                 if (!empty($params['level'])) $query->where('level', '<', $params['level']);
             }

        })->where($where)
             ->select($field)
             ->get();

         return $data;
    }
    /**
     * 查询详情
     */
    protected function Detail($params = array()){
        return self::where($params)->first();
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

}
<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 17:03
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
         $data = self::where(function ($query)use($params) {

             if (isset($params['ids'])) {
                 if (!empty($params['ids']))$query->whereIn('id', $params['ids']);
                 unset($params['ids']);
             }

             if (isset($params['level'])) {
                 if (!empty($params['level'])) $query->where('level', '<', $params['level']);
                 unset($params['level']);
             }

            if (isset($params))$query->where($params);
        })->get();

         return json_decode(json_encode($data),true);
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
<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 */

namespace App\Models\Admin;



use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $connection = 'admin';
    protected $table = 'auth';
    public    $timestamps = false;
    public    $PAGESIZE = 10;

    /**
     * 查询列表
     */
    protected function getList($params=array()){

        $where = array();
        //查询条件
        !empty($params['role_id']) && $where['role_id'] = $params['role_id'];
        isset($params['is_open'])  && $where['is_open'] = $params['is_open'];

        //排序
        $sort['field'] = 'id';
        $sort['order'] = 'desc';
        if(!empty($params['sort_field']) && !empty($params['sort_order'])){
            $sort['field'] = $params['sort_field'];
            $sort['order'] = $params['sort_order'];
        }

        //查询字段
        $field = empty($params['field']) ? '*' : $params['field'];

        $data = self::orderBy($sort['field'], $sort['order'])->select($field);

        if (!empty($params['username']))
            $data->where('username', 'like', "%{$params['username']}%");
        if (!empty($where))
            $data->where($where);

        //是否分页
        if (empty($params['show_all'])) {
            $page_size = empty($params['page_size']) ? $this->PAGESIZE : $params['page_size'];
            return $data->paginate($page_size);
        } else {
            return $data->get();
        }

    }

    /**
     * 添加
     */
    protected function Add($params){
        return self::insertGetId($params);
    }

    /**
     * 删除
     */
    protected function Del($params=[]){
        if (empty($params)) return false;
        return self::where($params)->delete();
    }

    /**
     * 修改
     */
    protected function Edit($where=[], $params=[]){
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

    /**
     * 查询数量
     */
    protected function Check($params){
        return self::where($params)->count();
    }
}
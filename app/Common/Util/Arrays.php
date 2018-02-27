<?php
/**
 * Created by PhpStorm.
 * User: Jungle
 * Date: 2018/2/23
 * Time: 10:30
 */

namespace App\Org\Util;

class Arrays {
    /**
     +----------------------------------------------------------
     * 把返回的数据集转换成Tree
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    static public function listToTree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0)
    {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     +----------------------------------------------------------
     * 对查询结果集进行排序
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param array $sortby 排序类型
     * asc正向排序 desc逆向排序 nat自然排序
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    static public function listSortBy($list,$field, $sortby='asc') {
       if(is_array($list)){
           $refer = $resultSet = array();
           foreach ($list as $i => $data)
               $refer[$i] = &$data[$field];
           switch ($sortby) {
               case 'asc': // 正向排序
                    asort($refer);
                    break;
               case 'desc':// 逆向排序
                    arsort($refer);
                    break;
               case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
           }
           foreach ( $refer as $key=> $val)
               $resultSet[] = &$list[$key];
           return $resultSet;
       }
       return false;
    }

    /**
     +----------------------------------------------------------
     * 在数据列表中搜索
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $list 数据列表
     * @param mixed $condition 查询条件
     * 支持 array('name'=>$value) 或者 name=$value
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    static public function listSearch($list,$condition) {
        if(is_string($condition))
            parse_str($condition,$condition);
        // 返回的结果集合
        $resultSet = array();
        foreach ($list as $key=>$data){
            $find   =   false;
            foreach ($condition as $field=>$value){
                if(isset($data[$field])) {
                    if(0 === strpos($value,'/')) {
                        $find   =   preg_match($value,$data[$field]);
                    }elseif($data[$field]==$value){
                        $find = true;
                    }
                }
            }
            if($find)
                $resultSet[$key]     =   &$list[$key];
        }
        return $resultSet;
    }

    static public function listArea($list){
        $area = array();
        $first = array( 
                    'A'=>'A-G','B'=>'A-G','C'=>'A-G','D'=>'A-G','E'=>'A-G','F'=>'A-G','G'=>'A-G',
                    'H'=>'H-K','I'=>'H-K','J'=>'H-K','K'=>'H-K',
                    'L'=>'L-S','M'=>'L-S','N'=>'L-S','O'=>'L-S','P'=>'L-S','Q'=>'L-S','R'=>'L-S','S'=>'L-S',
                    'T'=>'T-Z','O'=>'T-Z','U'=>'T-Z','V'=>'T-Z','X'=>'T-Z','Y'=>'T-Z','Z'=>'T-Z'
                );
        foreach ($list as $v ) {
            if($v['level']==1){
                $area['86'][$first[$v['first_letter']]][] = array('code'=>$v['id'],'address'=>$v['area_name']);
            }else if(!empty($v['level'])){
                $area[$v['pid']][$v['id']]=$v['area_name'];
            }
        }
        return $area;
    }


    /**
     * [toAssocArr 二维数组转关联数组]
     * @param  [type] &$data [description]
     * @param  string $field [description]
     * @param  bool $isList  [description]
     * @return [array]       [description]
     */
    public static function toAssocArr(&$data,$index='id') {
        $ret = array();
        $index = explode(',',$index);
        $isList = end($index)=='group'?true:false;
        if($isList===true) array_pop($index);
        //$field = $index[0];
        //$isList = (!empty($index[1]) && $index[1]=='group') ? true : false;
        $field = array_shift($index);
        foreach($data as $k=>$v) {
            if($isList===true || !empty($index)) {
                $ret[$v[$field]][] = $v;
            }
            else $ret[$v[$field]] = $v;
        }
        if(!empty($index)) {
            $isList===true && array_push($index,'group');
            foreach ($ret as $k=>$items) {
                $ret[$k]= self::toAssocArr($items,implode(',',$index ));
            }
        }
        return $ret;
    }

}
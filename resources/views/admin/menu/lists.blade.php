<div class="btn_bar mb8">
    <div class="float_l">
        <button class="btn btn-info btn-mini" type="button" title="批量启用" onclick="auth_menu.setflag(0);">批量启用</button>
        <button class="btn btn-info btn-mini" type="button" title="批量禁用" onclick="auth_menu.setflag(1);">批量禁用</button>
    </div>
    <div class="pagenation"></div>
    <div class="clear"></div>
</div>

<table class="table datatable table-striped table-hover" cellspacing="0" cellpadding="0">
    <colgroup>
        <col width="2%" />
        <col width="5%" />
        <col width="20%" />
        <col width="8%" />
        <col width="18%" />
        <col width="8%" />
        <col width="8%" />
        <col width="8%" />
        <col width="8%" />
        <col width="10%" />
    </colgroup>
    <thead>
        <tr class="datatable_tr">
            <th>ID</th>
            <th class="align_l" style="padding-left:20px;" data-type="tree">分类名称</th>
            <th>分类Url地址</th>
            <th>品类级别</th> 
            <th>是否启用</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody class="dtd">
        <?php if(!empty($errMsg)){ ?>
            <tr><td colspan="10">{$errMsg}</td></tr>
        <?php } else if(empty($menus)){ ?>
            <tr><td colspan="10">无数据显示</td></tr>
        <?php } else { treeCallBack($menus,function($vo,$repeat) { ?>
            <tr data-id="{{$vo['id']}}" data-pid="{{$vo['pid']}}" data-repeat="{{$repeat}}" data-parent="<?php echo empty($vo['_child'])?0:1;?>">
                <td>{{$vo['id']}}</td>
                <td class="align_l" style="padding-left:<?php echo 20+$repeat*20; ?>px;" data-type="tree">{{$vo['name']}}</td>
                <td>{{$vo['url']}}</td>
                <td>{{$vo['level']}}</td>
                <td><?php echo $vo['flag']==0?'<span class="text-success">启用</span>':'<span class="text-warning">禁用</span>'; ?></td>
                <td>{{$vo['order_by']}}</td>
                <td>
                    <button class="btn btn-info btn-mini" type="button" onclick="auth_menu.edit({{$vo['id']}})">编辑</button>
                    <button class="btn btn-info btn-mini" type="button" onclick="auth_menu.add({{$vo['id']}})">添加子菜单</button>
                </td>
            </tr>
        <?php });  } ?>
  </tbody>
</table>
<div class="btn_bar mb8">
    <div class="float_l">
        <button class="btn btn-info btn-mini" type="button" title="批量启用" onclick="auth_menu.setflag(1);">批量启用</button>&nbsp
        <button class="btn btn-info btn-mini" type="button" title="批量禁用" onclick="auth_menu.setflag(0);">批量禁用</button>
    </div>
    <div class="pagenation"></div>
    <div class="clear"></div>
</div>
<script type="text/javascript" charset="utf-8">
    /*$(function() {
        $('#auth_user_index .pagenation').pagerBar($.parseJSON('{$pagenation|json_encode}'), function(page,pagesize) {
            auth_user.getList({"page":page,"pagesize":pagesize});
        });
    });*/
</script>


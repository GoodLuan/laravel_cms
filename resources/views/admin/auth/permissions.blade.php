<form class="form-horizontal list_table table_datatab_l" role="form" method="post">
    <input type="hidden" name="id" value="{{$uid}}"/>
    <table class="table datatable table-striped table-hover" cellspacing="0" cellpadding="0">
        <thead>
        <tr class="datatable_tr">
            <th class="align_l" width="300" style="padding-left:20px;" data-type="tree">权限名称</th>
            <th class="align_l">菜单Url地址</th>
        </tr>
        </thead>
        <tbody class="dtd">
        <?php if(empty($menu)){ ?>
        <tr>
            <td colspan="10">无数据显示</td>
        </tr>
        <?php } else { treeCallBack($menu,function($vo, $repeat, $role, $user) { ?>
        <tr data-id="{{$vo['id']}}" data-pid="{{$vo['pid']}}" data-repeat="{{$repeat}}"
            data-parent="<?php echo empty($vo['_child']) ? 0 : 1;?>">
            <td class="align_l" style="padding-left:<?php echo 20 + $repeat * 20; ?>px;" data-type="tree">
                <label>
                    <input type="checkbox" name="menu_id[{{$vo['id']}}]" value="{{$vo['pid']}}" disabled
                           <?php if (in_array($vo['id'], $role)) {
                               echo 'checked ';
                           } elseif (in_array($vo['id'], $user)) {
                               echo 'checked class="never_hide"';
                           } else {
                               echo ' class="never_hide"';
                           } ?> data-repeat="{{$repeat}}" data-parent="<?php echo empty($vo['_child']) ? 0 : 1;?>"
                    />
                    {{$vo['name']}}
                </label>
            </td>
            <td class="align_l">{{$vo['url']}}</td>
        </tr>
        <?php },0,$role,$user);  } ?>
        </tbody>
    </table>
</form>
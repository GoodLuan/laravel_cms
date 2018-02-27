<div class="btn_bar mb8">
    <div class="pagenation"></div>
    <div class="clear"></div>
</div>
<table class="table datatable table-striped table-hover" cellspacing="0" cellpadding="0">
    <thead>
    <tr class="datatable_tr">
        <th>角色名称</th>
        <th>备注</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody class="dtd">
    <?php if(!empty($errMsg)){ ?>
    <tr>
        <td colspan="10">{$errMsg}</td>
    </tr>
    <?php } else if(empty($rows)){ ?>
    <tr>
        <td colspan="10">无数据显示</td>
    </tr>
    <?php } else { ?>
    @foreach($rows as $vo)
        <tr>
            <td>{{$vo->name}}</td>
            <td>{{$vo->remarks}}</td>
            <td>
                <button class="btn btn-info btn-mini" type="button" onclick="auth_role.edit({{$vo->id}})">编辑</button>

                <!--<button class="btn btn-info btn-mini" type="button" onclick="auth_role.del({$vo.id})">删除</button>-->

                <button class="btn btn-warning btn-mini" type="button"
                        onclick="auth_role.auth_list({{$vo->id}},'{{$vo->name}}')">授权
                </button>
            </td>
        </tr>
    @endforeach
    <?php } ?>
    </tbody>
</table>
<div class="btn_bar mb8">
    <div class="pagenation"></div>
    <div class="clear"></div>
</div>
<script type="text/javascript" charset="utf-8">
    $(function () {
        $('#auth_role_index .pagenation').pagerBar($.parseJSON('{$pagenation|json_encode}'), function (page, pagesize) {
            auth_role.getList({"page": page, "pagesize": pagesize});
        });
    });
</script>

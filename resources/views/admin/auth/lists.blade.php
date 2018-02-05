<div class="btn_bar mb8">
    <div class="float_l">
        <button class="btn btn-info btn-mini" type="button" title="批量启用" onclick="auth_user.setFlag(0);">批量启用</button>
        <button class="btn btn-info btn-mini" type="button" title="批量禁用" onclick="auth_user.setFlag(1);">批量禁用</button>
    </div>
    <div class="pagenation"></div>
    <div class="clear"></div>
</div>

<table class="table datatable table-striped table-hover" cellspacing="0" cellpadding="0">
    <thead>
        <tr class="datatable_tr">
            <th>管理员名称</th>
            <th>管理员姓名</th>
            <th>管理员email</th> 
            <th>所属组别</th>
            <th>联系电话</th>
            <th sort-field="last_login_time">最后登录时间</th>
            <th sort-field="flag">是否启用</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody class="dtd">
        <?php if(!empty($errMsg)){ ?>
            <tr><td colspan="10">{$errMsg}</td></tr>
        <?php } else if(empty($rows)){ ?>
            <tr><td colspan="10">无数据显示</td></tr>
        <?php } else { ?>
                @foreach($rows as $vo)
                <tr data-id="{{$vo['id']}}" <?php echo $vo['username']==getLoginInfo()['username']?'check-disable="true"':'';?>>
                    <td>{{$vo['username']}}</td>
                    <td>{{$vo['name']}}</td>
                    <td>{{$vo['email']}}</td>
                    <td>{{empty($vo['role_id'])?'':$role[$vo['role_id']]['name']}}</td>
                    <td>{{$vo['phone']}}</td>
                    <td><?php echo !$vo['last_login_time']? '': date("Y-m-d H:i:s",$vo['last_login_time']); ?></td>
                    <td><?php echo $vo['is_open']==0?'<span class="text-success">启用</span>':'<span class="text-warning">禁用</span>'; ?></td>
                    <td>
                        <button class="btn btn-info btn-mini" type="button" onclick="auth_user.edit({{$vo['id']}})">编辑</button>
                        <button class="btn btn-warning btn-mini" type="button" onclick="auth_user.permissions_list({{$vo['id']}},'{{$vo['username']}}')">授权</button>
                    </td>
                </tr>
           @endforeach
        <?php } ?>
  </tbody>
</table>
<div class="btn_bar mb8">
    <div class="float_l">
        <button class="btn btn-info btn-mini" type="button" title="批量启用" onclick="auth_user.setFlag(0);">批量启用</button>&nbsp
        <button class="btn btn-info btn-mini" type="button" title="批量禁用" onclick="auth_user.setFlag(1);">批量禁用</button>
    </div>
    <div class="pagenation"></div>
    <div class="clear"></div>
</div>
<script type="text/javascript" charset="utf-8">
    $(function() {
        $('#auth_user_index .pagenation').pagerBar($.parseJSON('{$pagenation|json_encode}'), function(page,pagesize) {
            auth_user.getList({"page":page,"pagesize":pagesize});
        });
    });
</script>


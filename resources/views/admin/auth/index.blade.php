<div id="auth_user_index">
    <div style="width: 100%;margin:-5px 0 10px 0;padding-right: 50px;text-align: right;"><button class="btn btn-info" type="button" onclick="auth_user.add();">新增</button></div>
    <!-- 搜索条件头 -->
    <div class="search_bar">
        <table width="100%">
            <colgroup>
                <col width="6%"><col width="19%"><col width="6%"><col width="19%"><col width="6%"><col width="19%"><col width="6%"><col width="19%">
            </colgroup>
            <tr>
                <td><span>用户名称：</span></td>
                <td><input type="text" name="username" class="form-control" title="用户名称："></td>
                <td><span>所属组别：</span></td>
                <!-- 搜索的name名必须和数据库中的字段名一致 -->
                <td>
                    <select class="form-control form-focus" name="group_id">
                        <option value="" selected>--请选择--</option>
                        @foreach($role as $r)
                            <option value="{$r.id}">{{$r['name']}}</option>
                            @endforeach
                    </select>
                </td>
                <td><span>账号状态：</span></td>
                <td>
                    <select class="form-control form-focus" name="flag">
                        <option value="" selected>--请选择--</option>
                        <option value="1">启用</option>
                        <option value="0">禁用</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="align_r" style="padding-right: 43px;" colspan="8"><button class="btn btn-primary" type="button" onclick="auth_user.getList();">查询</button></td>
            </tr>
        </table>
    </div>

    <div class="list_table table_datatab_l"></div>


</div>
<script type="text/javascript" charset="utf-8">
    modules('Admin/Auth',function () {
        $('#auth_user_index .search_bar :input').keyup(function() {
            if(event.keyCode==13) auth_user.getList();
        });
        auth_user.getList();
    });
</script>
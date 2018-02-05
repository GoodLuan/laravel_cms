<div id="auth_role_index">
    <div class="search_bar">
        <table cellpadding="5" width="99%">
    		<tr>
    			<td class="align_r"><button class="btn btn-info" type="button" onclick="auth_role.add();">新增</button></td>
    		</tr>
    	</table>
    </div>
	<div class="list_table table_datatab_l"></div>
</div>
<script type="text/javascript" charset="utf-8">
    modules('Admin/Role',function () {
        $('#auth_role_index .search_bar :input').keyup(function() {
            if(event.keyCode==13) auth_role.getList();
        });
        auth_role.getList();
    });
</script>
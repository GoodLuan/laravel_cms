<div id="auth_menu_index">
    <div class="search_bar">
        <table cellpadding="5" width="99%">
        <tr>
          <td class="align_r"><button class="btn btn-info" type="button" onclick="auth_menu.add();">新增</button></td>
        </tr>
      </table>
    </div>
    <div class="list_table table_datatab_l"></div>
</div>
<script type="text/javascript" charset="utf-8">
    modules('Admin/Menu',function () {
        $('#auth_menu_index .search_bar :input').keyup(function() {
            if(event.keyCode==13) auth_menu.getList();
        });
        auth_menu.getList();
    });
</script>
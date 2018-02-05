/* 
* @Author: Alan
* @Date:   2016-05-11 11:45:32
* @Last Modified time: 2016-07-14 14:47:27
*/

var auth_role = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

auth_role.getList = function(params,obj) {
    obj = !!obj ? obj : $('#auth_role_index .list_table');

    var data = $('#auth_role_index .search_bar').getFormData();
    data.page=auth_role.page;
    data.pagesize=auth_role.pagesize;
    data.sort_field=auth_role.sort_field;
    data.sort_order=auth_role.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    auth_role.page=data.page;
    auth_role.pagesize=data.pagesize;
    auth_role.sort_field=data.sort_field;
    auth_role.sort_order=data.sort_order;
    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/role/lists',data,function(res) {
        obj.html(res);
        //obj.listCheckAble();
        /*obj.listSortAble(auth_role,function(field,order) {
            auth_role.getList({sort_field:field,sort_order:order});
        }); */
    });
};

//添加
auth_role.add = function() {
    dialogWin({title:'添加用户组',url:'/role/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                auth_role.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
auth_role.addSubmit = function(win) {
    win.find('form').ajaxSubmit({
        url:'/role/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); auth_role.getList(); }
        }
    });
};


//编辑
auth_role.edit = function(id) {
    dialogWin({title:'编辑用户组编号['+id+']',url:'/role/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                auth_role.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
auth_role.editSubmit = function(win) {
    win.find('form').ajaxSubmit({
        url:'/role/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); auth_role.getList(); }
        }
    });
};

auth_role.del = function(delid){
	confirm_ = confirm('确定要删除吗？');
	if(confirm_){
		 $.post('/role/del',{id:delid,dosubmit:1},function() {auth_role.getList(); });
	}
}
//批量启用禁用
auth_role.setFlag = function(flag) {
    var codes=[];
    $('#auth_role_index .list_table').find('input:checked[name^=codes]').each(function(i) {
        codes[i] = this.value;
    });
    //alert(codes);
    $.postJ('/role/setflag',{flag:(!flag?0:1),ids:codes.join(),dosubmit:1},function() {auth_role.getList(); });
}


/*用户组权限列表*/
auth_role.auth_list = function(id,name) {
    "use strict";
    dialogWin({title:'['+id+']['+name+'] 权限设置',url:'/role/permissions',data:{id:id},icon:'icon-pencil',width:760,closeBtn:false,
        btns:[
            {name:'设置',style:'btn-default',callback:function(win,btn) {
                win.find('.list_table').find(':checkbox').attr('disabled',false);
                btn.hide().next().show();
            }},
            {name:'保存',style:'btn-warning hide', callback:function(win,btn) {
                auth_role.set_auth(win);
            }}
        ]}
        ,function (win) {
            win.find('.list_table').renderTreeGrid(); //渲染树状表格
            win.find('.list_table').find(':checkbox[data-parent=1]').click(function () {
                var repeat = $(this).attr('data-repeat');
                var isChecked = $(this).is(':checked');
                $(this).parent().parent().parent().nextUntil('[data-repeat='+repeat+']').find(':checkbox').each(function () {
                    if($(this).attr('data-repeat')>=repeat) $(this).prop('checked',isChecked);
                });
            });
    });
};

//保持权限
auth_role.set_auth = function (win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/role/edit', data:{dosubmit:1},
        success: function (r) {
            msgShow(r.msg,r.status);
            if(!!r.status) {  win.modal('hide'); }
        }
    });
}


/*权限列表与设置权限页切换*/
/*auth_role.auth = function(type) {
    if(type==1){
        auth_role.set_select();
        $("#auth_role_getauth_box .authority").css('display','none').next().css('display','block');
    }else{
        $("#auth_role_getauth_box .authority").css('display','block').next().css('display','none');
    }
};*/

/*auth_role.set_select = function() {
    /!*select菜单权限切换获取*!/
    $("#auth_role_getauth_box .accessList").find('select').change(function(){
        var Post={
            access_id : $(this).find("option:selected").val(),
            group_id : $(this).next().val()
        }
        obj = $("#auth_role_getauth_box .accessList #auth_role_authlist");
        //select选中空
        if(Post.access_id == ''){
            obj.html('');
            obj.next().show();
        }else{
            obj.next().hide();
            $.postH('/role/setauth',Post,function(res) {
                obj.html(res);  
                auth_role.listCheckAble();  
            });
        }

    });
}*/


/*绑定设置权限的选中按钮事件*/
/*auth_role.listCheckAble = function() {
    var list_Obj =  $("#auth_role_getauth_box .authority2 ul li");
    var check_All = $('#auth_role_getauth_box .auth_role_all');
    //绑定权限事件
    check_All.find('i').click(function() {
        if($(this).hasClass('icon-check-empty')) {
            $(this).removeClass('icon-check-empty').addClass('icon-checked');
            list_Obj.find('i').removeClass('icon-check-empty').addClass('icon-checked');
            list_Obj.find('i').addClass('active');
        }
        else {
            $(this).removeClass('icon-checked').addClass('icon-check-empty');
            list_Obj.find('i').removeClass('icon-checked').addClass('icon-check-empty');
            list_Obj.find('i').removeClass('active');
        }
    });

    //绑定每个选中框事件
    list_Obj.find('i').click(function() {
        if($(this).hasClass('icon-check-empty')) {
            $(this).removeClass('icon-check-empty').addClass('icon-checked');
            $(this).addClass('active');
        }
        else {
            $(this).removeClass('icon-checked').addClass('icon-check-empty');
            $(this).removeClass('active');
        }
    });
}*/


/*设置权限提交处理*/
/*
auth_role.add_auth = function(gid,access_id,group_auth) {
    var ids='';
    $('#auth_role_getauth_box .accessList .authority2').find('li[data-id]').each(function(i) {
        if($(this).find('i').hasClass('icon-checked')) {
            ids += ids==''? $(this).attr('data-id') : ','+$(this).attr('data-id');      
        }
    });
    $.postJ('/role/setauth',{group_id:gid,access_pid:access_id,access_ids:ids,group_auth:group_auth,dosubmit:1},function(r) { 
        new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
        if(!!r.status) { auth_role.auth_list(gid); }
    });
}*/

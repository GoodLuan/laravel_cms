/* 
* @Author: Peak
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:23:41
*/

var auth_user = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

auth_user.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#auth_user_index .list_table');

    var data = $('#auth_user_index .search_bar').getFormData();
    data.page=auth_user.page;
    data.pagesize=auth_user.pagesize;
    data.sort_field=auth_user.sort_field;
    data.sort_order=auth_user.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    auth_user.page=data.page;
    auth_user.pagesize=data.pagesize;
    auth_user.sort_field=data.sort_field;
    auth_user.sort_order=data.sort_order;
    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/auth/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble();
        obj.listSortAble(auth_user,function(field,order) {
            auth_user.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
auth_user.add = function() {
    "use strict";
    dialogWin({title:'添加用户',url:'/auth/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                auth_user.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
auth_user.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/auth/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); auth_user.getList(); }
        }
    });
};


//编辑
auth_user.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑用户[UID: '+id+']',url:'/auth/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                auth_user.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
auth_user.editSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/auth/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); auth_user.getList(); }
        }
    });
};


//批量启用禁用
auth_user.setFlag = function(flag) {
    var ids='';
    $('#auth_user_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            ids += ids==''? $(this).attr('data-id') : ','+$(this).attr('data-id');        
        }
    });
    $.postJ('/auth/edit',{is_open:(!flag?0:1),ids:ids,dosubmit:1},function(r) {
        if(!r.status){
            msgShow(r.msg);return false;
        }
        auth_user.getList();
    });
}

/*用户权限列表*/
auth_user.permissions_list = function(id,username) {
    "use strict";
    dialogWin({title:'['+id+']['+username+'] 权限设置',url:'/auth/permissions',data:{id:id},icon:'icon-pencil',width:760,closeBtn:false,
        btns:[
            {name:'设置',style:'btn-default',callback:function(win,btn) {
                win.find('.list_table').find('.never_hide').attr('disabled',false);
                btn.hide().next().show();
            }},
            {name:'保存',style:'btn-warning hide', callback:function(win,btn) {
                auth_user.set_permissions(win);
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

//保存权限
auth_user.set_permissions = function (win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/auth/edit', data:{dosubmit:1},
        success: function (r) {
            msgShow(r.msg,r.status);
            if(!!r.status) {  win.modal('hide'); }
        }
    });
}

/*按id获取用户信息*/
auth_user.listbyids = function (ids,fn) {
    if($.isArray(ids)) ids = ids.join(',');
    $.postJ('/auth/listbyids',{ids:ids},function (json) {
        if(!!json.status && $.isFunction(fn)) fn(json.data);
    })
}
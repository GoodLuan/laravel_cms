/* 
* @Author: Alan
* @Date:   2016-05-11 11:45:32
* @Last Modified time: 2016-07-14 14:46:56
*/

var auth_menu = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

auth_menu.getList = function(params,obj) {
    obj = !!obj ? obj : $('#auth_menu_index .list_table');

    var data = $('#auth_menu_index .search_bar').getFormData();
    data.page=auth_menu.page;
    data.pagesize=auth_menu.pagesize;
    data.sort_field=auth_menu.sort_field;
    data.sort_order=auth_menu.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    auth_menu.page=data.page;
    auth_menu.pagesize=data.pagesize;
    auth_menu.sort_field=data.sort_field;
    auth_menu.sort_order=data.sort_order;
    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/menu/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble();
        obj.listSortAble(auth_menu,function(field,order) {
            auth_menu.getList({sort_field:field,sort_order:order});
        });
        obj.renderTreeGrid(); //渲染树状表格
    });
};

//添加
auth_menu.add = function(pid) {
    dialogWin({title:'添加菜单',url:'/menu/add',data:{pid:parseInt(pid)},icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                auth_menu.addSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
        var sel = win.find('select[name=pid]').val();
        if(sel == 0){
           win.find('input[name=url]').attr('disabled',true);
        }
        win.find('select[name=pid]').change(function() {
            var selector = $(this);
            var selVal = selector.val();
            selector.find('option').each(function(i) {
                if($(this).attr('value')==selVal) {
                    if(selVal>0) {
                        win.find('input[name=url]').attr('disabled',false);
                    }
                    else {
                        win.find('input[name=url]').attr('disabled',true);
                    }
                }
            });
        });
    });
};

//添加表单提交
auth_menu.addSubmit = function(win) {
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/menu/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); auth_menu.getList(); }
        }
    });
    });
};


//编辑
auth_menu.edit = function(id) {
    dialogWin({title:'编辑菜单[ID: '+id+']',url:'/menu/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                auth_menu.editSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
        var sel = win.find('select[name=pid]').val();
        if(sel == 0){
            win.find('input[name=url]').attr('disabled',true);
        }
        win.find('select[name=pid]').change(function() {
            var selector = $(this);
            var selVal = selector.val();
            selector.find('option').each(function(i) {
                if($(this).attr('value')==selVal) {
                    if(selVal>0) {
                        win.find('input[name=url]').attr('disabled',false);
                    }
                    else {
                        win.find('input[name=url]').attr('disabled',true);
                    }
                }
            });
        });
    });
};

//编辑表单提交
auth_menu.editSubmit = function(win) {
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
            url:'/menu/edit',
            data:{dosubmit:1},
            success: function (r) {
                new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
                if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); auth_menu.getList(); }
            }
        });
    });
};
//菜单删除
auth_menu.del = function(delid){
	confirm_ = confirm('确定要删除吗？');
	if(confirm_){
		 $.post('/menu/del',{id:delid,dosubmit:1},function() {auth_menu.getList(); });
	}
}
//批量显示隐藏
auth_menu.setflag = function(flag) {
    "use strict";
    var ids='';
    $('#auth_menu_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            ids += ids==''? $(this).attr('data-id') : ','+$(this).attr('data-id');
        }
    });
    if(ids==''){
        msgShow('请选择ID'); return false;
    }

    $.postJ('/menu/edit',{flag:(!flag?0:1),ids:ids,dosubmit:1},function(r) {
        if(!r.status){
            msgShow(r.msg);return false;
        }
        auth_menu.getList();
    });
}
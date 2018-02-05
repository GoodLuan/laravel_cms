/*
* @Author: brandon
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_sensitive = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

dict_sensitive.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_sensitive_index .list_table');
    var data = $('#dict_sensitive_index .search_bar').getFormData();
    data.page=dict_sensitive.page;
    data.pagesize=dict_sensitive.pagesize;
    data.sort_field=dict_sensitive.sort_field;
    data.sort_order=dict_sensitive.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_sensitive.page=data.page;
    dict_sensitive.pagesize=data.pagesize;
    dict_sensitive.sort_field=data.sort_field;
    dict_sensitive.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/sensitive/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_sensitive,function(field,order) { //渲染排序效果
            dict_sensitive.getList({sort_field:field,sort_order:order});
        });        
    });
};

//导出生成词库文件
dict_sensitive.refreshSensitivewords = function () {
    "use strict";
    $.postJ('/dict/sensitive/refreshSensitivewords',{dosubmit:1},function(r) {
        // console.log(r);
        new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();   });
}


//添加
dict_sensitive.add = function() {
    "use strict";
    dialogWin({title:'添加单词',url:'/dict/sensitive/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {

                dict_sensitive.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
dict_sensitive.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/sensitive/add',
        data:{dosubmit:1},
        success: function (r) {
            // console.log(r);
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_sensitive.getList(); }
        }
    });
};


//批量启用禁用
dict_sensitive.del = function() {
    "use strict";
    if(window.confirm('您确定要删除所选单词吗？')) {
        var ids='';
        $('#dict_sensitive_index .list_table').find('tbody>tr[data-id]').each(function(i) {
            if($(this).find('i.checkable').hasClass('icon-checked')) {
                if($(this).attr('data-id')) {
                    ids += ids == '' ? $(this).attr('data-id') : ',' + $(this).attr('data-id');
                }
            }
        });
        $.postJ('/dict/sensitive/del',{id:ids,dosubmit:1},function() {dict_sensitive.getList(); });
    }
}
//编辑
dict_sensitive.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑敏感词['+id+']',url:'/dict/sensitive/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_sensitive.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
dict_sensitive.editSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/sensitive/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_sensitive.getList(); }
        }
    });
};
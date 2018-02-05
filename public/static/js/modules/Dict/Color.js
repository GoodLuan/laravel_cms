/*
* @Author: peak
* @Date:   2016-09-20 11:23:13
*/

var dict_color = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

dict_color.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_color_index .list_table');
    var data = $('#dict_color_index .search_bar').getFormData();
    data.page=dict_color.page;
    data.pagesize=dict_color.pagesize;
    data.sort_field=dict_color.sort_field;
    data.sort_order=dict_color.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_color.page=data.page;
    dict_color.pagesize=data.pagesize;
    dict_color.sort_field=data.sort_field;
    dict_color.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/color/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_color,function(field,order) { //渲染排序效果
            dict_color.getList({sort_field:field,sort_order:order});
        });
        obj.renderTreeGrid(); //渲染树状表格
    });
};

//添加
dict_color.add = function() {
    "use strict";
    dialogWin({title:'添加颜色',url:'/dict/color/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_color.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
dict_color.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/color/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_color.getList(); }
        }
    });
};


//编辑
dict_color.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑颜色['+id+']',url:'/dict/color/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_color.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
dict_color.editSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/color/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_color.getList(); }
        }
    });
};


//批量启用禁用
dict_color.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_color_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-id')) {
                ids += ids == '' ? $(this).attr('data-id') : ',' + $(this).attr('data-id');
            }
        }
    });
    $.postJ('/dict/color/setflag',{flag:(!flag?0:1),ids:ids,dosubmit:1},function() {dict_color.getList(); });
}

//更新缓存，用于ES分词判断
dict_color.refreshCache =  function () {
    "use strict";
    $.postJ('/dict/color/refreshCache',{dosubmit:1},function(r) { new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();   });
}

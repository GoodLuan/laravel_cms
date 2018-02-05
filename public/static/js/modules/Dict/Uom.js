/*
* @Author: brandon
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_uom = { page:1, pagesize:20, sort_field:'code', sort_order:'desc' };

dict_uom.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_uom_index .list_table');
    var data = $('#dict_uom_index .search_bar').getFormData();
    data.page=dict_uom.page;
    data.pagesize=dict_uom.pagesize;
    data.sort_field=dict_uom.sort_field;
    data.sort_order=dict_uom.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_uom.page=data.page;
    dict_uom.pagesize=data.pagesize;
    dict_uom.sort_field=data.sort_field;
    dict_uom.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/uom/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_uom,function(field,order) { //渲染排序效果
            dict_uom.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
dict_uom.add = function() {
    "use strict";
    dialogWin({title:'添加物品单位',url:'/dict/uom/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_uom.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
dict_uom.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/uom/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_uom.getList(); }
        }
    });
};


//编辑
dict_uom.edit = function(code) {
    "use strict";
    dialogWin({title:'编辑物品单位['+code+']',url:'/dict/uom/edit',data:{code:code},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_uom.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
dict_uom.editSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/uom/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_uom.getList(); }
        }
    });
};


//批量启用禁用
dict_uom.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_uom_index .list_table').find('tbody>tr[data-code]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-code')) {
                ids += ids == '' ? $(this).attr('data-code') : ',' + $(this).attr('data-code');
            }
        }
    });
    $.postJ('/dict/uom/setflag',{flag:(!flag?0:1),codes:ids,dosubmit:1},function() {dict_uom.getList(); });
}
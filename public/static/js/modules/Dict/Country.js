/*
* @Author: peak
* @Date:   2016-09-20 11:23:13
*/

var dict_country = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

dict_country.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_country_index .list_table');
    var data = $('#dict_country_index .search_bar').getFormData();
    data.page=dict_country.page;
    data.pagesize=dict_country.pagesize;
    data.sort_field=dict_country.sort_field;
    data.sort_order=dict_country.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_country.page=data.page;
    dict_country.pagesize=data.pagesize;
    dict_country.sort_field=data.sort_field;
    dict_country.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/country/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_country,function(field,order) { //渲染排序效果
            dict_country.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
dict_country.add = function() {
    "use strict";
    dialogWin({title:'添加国家',url:'/dict/country/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_country.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
dict_country.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/country/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_country.getList(); }
        }
    });
};


//编辑
dict_country.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑国家['+id+']',url:'/dict/country/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_country.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
dict_country.editSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/country/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_country.getList(); }
        }
    });
};

//批量启用禁用
dict_country.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_country_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-id')) {
                ids += ids == '' ? $(this).attr('data-id') : ',' + $(this).attr('data-id');
            }
        }
    });
    $.postJ('/dict/country/setflag',{flag:(!flag?0:1),ids:ids,dosubmit:1},function() {dict_country.getList(); });
}
/* 
* @Author: Peak
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_hsgoodsno = { page:1, pagesize:20, sort_field:'code', sort_order:'desc' };

dict_hsgoodsno.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_hsgoodsno_index .list_table');
    var data = $('#dict_hsgoodsno_index .search_bar').getFormData();
    data.page=dict_hsgoodsno.page;
    data.pagesize=dict_hsgoodsno.pagesize;
    data.sort_field=dict_hsgoodsno.sort_field;
    data.sort_order=dict_hsgoodsno.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_hsgoodsno.page=data.page;
    dict_hsgoodsno.pagesize=data.pagesize;
    dict_hsgoodsno.sort_field=data.sort_field;
    dict_hsgoodsno.sort_order=data.sort_order;

    $.postH('/dict/hsgoodsno/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_hsgoodsno,function(field,order) { //渲染排序效果
            dict_hsgoodsno.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
dict_hsgoodsno.add = function() {
    "use strict";
    dialogWin({title:'添加海关备案号',url:'/dict/hsgoodsno/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_hsgoodsno.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
dict_hsgoodsno.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/hsgoodsno/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_hsgoodsno.getList(); }
        }
    });
};


//编辑
dict_hsgoodsno.edit = function(code) {
    "use strict";
    dialogWin({title:'编辑海关备案号['+code+']',url:'/dict/hsgoodsno/edit',data:{code:code},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_hsgoodsno.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
dict_hsgoodsno.editSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/hsgoodsno/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_hsgoodsno.getList(); }
        }
    });
};


//批量启用禁用
dict_hsgoodsno.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_hsgoodsno_index .list_table').find('tbody>tr[data-code]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-code')) {
                ids += ids == '' ? $(this).attr('data-code') : ',' + $(this).attr('data-code');
            }
        }
    });
    $.postJ('/dict/hsgoodsno/setflag',{flag:(!flag?0:1),codes:ids,dosubmit:1},function() {dict_hsgoodsno.getList(); });
}
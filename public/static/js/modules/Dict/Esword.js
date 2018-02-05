/*
* @Author: brandon
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_esword = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

dict_esword.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_esword_index .list_table');
    var data = $('#dict_esword_index .search_bar').getFormData();
    data.page=dict_esword.page;
    data.pagesize=dict_esword.pagesize;
    data.sort_field=dict_esword.sort_field;
    data.sort_order=dict_esword.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_esword.page=data.page;
    dict_esword.pagesize=data.pagesize;
    dict_esword.sort_field=data.sort_field;
    dict_esword.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/esword/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_esword,function(field,order) { //渲染排序效果
            dict_esword.getList({sort_field:field,sort_order:order});
        });        
    });
};

//导出生成词库文件
dict_esword.exportDic = function () {
    "use strict";
    $.postJ('/dict/esword/exportDic',{dosubmit:1},function(r) { new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();   });
}


//添加
dict_esword.add = function() {
    "use strict";
    dialogWin({title:'添加单词',url:'/dict/esword/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_esword.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
dict_esword.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/esword/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_esword.getList(); }
        }
    });
};


//批量启用禁用
dict_esword.del = function() {
    "use strict";
    if(window.confirm('您确定要删除所选单词吗？')) {
        var ids='';
        $('#dict_esword_index .list_table').find('tbody>tr[data-id]').each(function(i) {
            if($(this).find('i.checkable').hasClass('icon-checked')) {
                if($(this).attr('data-id')) {
                    ids += ids == '' ? $(this).attr('data-id') : ',' + $(this).attr('data-id');
                }
            }
        });
        $.postJ('/dict/esword/del',{id:ids,dosubmit:1},function() {dict_esword.getList(); });
    }
}
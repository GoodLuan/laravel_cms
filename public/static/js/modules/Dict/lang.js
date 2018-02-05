/*
* @Author: brandon
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_lang = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

dict_lang.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_lang_index .list_table');
    var data = $('#dict_lang_index .search_bar').getFormData();
    data.page=dict_lang.page;  
    data.pagesize=dict_lang.pagesize;
    data.sort_field=dict_lang.sort_field;
    data.sort_order=dict_lang.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_lang.page=data.page;
    dict_lang.pagesize=data.pagesize;
    dict_lang.sort_field=data.sort_field;
    dict_lang.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/lang/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_lang,function(field,order) { //渲染排序效果
            dict_lang.getList({sort_field:field,sort_order:order});
        });        
    });
};

    //js设置cookie
function get_cookie(name){
    var arr;
    var reg = new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if (arr=document.cookie.match(reg)) {
      return unescape(arr[2]);
    }else{
      return null;
    } 
}
//添加
dict_lang.add = function() {
    "use strict";

    dialogWin({title:'添加词汇',url:'/dict/lang/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_lang.addSubmit(win);
            }}
        ]
    });
};

//添加表单提交
dict_lang.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/lang/add',
        data:{dosubmit:1},  
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_lang.getList(); }
        }
    });
};


//编辑
dict_lang.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑词汇编号['+id+']',url:'/dict/lang/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_lang.editSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//编辑表单提交
dict_lang.editSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/dict/lang/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_lang.getList(); }
        }
    });
    });
};

//批量启用禁用
dict_lang.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_lang_index .list_table').find('tbody>tr[data-code]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-code')) {
                ids += ids == '' ? $(this).attr('data-code') : ',' + $(this).attr('data-code');
            }
        }
    });
    $.postJ('/dict/lang/setflag',{flag:(!flag?0:1),codes:ids,dosubmit:1},function() {dict_lang.getList(); });
}

//批量启用禁用
dict_lang.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_lang_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-id')) {
                ids += ids == '' ? $(this).attr('data-id') : ',' + $(this).attr('data-id');
            }
        }
    });
    $.postJ('/dict/lang/setflag',{flag:(!flag?0:1),ids:ids,dosubmit:1},function() {dict_lang.getList(); });
}







/* 
* @Author: Peak
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-06-06 11:51:53
*/

var dict_hscode = { page:1, pagesize:20, sort_field:'code', sort_order:'desc' };

dict_hscode.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_hscode_index .list_table');
    var data = $('#dict_hscode_index .search_bar').getFormData();
    data.page=dict_hscode.page;
    data.pagesize=dict_hscode.pagesize;
    data.sort_field=dict_hscode.sort_field;
    data.sort_order=dict_hscode.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_hscode.page=data.page;
    dict_hscode.pagesize=data.pagesize;
    dict_hscode.sort_field=data.sort_field;
    dict_hscode.sort_order=data.sort_order;

    $.postH('/dict/hscode/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_hscode,function(field,order) { //渲染排序效果
            dict_hscode.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
dict_hscode.add = function() {
    "use strict";
    dialogWin({title:'添加海关编号',url:'/dict/hscode/add',icon:'icon-plus',width:400,height:420,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_hscode.addSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//添加表单提交
dict_hscode.addSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){
        win.find('form').ajaxSubmit({
            url:'/dict/hscode/add',
            data:{dosubmit:1},
            success: function (r) {
                new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
                if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_hscode.getList(); }
            }
        });
    });
};


//编辑
dict_hscode.edit = function(code) {
    "use strict";
    dialogWin({title:'编辑海关编号['+code+']',url:'/dict/hscode/edit',data:{code:code},icon:'icon-pencil',width:400,height:420,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_hscode.editSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//编辑表单提交
dict_hscode.editSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){
        win.find('form').ajaxSubmit({
            url:'/dict/hscode/edit',
            data:{dosubmit:1},
            success: function (r) {
                new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
                if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_hscode.getList(); }
            }
        });
    });
};


//批量启用禁用
dict_hscode.setFlag = function(flag) {
    "use strict";
    var codes=[];
    /*$('#dict_hscode_index .list_table').find('input:checked[name^=codes]').each(function(i) {
        codes[i] = this.value;
    });*/
    $('#dict_hscode_index .list_table').find('tbody>tr[data-code]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            codes[i] = $(this).attr('data-code');
        }
    });
    $.postJ('/dict/hscode/setflag',{flag:(!flag?0:1),codes:codes.join(),dosubmit:1},function() {dict_hscode.getList(); });
}
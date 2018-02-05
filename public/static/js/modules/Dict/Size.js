/* 
* @Author: Peak
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_size = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

dict_size.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_size_index .list_table');
    var data = $('#dict_size_index .search_bar').getFormData();
    data.page=dict_size.page;
    data.pagesize=dict_size.pagesize;
    data.sort_field=dict_size.sort_field;
    data.sort_order=dict_size.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_size.page=data.page;
    dict_size.pagesize=data.pagesize;
    dict_size.sort_field=data.sort_field;
    dict_size.sort_order=data.sort_order;
    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');

    $.postH('/dict/size/lists',data,function(res) {
        obj.html(res);
        //obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_size,function(field,order) { //渲染排序效果
            dict_size.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
dict_size.add = function() {
    "use strict";
    dialogWin({title:'添加尺寸编号',url:'/dict/size/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_size.addSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//添加表单提交
dict_size.addSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/dict/size/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_size.getList(); }
        }
    });
    });
};


//编辑
dict_size.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑尺寸编号['+id+']',url:'/dict/size/edit',data:{id:id},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_size.editSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//编辑表单提交
dict_size.editSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/dict/size/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_size.getList(); }
        }
    });
    });
};


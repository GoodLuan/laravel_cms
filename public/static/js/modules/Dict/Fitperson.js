/* 
* @Author: Peak
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_fitperson = { page:1, pagesize:20, sort_field:'code', sort_order:'desc' };

dict_fitperson.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_fitperson_index .list_table');
    var data = $('#dict_fitperson_index .search_bar').getFormData();
    data.page=dict_fitperson.page;
    data.pagesize=dict_fitperson.pagesize;
    data.sort_field=dict_fitperson.sort_field;
    data.sort_order=dict_fitperson.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_fitperson.page=data.page;
    dict_fitperson.pagesize=data.pagesize;
    dict_fitperson.sort_field=data.sort_field;
    dict_fitperson.sort_order=data.sort_order;
    
    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/fitperson/lists',data,function(res) {
        obj.html(res);
        //obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_fitperson,function(field,order) { //渲染排序效果
            dict_fitperson.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
dict_fitperson.add = function() {
    "use strict";
    dialogWin({title:'添加适用人群',url:'/dict/fitperson/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_fitperson.addSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//添加表单提交
dict_fitperson.addSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/dict/fitperson/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_fitperson.getList(); }
        }
    });
    });
};


//编辑
dict_fitperson.edit = function(code) {
    "use strict";
    dialogWin({title:'编辑适用人群['+code+']',url:'/dict/fitperson/edit',data:{code:code},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_fitperson.editSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//编辑表单提交
dict_fitperson.editSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/dict/fitperson/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_fitperson.getList(); }
        }
    });
    });
};

//更新缓存，用于ES分词判断
dict_fitperson.refreshCache =  function () {
    "use strict";
    $.postJ('/dict/fitperson/refreshCache',{dosubmit:1},function(r) { new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();   });
}

/* 
* @Author: Peak
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:23:41
*/

var dict_config = { page:1, pagesize:20, sort_field:'key', sort_order:'desc' };

dict_config.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_config_index .list_table');

    var data = $('#dict_config_index .search_bar').getFormData();
    data.page=dict_config.page;
    data.pagesize=dict_config.pagesize;
    data.sort_field=dict_config.sort_field;
    data.sort_order=dict_config.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_config.page=data.page;
    dict_config.pagesize=data.pagesize;
    dict_config.sort_field=data.sort_field;
    dict_config.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/config/lists',data,function(res) {
        obj.html(res);
        obj.listSortAble(dict_config,function(field,order) {
            dict_config.getList({sort_field:field,sort_order:order});
        });
    });
};

//添加
dict_config.add = function() {
    "use strict";
    dialogWin({title:'添加配置',url:'/dict/config/add',icon:'icon-plus',width:400,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_config.addSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//添加表单提交
dict_config.addSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/dict/config/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_config.getList(); }
        }
    });
    });
};


//编辑
dict_config.edit = function(key) {
    "use strict";
    dialogWin({title:'编辑配置编号['+key+']',url:'/dict/config/edit',data:{key:key},icon:'icon-pencil',width:400,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_config.editSubmit(win);
            }}
        ]
    },function(win) {
        win.find('form').initValidate();
    });
};

//编辑表单提交
dict_config.editSubmit = function(win) {
    "use strict";
    win.find('form').validate(function(){win.find('form').ajaxSubmit({
        url:'/dict/config/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_config.getList(); }
        }
    });
    });
};

//刷新配置列表缓存
dict_config.refreshCache = function () {
    if(window.confirm('请谨慎操作，是否继续？')===false) return false;
    $.postJ('/dict/config/clearcache',{dosubmit:1},function (json) {
        msgShow(json.info,json.status);
    });
}

//批量启用禁用
/*dict_config.setFlag = function(flag) {
    var ids='';
    $('#dict_config_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            ids += ids==''? $(this).attr('data-id') : ','+$(this).attr('data-id');        
        }
    });
    $.postJ('/dict/config/flag',{flag:(!flag?0:1),ids:ids},function() {info_user.getList(); });
}*/
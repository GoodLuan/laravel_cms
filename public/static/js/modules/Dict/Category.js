/* 
* @Author: Peak
* @Date:   2016-05-05 11:23:13
* @Last Modified time: 2016-05-12 18:18:34
*/

var dict_category = { page:1, pagesize:20, sort_field:'list_sort,id', sort_order:'asc'};

dict_category.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_category_index .list_table');
    var data = $('#dict_category_index .search_bar').getFormData();
    data.page=dict_category.page;
    data.pagesize=dict_category.pagesize;
    data.sort_field=dict_category.sort_field;
    data.sort_order=dict_category.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_category.page=data.page;
    dict_category.pagesize=data.pagesize;
    dict_category.sort_field=data.sort_field;
    dict_category.sort_order=data.sort_order;
    $.postH('/dict/category/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble();
        obj.listSortAble(dict_category, function (field, order) {
            dict_category.getList({sort_field: field, sort_order: order});
        });
        obj.renderTreeGrid(); //渲染树状表格
    })
};

//排序
dict_category.setorder=function(obj) {
    "use strict";
    var inputObj = obj.next();
    obj.hide();
    inputObj.show();
    function saveOrder(obj) {
        var list_sort=inputObj.prop('value');
        var id= inputObj.attr('data');
        $.postJ('/dict/category/setorder',{'id':id,'list_sort':list_sort,dosubmit:1},function(res) {
            if(res.status) {  inputObj.hide(); obj.text(list_sort).show(); }
        });
    }
    inputObj.keyup(function(event){ if(event.keyCode==13) saveOrder(obj);  });
    inputObj.blur(function(){ saveOrder(obj);});
};

//添加私有属性
dict_category.addprivateatrr=function(win){
    "use strict";
    var str="<div class='form-group allginlft'>"
            +"<label class='col-md-2 control-label align_r'>属性标识(英文):</label>"
            +"<div class='col-md-3'>"
            +"<input name='private_field[]' class='form-control private_text' type='text' validate-rule='require|isLetter' > </div>"
            +"<label class='col-md-2 control-label align_r' style='margin-left: 13px;'>属性名称(中文):</label>"
            +"<div class='col-md-3'>"
            +"<input name='private_name[]' class='form-control private_text' type='text' validate-rule='require|isChinese' > </div>"
            +"<a class='private_button' onclick='dict_category.delprivateatrr(this)'><i class='icon icon-times'></i></a> </div>";
    $('#private_atrr').append(str);
    $(document).find('form').initValidate();
}

dict_category.delprivateatrr=function(obj){
 $(obj).parents('.form-group').remove();
}

//添加
dict_category.add = function() {
    "use strict";
    dialogWin({title:'添加品类',url:'/dict/category/add',icon:'icon-plus',width:900,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win,btn) {
                dict_category.addSubmit(win,btn);
            }}
        ]
    },function(win){
        win.find('form').initValidate();
        win.find('form').find('select[name=pid]').change(function () {
            var pid = $(this).val();
            var level = $(this).find('option[value='+pid+']').attr('level');
            win.find('form').find('input[name=tax]').attr('disabled',level==1?false:true);
        });
    });
};

//添加表单提交
dict_category.addSubmit = function(win,btn) {
    "use strict";
    win.find('form').validate(function(){
        btn.text('正在执行...').addClass('disabled').attr('disabled',true);
        win.find('form').ajaxSubmit({
            url:'/dict/category/add',
            data:{dosubmit:1},
            success: function (r) {
                btn.text('提交').removeClass('disabled').attr('disabled',false);
                new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
                if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_category.getList(); }
            }
        });
    });
};



//编辑
dict_category.edit = function(id) {
    "use strict";
    dialogWin({title:'品类编号['+id+']',url:'/dict/category/edit',data:{id:id},icon:'icon-pencil',width:900,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win,btn) {
                dict_category.editSubmit(win,btn);
            }}
        ]
    },function(win){
        win.find('form').initValidate();
    });
};


//编辑表单提交
dict_category.editSubmit = function(win,btn) {
    "use strict";
    win.find('form').validate(function(){
        btn.text('正在执行...').addClass('disabled').attr('disabled',true);
        win.find('form').ajaxSubmit({
            url:'/dict/category/edit',
            data:{dosubmit:1},
            success: function (r) {
                btn.text('保存').removeClass('disabled').attr('disabled',false);
                new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
                if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_category.getList(); }
            }
        });
    });
};


//批量启用禁用
dict_category.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_category_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            ids += ids==''? $(this).attr('data-id') : ','+$(this).attr('data-id');
        }
    });
    $.postJ('/dict/category/setflag',{flag:(!flag?0:1),ids:ids,dosubmit:1},function() {dict_category.getList(); });
}

//
dict_category.addLabelInput = function (obj,val) {
    var labelInput = $('<span class="float_l mgR8">' +
        '<input name="labels[]" class="form-control float_l" type="text" style="width:80px;" placeholder="输入标签" validate-rule="require|isChinese|maxLength:30" value="'+(!val?'':val)+'"/>' +
        '<a class="pointer float_r wsbtn" onclick="$(this).parent().remove();"><i class="icon icon-times"></i></a>'+
        '</span>');
    obj.before(labelInput);
    $(labelInput).initValidate();
}

//更新缓存，用于ES分词判断
dict_category.refreshCache =  function () {
    "use strict";
    $.postJ('/dict/category/refreshCache',{dosubmit:1},function(r) { new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();   });
}

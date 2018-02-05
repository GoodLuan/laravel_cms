/* 
* @Author: brandon
* @Date:   2016-06-01 15:30:13
* @Last Modified time: 2016-07-14 15:05:33
*/

var dict_brand = { page:1, pagesize:10, sort_field:'id', sort_order:'desc' };

dict_brand.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_brand_index .list_table');
    var data = $('#dict_brand_index .search_bar').getFormData();
    data.page=dict_brand.page;
    data.pagesize=dict_brand.pagesize;
    data.sort_field=dict_brand.sort_field;
    data.sort_order=dict_brand.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_brand.page=data.page;
    dict_brand.pagesize=data.pagesize;
    dict_brand.sort_field=data.sort_field;
    dict_brand.sort_order=data.sort_order;

    $.postH('/dict/brand/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.find('tbody>tr').children('.pointer').css('vertical-align','middle');
        obj.listSortAble(dict_brand,function(field,order) { //渲染排序效果
            dict_brand.getList({sort_field:field,sort_order:order});
        });
    });
};

//添加
dict_brand.add = function() {
    "use strict";
    dialogWin({title:'添加品牌',url:'/dict/brand/add',icon:'icon-plus',width:900,divId:parseInt( new Date().getTime()),
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_brand.addSubmit(win);
            }}
        ]
    },function(win) {
        win.find('[use-fileupload]').renderFileUpload();
        win.find('form').initValidate();
    });
};

//添加表单提交
dict_brand.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/brand/add',
        data:{dosubmit:1},
        success: function (r) {
            var html = '';
            if (r.data.warm)  html += '<div class="textarea_warm">品牌描述出现<span style="color:red ">一级</span>敏感词:<span style="color:red ">'+r.data.warm+'</span>请<span style="color: ">重新编辑</span></span></div><br>';
            if (r.data.notice)  html += '<div class="textarea_notice">品牌描述出现<span style="color:red ">二级</span>敏感词:<span style="color:red ">'+r.data.notice+'</span>如有问题，请重新修改</div><br>';
            if(r.data.warm || r.data.notice ){
                dialogWin({title:'敏感词提醒',url:'/pms/product/checksensitive',data:r.data,icon:'icon-plus',width:320,divId:'product_sensitive',
                },function(win){
                    win.find('.modal-body').html(html);
                });
            }
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_brand.getList(); }
        }
    });
};


//编辑
dict_brand.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑品牌['+id+']',url:'/dict/brand/edit',data:{'id':id},icon:'icon-pencil',width:900,divId:new Date().getTime(),
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_brand.editSubmit(win);
            }}
        ]
    },function(win) {
        win.find(':input[name=logo_url]').renderFileUpload({uploadPath:'WstBrand/logo',imageSize:'170x52'});
        win.find(':input[name=thumb_url]').renderFileUpload({uploadPath:'WstBrand/thumb',imageSize:'168*143'});
        win.find(':input[name=big_img_url]').renderFileUpload({uploadPath:'WstBrand/bigImg',imageSize:'748*414'});
        win.find('form').initValidate();
    });
};


//编辑表单提交
dict_brand.editSubmit = function(win) {
    "use strict";

    var flag = win.find('input[name=flag]:checked').val();
    if(flag==0){
        if(!confirm('品牌禁用后该品牌下所有商品均将下架，是否保存操作?')){return false;}
    }
    win.find('form').ajaxSubmit({
        url:'/dict/brand/edit',
        data:{dosubmit:1},
        success: function (r) {
            var html = '';
            if (r.data.warm)  html += '<div class="textarea_warm">品牌描述出现<span style="color:red ">一级</span>敏感词:<span style="color:red ">'+r.data.warm+'</span>请<span style="color: ">重新编辑</span></span></div><br>';
            if (r.data.notice)  html += '<div class="textarea_notice">品牌描述出现<span style="color:red ">二级</span>敏感词:<span style="color:red ">'+r.data.notice+'</span>如有问题，请重新修改</div><br>';
            if(r.data.warm || r.data.notice ){
                dialogWin({title:'敏感词提醒',url:'/pms/product/checksensitive',data:r.data,icon:'icon-plus',width:320,divId:'product_sensitive',
                },function(win){
                    win.find('.modal-body').html(html);
                });
            }
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_brand.getList(); }
        }
    });

};


//批量启用禁用
dict_brand.setFlag = function(flag) {
    "use strict";

    if(flag==0){
        if(!confirm('品牌禁用后该品牌下所有商品均将下架，是否保存操作?')){return false;}
    }

    var ids='';
    $('#dict_brand_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-id')) {
                ids += ids == '' ? $(this).attr('data-id') : ',' + $(this).attr('data-id');
            }
        }
    });
    $.postJ('/dict/brand/setflag',{flag:(!flag?0:1),ids:ids,dosubmit:1},function() {dict_brand.getList(); });
}

//更新缓存，用于ES分词判断
dict_brand.refreshCache =  function () {
    "use strict";
    $.postJ('/dict/brand/refreshCache',{dosubmit:1},function(r) { new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();   });
}

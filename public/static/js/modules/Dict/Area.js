/*
* @Author: peak
* @Date:   2016-09-20 11:23:13
*/

var dict_area = { page:1, pagesize:20, sort_field:'id', sort_order:'desc' };

dict_area.getList = function(params,obj) {
    "use strict";
    obj = !!obj ? obj : $('#dict_area_index .list_table');
    var data = $('#dict_area_index .search_bar').getFormData();
    data.page=dict_area.page;
    data.pagesize=dict_area.pagesize;
    data.sort_field=dict_area.sort_field;
    data.sort_order=dict_area.sort_order;
    if($.isPlainObject(params)) { $.extend(data,params); }
    dict_area.page=data.page;
    dict_area.pagesize=data.pagesize;
    dict_area.sort_field=data.sort_field;
    dict_area.sort_order=data.sort_order;

    obj.html('<i class="icon icon-spin icon-spinner-indicator"></i>');
    $.postH('/dict/area/lists',data,function(res) {
        obj.html(res);
        obj.listCheckAble(); //渲染复选框效果
        obj.listSortAble(dict_area,function(field,order) { //渲染排序效果
            dict_area.getList({sort_field:field,sort_order:order});
        });        
    });
};

//添加
dict_area.add = function() {
    "use strict";
    dialogWin({title:'添加地区',url:'/dict/area/add',icon:'icon-plus',width:700,
        btns:[
            {name:'提交',style:'btn-primary',callback:function(win) {
                dict_area.addSubmit(win);
            }}
        ]
    },function (win) {
        dict_area.areaSelector(win.find(':input.pidSelector'),{pid:1,maxLevel:2,minLevel:1});
    });
};

//添加表单提交
dict_area.addSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/area/add',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_area.getList(); }
        }
    });
};


//编辑
dict_area.edit = function(id) {
    "use strict";
    dialogWin({title:'编辑地区['+id+']',url:'/dict/area/edit',data:{id:id},icon:'icon-pencil',width:700,
        btns:[
            {name:'保存',style:'btn-primary',callback:function(win) {
                dict_area.editSubmit(win);
            }}
        ]
    });
};

//编辑表单提交
dict_area.editSubmit = function(win) {
    "use strict";
    win.find('form').ajaxSubmit({
        url:'/dict/area/edit',
        data:{dosubmit:1},
        success: function (r) {
            new $.zui.Messager(r.info, { type:!r.status?'warning':'success', placement: 'center' }).show();
            if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); dict_area.getList(); }
        }
    });
};

//批量启用禁用
dict_area.setFlag = function(flag) {
    "use strict";
    var ids='';
    $('#dict_area_index .list_table').find('tbody>tr[data-id]').each(function(i) {
        if($(this).find('i.checkable').hasClass('icon-checked')) {
            if($(this).attr('data-id')) {
                ids += ids == '' ? $(this).attr('data-id') : ',' + $(this).attr('data-id');
            }
        }
    });
    $.postJ('/dict/area/setflag',{flag:(!flag?0:1),ids:ids,dosubmit:1},function() {dict_area.getList(); });
}

/*地区选择器控件
 * 参数 inputObj 指输入控件 jquery对象
 * 参数 params 指选项
 * 参数 params.pid 指定上级id（0国家级别，1中国省级别）
 * 参数 params.retField 指定返回值的字段
 * 参数 params.maxLevel 指定可显示的最大级别(国家0，省1，市2，区县3)
 * 参数 params.minLevel 指定可获取值的最小级别(国家0，省1，市2，区县3)
 * 参数 params.cls 指定下拉列表的css class，默认form-control
 * 参数 params.initId 指定id初始值（0默认不指定）
 */
dict_area.areaSelector = function (inputObj,params) {
    "use strict";
    var data = {pid:0,retField:'id',maxLevel:5,minLevel:3,cls:'form-control',initId:0};
    if($.isPlainObject(params)) { $.extend(data,params); }

    function buildValue(areaInfo,retField) {
        var fields = retField.split(',');
        var val = [];
        for(var i in fields) { val[i] = areaInfo[fields[i]];}
        return val.join(',');
    }
    function renderSelector(sObj,pid,initId,fn) {
        sObj.nextAll().remove();
        /*$.postJ('/dict/area/getareas',{pid:pid,dosubmit:1},function(area) {
         if(!!area.status && $.isPlainObject(area.data)) {
         var areaList = area.data;*/
        $.jsonP('dict','frontend/area/list',{pid:pid,flag:1,list_index:'id',show_all:1},function (area) {
            if(!!area.status && $.isPlainObject(area.data.rows)) {
                var areaList = area.data.rows;
                var areaSelector  = $('<select class="'+data.cls+'" style="width:auto!important;display:inline!important;"><option value="">- 请选择 -</option></select>');
                var options = '';
                for(var areaId in areaList) {
                    options+='<option value="'+areaList[areaId]['id']+'" '+(initId==areaList[areaId]['id']?'selected':'')+'>'+areaList[areaId]['area_name']+'</option>';
                }
                if(!!options) {
                    areaSelector.append(options);
                    sObj.after(areaSelector);
                    areaSelector.change(function() {
                        areaSelector.nextAll().remove();
                        var selectedId = $(this).val();
                        if(!!areaList[selectedId]) {
                            if(areaList[selectedId]['level']>=data.minLevel) inputObj.val(buildValue(areaList[selectedId],data.retField));
                            else inputObj.val('');
                            if(areaList[selectedId]['level']<data.maxLevel) renderSelector(areaSelector,selectedId);
                        }
                        else inputObj.val('');
                    });
                    if($.isFunction(fn)) fn(areaSelector);
                }
            }
        });
    }
    if(data.initId>0) {
        var sInitId = data.initId+'';
        data.pid=1; //有初始值则从中国查起
        var provinceId = parseInt(sInitId.substring(0,2)+'0000');
        var cityId = data.initId>provinceId ? parseInt(sInitId.substring(0,4)+'00'):0;
        var areaId = data.initId>cityId ? data.initId:0;
        renderSelector(inputObj,data.pid,provinceId,function (pObj) {
            if(cityId>0) {  renderSelector(pObj,provinceId,cityId,function (cObj) { if(areaId>0) { renderSelector(cObj,cityId,areaId); } });  }
        });
    }
    else renderSelector(inputObj,data.pid);
}
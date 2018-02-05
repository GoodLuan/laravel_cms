<form class="form-horizontal" role="form" method="post">
	<div class="table_datatab_l table_datatab_overseas">
		<div class="form-group">
			<input type="hidden" name="id" value="{{$detail['id']}}">
	    	<label class="col-md-4 control-label align_r"><b class="text-important">*</b>父 类：</label>
	       		<div class="col-md-7">
	       			<select class="form-control form-focus" name="pid">
						<option value="0">一级菜单</option>
       					<?php { treeCallBack($menus,function($vo,$repeat) use($detail) {  ?>
                            <option value="{{$vo['id']}}" class="align_l" <?php  if($detail['pid']==$vo['id']) {echo "selected";} ?>><?php echo str_repeat('&nbsp;',$repeat*4); ?><?php echo $repeat>0?'└─':'';?> {{$vo['name']}}</option>
                        <?php });  } ?>
					</select>
	        	</div>
		</div>

	<div class="form-group">
	    <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 导航栏名称：</label>
        <div class="col-md-7">
            <input name="name" class="form-control" type="text" placeholder value="{{$detail['name']}}" validate-rule="require|maxLength:90">
       	</div>
	</div>

    <div class="form-group">
	    <label class="col-md-4 control-label align_r"><b class="text-important">*</b> /模块/类/方法：</label>
        <div class="col-md-7">
            <input name="url" class="form-control" type="text" placeholder value="{{$detail['url']}}" validate-rule="maxLength:90">
       	</div>
	</div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 是否启用：</label>
        <div class="col-md-7">
            <label class="radio-inline"> <input name="flag" value="0" type="radio" <?php echo !$detail['flag']?'checked=""':''?>> 是 </label>
            <label class="radio-inline"> <input name="flag" value="1" type="radio" <?php echo $detail['flag']?'checked=""':''?>> 否 </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r">排序：</label>
        <div class="col-md-7">
            <input name="order_by" class="form-control" type="text" placeholder value="{{$detail['order_by']}}" validate-rule="require|default:0|maxLength:10">
        </div>
    </div>
</div>	
</form>
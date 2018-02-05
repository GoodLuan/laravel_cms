<form class="form-horizontal" role="form" method="post">
	<div class="table_datatab_l table_datatab_overseas">
		<input name="id" class="form-control" type="hidden" placeholder value="{{$detail['id']}}">
		<div class="form-group">
	        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 组名称：</label>
	        <div class="col-md-6">
	            <input name="group_name" class="form-control" type="text" placeholder value="{{$detail['name']}}">
	        </div>
		</div>

		<div class="form-group">
	        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 备注：</label>
	        <div class="col-md-6">
	            <input name="remarks" class="form-control" type="text" placeholder value="{{$detail['remarks']}}">
	        </div>
		</div>		
	</div>
</form>


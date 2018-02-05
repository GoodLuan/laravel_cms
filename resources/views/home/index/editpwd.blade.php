<form class="form-horizontal" role="form" method="post">
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 旧密码：</label>
        <div class="col-md-6">
            <input name="pwd_old" class="form-control" type="password" placeholder autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 新密码：</label>
        <div class="col-md-6">
            <input name="password" class="form-control" type="password" placeholder autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 重复新密码：</label>
        <div class="col-md-6">
            <input name="password1" class="form-control" type="password" placeholder autocomplete="off">
        </div>
    </div>
    <input name="id" class="form-control" value="{{$uid}}" type="hidden" placeholder autocomplete="off">
</form>
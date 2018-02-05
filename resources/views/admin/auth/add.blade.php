<form class="form-horizontal" role="form" method="post">
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 用户组id：</label>
        <div class="col-md-6">
            <select class="form-control form-focus" name="role_id">
                <option value="">--请选择--</option>
                    @foreach($role as $r)
                <option value="{{$r['id']}}">{{$r['name']}}</option>
               @endforeach
            </select> 
           <!--  <input name="group_id" class="form-control" type="text" placeholder> -->
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 用户名：</label>
        <div class="col-md-6">
            <input name="username" class="form-control" type="text" placeholder>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 姓名：</label>
        <div class="col-md-6">
            <input name="name" class="form-control" type="text" placeholder>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 手机：</label>
        <div class="col-md-6">
            <input name="phone" class="form-control" type="text" placeholder autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 密码：</label>
        <div class="col-md-6">
            <input name="password" class="form-control" type="password" placeholder autocomplete="off">
        </div>
    </div>
      <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> email：</label>
        <div class="col-md-6">
            <input name="email" class="form-control" type="text" placeholder>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 是否启用：</label>
        <div class="col-md-6">
            <label class="radio-inline"> <input name="is_open" value="0" checked="" type="radio"> 是 </label>
            <label class="radio-inline"> <input name="is_open" value="1" type="radio"> 否 </label>
        </div>
    </div>
</form>
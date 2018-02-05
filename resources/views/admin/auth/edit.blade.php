<form class="form-horizontal" role="form" method="post">
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 用户组：</label>
        <div class="col-md-6">
            <select name="role_id" class="form-control form-focus">
                @foreach($role as $r)
                    <option value="{{$r['id']}}" <?php if ($detail['role_id'] == $r['id']) {
                        echo "selected";
                    }?> >{{$r['name']}}</option>
                @endforeach
            </select>
            <input name="id" class="form-control" type="hidden" readonly value="{{$detail['id']}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r">用户名：</label>
        <div class="col-md-6 control-label align_l">
            {{$detail['username']}}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 姓名：</label>
        <div class="col-md-6">
            <input name="name" class="form-control" type="text" placeholder value="{{$detail['name']}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 手机：</label>
        <div class="col-md-6">
            <input name="phone" class="form-control" type="text" placeholder autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"> 密码：</label>
        <div class="col-md-6">
            <input name="password" class="form-control" type="password" placeholder value="" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> email：</label>
        <div class="col-md-6">
            <input name="email" class="form-control" type="text" placeholder value="{{$detail['email']}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label align_r"><b class="text-important">*</b> 是否启用：</label>
        <div class="col-md-6">
            <label class="radio-inline"> <input name="is_open" value="0"
                                                type="radio" <?php echo !$detail['is_open'] ? 'checked=""' : ''; echo getLoginInfo()['username'] == $detail['username'] ? ' disabled=""' : '';?>>
                是 </label>
            <label class="radio-inline"> <input name="is_open" value="1"
                                                type="radio" <?php echo $detail['is_open'] ? 'checked=""' : ''; echo getLoginInfo()['username'] == $detail['username'] ? ' disabled=""' : '';?>>
                否 </label>
        </div>
    </div>
</form>
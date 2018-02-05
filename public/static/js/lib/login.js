jQuery.support.cors = true; //<IE10
$.ajaxSetup({ global : true, cache : false, xhrFields: { withCredentials: true }, crossDomain: true});
	//验证登入
	function checkLogin(){
		var username = $('#username').val();
		if(username=='')
		{
			$('.login_msg').html('用户名不能为空');
					$('#verify').val('');
			return false;
		}

		var password = $('#password').val();
		if(password=='')
		{
			$('.login_msg').html('密码不能为空');
					$('#verify').val('');
			return false;
		}
        var _token = $('#_token').val();
        var geetest_challenge = $('[name="geetest_challenge"]').val();
        var geetest_validate = $('[name="geetest_validate"]').val();
        var geetest_seccode = $('[name="geetest_seccode"]').val();
		var post = {
            _token:_token,
			username:username,
			password:password,
            geetest_challenge:geetest_challenge,
            geetest_validate:geetest_validate,
            geetest_seccode:geetest_seccode,
		}
		$.post('/home/login',post,function(r){
			r = $.isPlainObject(r)?r:$.parseJSON(r);
            if(!r.status) {

                $('.login_msg').html(r.msg);
                $('#verify').val('');
                // fleshVerify();
            }else if(r.status==2){
            	shade.style.display='block';
            	Layer1.style.display='block';
            	$("#mobile").html(r.data.mobile);
            	$("#user_id").val(r.data.user_id);
                return false;
            }else{
                window.location.href = '/';
            }
		});
		return false;
	}


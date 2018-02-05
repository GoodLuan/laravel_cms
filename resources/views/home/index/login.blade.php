<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{config('setting')['APP_TITLE']}} -- 登录</title>
<!-- zui -->
<link href="/static/css/login.css" rel="stylesheet">

<script type="text/javascript" src="/static/js/lib/jquery.min.js"></script>
<script src="/static/js/lib/JPlaceHolder.js"></script>
<script src="/static/js/lib/login.js"></script>

<style type="text/css">
.dllogo {background:url(/static/images/bg.gif) no-repeat; background-size:cover;}
#Layer1 { height: 150px; width: 280px; border: 5px solid #999; margin-right: auto; margin-left: auto; z-index: 50; display: none; position: relative; background-color: #FFF;top:-300px;}
#Layer1 #win_top {  height: 30px;  width: 280px;  border-bottom-width: 1px;  border-bottom-style: solid;  border-bottom-color: #999;  line-height: 30px; color: #666; font-family: "微软雅黑", Verdana, sans-serif, "宋体"; font-weight: bold;  text-indent: 1em;}
#Layer1 #win_top a {  float: right; margin-right: 5px;}
#shade { background-color:#000; position:absolute; z-index:49; display:none;width:100%; height:100%; opacity:0.6; filter: alpha(opacity=60); -moz-opacity: 0.6; margin: 0px;left: 0px; top: 0px; right: 0px; bottom: 0px;}
#Layer1 .content { margin-top: 5px;margin-right: 30px; margin-left: 30px;}
.content_tr{margin-bottom:10px;}
.send_btn{ border:0px; margin-left:8px;color:#8e0c3a;margin-top:-2px;cursor: pointer;background-color:#fff; }
</style>
</head>
<body >
<div id="shade"></div>
<div class="dllogo">
	<div class="meicilogo"></div>
    <div class="dlcentert">
        <input name="_token" id="_token"  type="hidden" value="{{ csrf_token() }}">
        <div class="login_item">
            <input type="text" class="username" placeholder="Username" id="username" name="username"/>
        </div>
        <div class="login_item">
            <input type="password" class="password" placeholder="Password" id="password" name="password" autocomplete="off"/>
        </div>
        <div class="login_item login_item_code">
            {!! Geetest::render() !!}
        </div>
        <div class="login_in">
            <button class="login_btn" onfocus="blur(this)" onclick="return checkLogin()">LOG IN</button>
        </div>
        <div class="login_msg"></div>
        <div id="Layer1">
            <div id="win_top">手机验证<a href="#" onClick="shade.style.display='none';Layer1.style.display='none';">关闭</a></div>
            <br />
            <div class="content">
                <div class="content_tr">
                    <b>手　机：</b><span id="mobile"></span>
                    <input autocomplete="off" type="button" value="发送验证码" onclick="sendsms(this);" class="send_btn" />
                </div>
                <div class="content_tr">
                    <input type="hidden" value="" id="user_id" name="userId" autocomplete="off">
                    <b>验证码：</b><input type="text" id="mobileCode" name="code" style="margin-right: 5px; width: 140px;" autocomplete="off" onkeydown='if(event.keyCode==13){checksms();}'><br>
                </div>
                <div style="text-align:center;"><button style="padding:2px 15px;cursor: pointer;" onclick="checksms();">验 证</button></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" charset="utf-8">
    // 绑定键盘按下事件
    $('.login_item').find(':input').keyup(function(e) {
        // 回车键事件
        if(e.which == 13) { jQuery(".login_btn").click();  }
    });

	$(function(){
		bgsize();
	});
    $(window).resize(function(){
        bgsize();
    });

	function bgsize(){
		var height = $(window).height();
        var width = $(window).width();
		$('.dllogo').css({
            width:width + 'px',
            height:height + 'px'
        })
	}
</script>

</body>
</html>
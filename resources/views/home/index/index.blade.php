<!DOCTYPE html>
<html lang="cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>{{config('setting')['APP_TITLE']}}</title>
	<meta name="Keywords" content="WST3.0后台">
	<meta name="Description" content="WST3.0后台">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>

	<link rel="stylesheet" href="/static/css/zui.min.css"/>
	<link rel="stylesheet" href="/static/css/swiper.css"/>
	<link rel="stylesheet" href="/static/css/zui-theme.css"/>
	<link rel="stylesheet" href="/static/css/main_m.css"/>
	<link rel="stylesheet" href="/static/css/datetimepicker.min.css"/>
	<link rel="stylesheet" href="/static/css/wst.css"/>
	<link rel="stylesheet" href="http://cache.amap.com/lbs/static/main.css"/>
	<!--[if lte IE 6]>
	<link rel="stylesheet" type="text/css" href="/static/css/ie/bootstrap-ie6.min.css">
	<![endif]-->
	<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="/static/css/ie/ie.css">
	<![endif]-->
	<script type="text/javascript" src="/static/js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/static/js/lib/jquery.form.js"></script>
	<script type="text/javascript" src="/static/js/lib/jquery-barcode.js"></script>
	<script type="text/javascript" src="/static/js/lib/zui.min.js"></script>
	<script type="text/javascript" src="/static/js/lib/datetimepicker.min.js"></script>
	<script type="text/javascript" src="/static/js/lib/chosen.min.js"></script>
	<script type="text/javascript" src="/static/js/lib/in-min.js"></script>
	<script type="text/javascript" src="/static/js/lib/main_m.js"></script>
	<script type="text/javascript" src="/static/js/lib/validate.js"></script>
	<script type="text/javascript"
			src="http://webapi.amap.com/maps?v=1.3&key=98ccc8be7c5ec4e4d9b034230f8d993a"></script>
	<script type="text/javascript" src="http://webapi.amap.com/demos/js/liteToolbar.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
	<script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script type="text/javascript">
        $(function () {
            eventOfMenus();
            window.setInterval(function () {
                $.postJ('/home/islogin', {dosubmit: 1}, function (json) {
                    if (!json.data.isLogin) {
                        msgShow('请重新登录！', 0);
                    }
                });
            }, 300000); //每 5分钟 检测登陆 状态
            addTabToNavBar('首页', 'home/welcome', false);
        });
	</script>

</head>
<body>
<body>
<!-- 头部 -->
<div class="header">
	<div class="logo"><a href="/"><img src="/static/images/wst_logo.png" alt="" title="" /></a></div>
	<div class="nav nav-primary color-white">
		<span class="color-back"><?php echo $userInfo['username'];?> </span>
		<a style="margin: 0px 20px;" href="#" title="我的导入导出任务" onclick=" modules('Auth/ImexTask',function() {dict_imextask.listWin();});"><i class="icon icon-tasks"></i></a>
		<a href="#" title="修改密码" onclick="editpwd({{$userInfo['id']}})">
			<i class="icon icon-wrench"></i>
		</a>
		<a href="/home/logout" title="登出"><i class="icon icon-signout"></i></a>
	</div>
	<div class="clear"></div>
</div>
<!-- 左侧导航 -->
<div class="leftSidebar">
	<ul class="partent_side">
		<li>
			<div class="menu_left" url="/home/welcome">首页</div>
		</li>
			@foreach($accesss as $vo)
			<li>
				<div class="menu_left" url="">{{$vo['name']}}
                    <?php if(!empty($vo['_child'])) { ?>
					<em class="sjlg"></em>
                    <?php } ?>
				</div>
				<div class="menu_lnext">
					<ul>
                        <?php if(!empty($vo['_child'])) { ?>
							@foreach($vo['_child'] as $svo)
								<li url="{{$svo['url']}}">{{$svo['name']}}</li>
							@endforeach
                        <?php } ?>
					</ul>
				</div>
			</li>
		@endforeach
	</ul>
	<div class="menu_turn_left" title="收起"><i class="icon icon-chevron-left"></i></div>
</div>
<!-- 右侧导航 -->
<div class="rightSidebar">
	<div class="pageTit">
		<div class="menu_turn_right" title="展开"><i class="icon icon-chevron-right"></i></div>
		<ul id="HomeTab" class="nav nav-secondary"></ul>
		<div class="clear"></div>
	</div>
	<div id="HomeTabContent" class="tab-content"></div>

</div>
<script type="text/javascript" charset="utf-8">
    $(function() {
        $('.leftSidebar .menu_turn_left').click(function() {
            $('.leftSidebar').animate({ left: -215}, "normal", function() {
                $('.rightSidebar').animate({ left: 0}, "normal",function(){
                    $('.rightSidebar .pageTit').animate({"margin-left":30},"normal");
                });
            });
        });

        $('.rightSidebar .pageTit .menu_turn_right').click(function() {
            $('.rightSidebar .pageTit').animate({"margin-left":8},"normal",function() {
                $('.rightSidebar').animate({ left: 215}, "normal", function() {
                    $('.leftSidebar').animate({ left: 0}, "normal");
                });
            });
        });

    });

    //编辑
    function editpwd(id) {
        "use strict";
        dialogWin({title:'修改个人密码',url:'/home/editpwd',data:{id:id},icon:'icon-pencil',width:400,
            btns:[
                {name:'保存',style:'btn-primary',callback:function(win) {
                        editpwdSubmit(win);
                    }}
            ]
        });
    };

    //编辑表单提交
    function editpwdSubmit(win) {
        "use strict";
        win.find('form').ajaxSubmit({
            url:'/home/editpwd',
            data:{dosubmit:1},
            success: function (r) {
                new $.zui.Messager(r.msg, { type:!r.status?'warning':'success', placement: 'center' }).show();
                if(!!r.status) { win.find('form').resetForm(); win.modal('hide'); auth_user.getList(); }
            }
        });
    };
</script>

</body>
</html>
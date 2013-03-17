{__NOLAYOUT__}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="__PUBLIC__/css/admin/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$TITLE}</title>
<script type="text/javascript" src="__PUBLIC__/js/libs/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
/* 按下F5时仅刷新iframe页面 */
function inactiveF5(e) {
	return ;
	e=window.event||e;
	var key = e.keyCode;
	if (key == 116){
		parent.MainIframe.location.reload();
		if(document.all) {
			e.keyCode = 0;
			e.returnValue = false;
		}else {
			e.cancelBubble = true;
			e.preventDefault();
		}
	}
}

function nof5() {
    return ;
	if(window.frames&&window.frames[0]) {
		window.frames[0].focus();
		for (var i_tem = 0; i_tem < window.frames.length; i_tem++) {
			if (document.all) {
				window.frames[i_tem].document.onkeydown = new Function("var e=window.frames[" + i_tem + "].event; if(e.keyCode==116){parent.MainIframe.location.reload();e.keyCode = 0;e.returnValue = false;};");
			}else {
				window.frames[i_tem].onkeypress = new Function("e", "if(e.keyCode==116){parent.MainIframe.location.reload();e.cancelBubble = true;e.preventDefault();}");
			}
		} //END for()
	} //END if()
}

function refresh() {
	parent.MainIframe.location.reload();
}

document.onkeydown=inactiveF5;
</script>
</head>
<body scroll="no" style="margin:0; padding:0;" onload="nof5()">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td colspan="3">
        <div class="header"><!-- 头部 begin -->
            <div class="logo"><a href="/admin2lib" >&nbsp;</a></div>
		    <div class="nav_sub">
		    	您好,{:session('user_name')}&nbsp; | 
                <!--<a href="javascript:void(0);" onclick="refresh();">刷新</a> | 
                <a href="/logout">退出</a><br/>-->
		    	<div id="TopTime"></div>
		    </div>
            <div class="main_nav">
                <a id="channel_index" class="on" href="javascript:void(0)" onclick="switchChannel('index');" hidefocus="true" style="outline:none;">首页</a>
				<a id="channel_content"  href="javascript:void(0)" onclick="switchChannel('content');" hidefocus="true" style="outline:none;">用户</a>
            </div>
        </div>
		<div class="header_line"><span>&nbsp;</span></div>
    </td>
  </tr>
<tr>
    <td width="200px" height="100%" valign="top" id="FrameTitle" background="__PUBLIC__/images/admin/left_bg.gif">
        <div class="LeftMenu">
        <!-- 第一级菜单，即大频道 -->
        <ul class="MenuList" id="root_index" >
        <!-- 第二级菜单 -->
            <li class="treemenu">
                <a id="root_1" class="actuator" href="javascript:void(0)" onclick="switch_root_menu('1');" hidefocus="true" style="outline:none;">首页</a>
                <ul id="tree_1" class="submenu">
                <!-- 第三级菜单 -->
                <li>
                <a id="menu_2" href="javascript:void(0)" onClick="switch_sub_menu('2', '{:U('Admin/Index/main')}');" class="submenuA" hidefocus="true" style="outline:none;">新闻管理</a>
                </li>
                </ul>
            </li>
        </ul>

		<!-- 用户部分 -->      
		<ul class="MenuList" id="root_content" style="display:none;">
		<!-- 第二级菜单 -->
			<li class="treemenu">
				<a id="root_14" class="actuator" href="javascript:void(0)" onclick="switch_root_menu('14');" hidefocus="true" style="outline:none;">用户</a>
				<ul id="tree_14" class="submenu">
				<!-- 第三级菜单 -->
					<li><a id="menu_16" href="javascript:void(0)" onClick="switch_sub_menu('16', '{:U('Admin/User/index')}');" class="submenuA" hidefocus="true" style="outline:none;">用户管理</a></li>
				</ul>
			</li>
		</ul>
		</div>
	</td>
	<td>
		<iframe onload="nof5()" id="MainIframe" name="MainIframe" scrolling="yes" src="{:U('Admin/Index/main')}" width="100%" height="100%" frameborder="0" noresize> </iframe>
	</td>
</tr>
</table>
</body>

<script type="text/javascript">
var current_channel   = null;
var current_menu_root = null;
var current_menu_sub  = null;
var viewed_channel	  = new Array();
	
$(document).ready(function(){
	switchChannel('index');
});
	
//切换频道（即头部的tab）
function switchChannel(channel) {
	if(current_channel == channel) return false;
	$('#channel_'+current_channel).removeClass('on');
	$('#channel_'+channel).addClass('on');
	$('#root_'+current_channel).css('display', 'none');
	$('#root_'+channel).css('display', 'block');
	var tmp_menulist = $('#root_'+channel).find('a');
	tmp_menulist.each(function(i, n) {
		// 防止重复点击ROOT菜单
		if( i == 0 && $.inArray($(n).attr('id'), viewed_channel) == -1 ) {
			$(n).click();
			viewed_channel.push($(n).attr('id'));
		}
		if ( i == 1 ) {
			$(n).click();
		}
	});
	current_channel = channel;
}
	
function switch_root_menu(root) {
	root = $('#tree_'+root);
	if (root.css('display') == 'block') {
		root.css('display', 'none');
		root.parent().css('backgroundImage', 'url(/Public/images/admin/ArrOn.png)');
	}else {
		root.css('display', 'block');
		root.parent().css('backgroundImage', 'url(/Public/images/admin/ArrOff.png)');
	}
}
	
function switch_sub_menu(sub, url) {
	if(current_menu_sub) {
		$('#menu_'+current_menu_sub).attr('class', 'submenuA');
	}
	$('#menu_'+sub).attr('class', 'submenuB');
	current_menu_sub = sub;
	parent.MainIframe.location = url;
}
</script>
</html>
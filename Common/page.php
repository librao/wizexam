<?php

/**
 * get_body_class()
 * 获得body页面class
 * 
 * @return void
 */
function get_body_class() {
	$class = '';
	if(in_array(ucfirst(MODULE_NAME), C('HTML_FULL_SCREEN_MODEL')))
		$class .= 'fluid-width';
	return $class;
}

/**
 * get_nav_class()
 * 获取body的页面class值
 * 
 * @param mixed $module
 * @param string $class
 * @return string
 */
function get_nav_class($module, $class = 'current') {
	switch($module) {
		case 'home':
			if(is_home_page())
				return $class;
			break;
		case 'info':
			if(is_info_page())
				return $class;
			break;
		case 'community':
			if(is_community_page())
				return $class;
			break;
		case 'zhidao':
			if(is_zhidao_page())
				return $class;
			break;
		case 'mart':
			if(is_mart_page())
				return $class;
			break;
		case 'center':
			if(is_user_page())
				return $class;
			break;
		case 'play':
			if(is_play_page())
				return $class;
			break;
		case 'events':
			if(is_events_page())
				return $class;
			break;
		case 'jiazu':
			if(is_jiazu_page())
				return $class;
			break;
		case 'list':
			if(is_list_page())
				return $class;
			break;
		case 'chuangzuo':
			if(is_chuangzuo_page())
				return $class;
			break;
		case 'tools':
			if(is_tools_page())
				return $class;
			break;
		case 'gongyi':
			if(is_gongyi_page())
				return $class;
			break;
	}
}

/**
 * is_chuangzuo_page()
 * 判断是否创作坊栏目
 * 
 * @return bool
 */
function is_chuangzuo_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && (isset($_GET['g_cate']) && $_GET['g_cate'] == '6'))
		return true;
	else
		return false;
}

/**
 * is_community_page()
 * 判断是否社区栏目
 * 
 * @return bool
 */
function is_community_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && strtoupper(ACTION_NAME) == 'INDEX')
		return true;
	else
		return false;
}

/**
 * is_events_page()
 * 判断是否赛事栏目
 * 
 * @return bool
 */
function is_events_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && (isset($_GET['g_cate']) && $_GET['g_cate'] == '4'))
		return true;
	else
		return false;
}

/**
 * is_gongyi_page()
 * 判断是否公益栏目
 * 
 * @return bool
 */
function is_gongyi_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && (isset($_GET['g_cate']) && $_GET['g_cate'] == '7'))
		return true;
	else
		return false;
}

/**
 * is_home()
 * 判断是否首页
 * 
 * @return bool
 */
function is_home_page() {
	if(strtoupper(MODULE_NAME) == 'INDEX' && strtoupper(ACTION_NAME) == 'INDEX')
		return true;
	else
		return false;
}

/**
 * is_info_page()
 * 判断是否资讯栏目
 * 
 * @return bool
 */
function is_info_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && (isset($_GET['g_cate']) && $_GET['g_cate'] == '1'))
		return true;
	else
		return false;
}

/**
 * is_jiazu_page()
 * 判断是否家族栏目
 * 
 * @return bool
 */
function is_jiazu_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && (isset($_GET['g_cate']) && $_GET['g_cate'] == '5'))
		return true;
	else
		return false;
}

/**
 * is_list_page()
 * 判断是否大全页
 * 
 * @return
 */
function is_list_page() {
	if(strtoupper(MODULE_NAME) == 'LIST' && !(isset($_GET['play'])))
		return true;
	else
		return false;
}

/**
 * is_mart_page()
 * 判断是否市集栏目
 * 
 * @return bool
 */
function is_mart_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && (isset($_GET['g_cate']) && $_GET['g_cate'] == '3'))
		return true;
	else
		return false;
}

/**
 * is_play_page()
 * 判断是否直接玩栏目
 * 
 * @return void
 */
function is_play_page() {
	if(strtoupper(MODULE_NAME) == 'LIST' && (isset($_GET['w_cate']) && $_GET['g_cate'] == '1') &&
		(isset($_GET['w_cate']) && $_GET['play'] == '1'))
		return true;
	else
		return false;
}

/**
 * is_tools_page()
 * 判断是否工具栏目
 * 
 * @return void
 */
function is_tools_page() {
	if(strtoupper(MODULE_NAME) == 'WIKI' && strtoupper(ACTION_NAME) == 'LIST' && (isset($_GET['w_cate']) && $_GET['g_cate'] == 'tools'))
		return true;
	else
		return false;
}

/**
 * is_user_page()
 * 判断是否用户中心
 * 
 * @return void
 */
function is_user_page() {
	if(strtoupper(MODULE_NAME) == 'MY')
		return true;
	else
		return false;
}

/**
 * is_zhidao_page()
 * 判断是否知道栏目
 * 
 * @return void
 */
function is_zhidao_page() {
	if(strtoupper(MODULE_NAME) == 'GROUP' && (isset($_GET['g_cate']) && $_GET['g_cate'] == '2'))
		return true;
	else
		return false;
}

?>
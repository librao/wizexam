<?php
$db_config = include_once 'db.inc.php'; // 数据库配置
$route_config = include_once 'route.inc.php'; // URL路由配置
$webapp_config = include_once 'webapp.inc.php'; // 项目自身配置

$global_config =  array(
	//'配置项'=>'配置值'

	/* 模板引擎设置 */
    'TMPL_CONTENT_TYPE'     => 'text/html', // 默认模板输出类型
    //'TMPL_ACTION_ERROR'     => THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    //'TMPL_ACTION_SUCCESS'   => THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
    //'TMPL_EXCEPTION_FILE'   => THINK_PATH.'Tpl/think_exception.tpl',// 异常页面的模板文件
    'TMPL_DETECT_THEME'     => false,       // 自动侦测模板主题
    'TMPL_TEMPLATE_SUFFIX'  => '.tpl',     // 默认模板文件后缀
    //'TMPL_FILE_DEPR'        =>  '-', //模板文件MODULE_NAME与ACTION_NAME之间的分割符，只对项目分组部署有效

	/* 默认设定 */
	'APP_GROUP_LIST'        => 'Web,Admin',      // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
	'DEFAULT_GROUP'         => 'Web',  // 默认分组
    //'DEFAULT_THEME'         => 'default',	// 默认模板主题名称
    'URL_MODEL'				=> '2', //URL模式
	'URL_PATHINFO_DEPR'     => '/',	// PATHINFO模式下，各参数之间的分割符号
	'TMPL_PARSE_STRING'  =>array(
		'__UPLOAD__' => '/Upload', // 增加新的上传路径替换规则
		'__IMAGES__' => '/Public/images',
	),
	//'VAR_PAGE'				=> 'page', //分页变量名

	//'TMPL_L_DELIM'			=> '<{',	//模版编译开始
    //'TMPL_R_DELIM'			=> '}>',	//模版编译结束

	// 函数库加载
    'LOAD_EXT_FILE' => 'libtool,user'

);

// 配置项合并
return array_merge($db_config, $route_config, $webapp_config, $global_config);
?>
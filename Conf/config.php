<?php

$db_config = include_once 'db.inc.php';
$config = array(
	'LAYOUT_ON' => true, // 开启模板布局
    'LAYOUT_NAME' => 'base',

	'URL_ROUTER_ON' => TRUE,
	'URL_ROUTE_RULES' => array(
		// 新闻祥情页
		'/^detail\/(\d+)$/' => array('Index/detail?n_id=:1'),

		/**
	 	* 后台管理部分
	 	*/
		// 后台首页
		'admin2lib' => array('Admin/Index/index'),
	),
);

return array_merge($db_config, $config);
?>
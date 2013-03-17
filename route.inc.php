<?php
/***路由配置***/
return array(
	'URL_ROUTER_ON' => TRUE,
	'URL_ROUTE_RULES' => array(
		// 新闻祥情页
		'/^detail\/(\d+)$/' => array('Index/detail?n_id=:1'),

		/**
	 	* 后台管理部分
	 	*/
		// 后台首页
		'admin2lib' => array('Admin/Index/index'),
	
		/**
	 	* 其它部分
	 	*/
		// 生成临时图片
		'/^pic\/(\S+)/' => array('Images/index?var=:1'),
	),
);
?>
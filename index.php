<?php
	// 定义THINKPHP框架路径
	define('THINK_PATH', './ThinkPHP/');

	//定义项目名称和路径
	define('APP_NAME', 'Librao');
	define('APP_PATH', './');

	//项目其它信息
	define('APP_VERSION', 'V1.0');
	define('APP_SUB_VERSION', '20130317');
	define('APP_ROOT_PATH', dirname(__FILE__));

	//开启调试模式
    define('APP_DEBUG', true);
	
	//加载框架入文件
	require (THINK_PATH.'ThinkPHP.php');
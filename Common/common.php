<?php

/**
 * FirePHP调试
 */
function firephp($output){
	require_once 'FirePHPCore/fb.php';
	if(APP_DEBUG){
		fb($output);
	}else{
		return 0;
	}
}

/**
 * load_app_option()
 * 加载项目配置数据
 * 
 * @return void
 */
function load_app_option(){
	if(S('DB_CACHE_APP_OPTION') === false)
		rebuild_app_option_cache();
	$app_configs = S('DB_CACHE_APP_OPTION');
	foreach($app_configs as $config){
		C('APP_' . strtoupper($config['option_name']), $config['option_value']);
	}
}

/**
 * rebuild_app_option_cache()
 * 建立项目配置缓存
 * 
 * @param bool $only_autoload 是否只建立自动加载部分
 * @return void
 */
function rebuild_app_option_cache($only_autoload = true){
	$model = M('Options');
	if($only_autoload)
		$map['autoload'] = array(
			'eq', 'yes'
		);
	$list = $model->field('option_name,option_value')->where($map)->select();
	S('DB_CACHE_APP_OPTION', $list);
}

/**
 * 整理时间格式
 * 
 * @param string $y
 * @param string $m
 * @param string $d
 */
function ymd_format($y, $m, $d){
	$format = '';
	if($y != null){
		$format .= $y;
	}else{
		return $format;
	}
	if($m != null){
		$format .= '-' . $m;
	}else{
		return $format;
	}
	if($d != null){
		$format .= '-' . $d;
	}else{
		return $format;
	}
	return $format;
}

/**
 * 获取图像,若无图像返回对应类型默认图像
 *
 * @param string $src 图像地址
 * @param string $type 类型
 */
function get_image_default($src, $type){
	if('' == $src){
		switch($type){
			case 'avatar':
				return 'http://images.youyouxi.com/avatar/default.jpg';
			case 'wiki':
				return 'http://images.youyouxi.com/wiki/default.jpg';
			case 'group':
				return 'http://images.youyouxi.com/group/logo/default.jpg';
			case 'topic':
				return 'http://images.youyouxi.com/group/topic/default.jpg';
		}
	}
	return $src;
}

?>
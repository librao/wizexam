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
 * 根据分类id获得分类指定字段
 * 
 * @param string $cid
 * @param string $field
 * 
 * @return string
 */
function get_cate_name_by_cid($cid, $field = 'c_name'){
	$cate_array = get_wiki_static_cate_cache();
	$result = list_search($cate_array, 'c_id=' . $cid);
	return $result[0][$field];
}

/**
 * 获得Wiki选项组名称
 * 
 * @param mixed $id
 * @return void
 */
function get_cate_module_name($id){
	$static_model = C('STATIC.STATIC_CATE_NAME');
	$cate_title = list_search($static_model, array(
		'id' => $id
	));
	return ($cate_title[0]['title'] == null)?'':'[' . $id . ']' . $cate_title[0]['title'];
}

/**
 * get_wikicate_by_id()
 * 根据id获得词条类型
 * 
 * @param mixed $cate_id
 * @return
 */
function get_wikicate_by_id($cate_id){
	$wiki_cates = C('STATIC.STATIC_WIKI_CATE');
	if($wiki_cates[$cate_id] != null){
		return $wiki_cates[$cate_id];
	}else{
		return false;
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
 * 分类散列值专用
 * 
 * @param string $c_id 散列值
 * @param integer $c_cid 要获取的类别
 * @param string $field 返回的字段
 * 
 * @return string
 */
function get_cate_name($c_id, $c_cid, $field = 'c_name'){
	$wiki_cate = get_wiki_static_cate_cache();
	$wiki_cate = list_search($wiki_cate, 'c_cid=' . $c_cid);
	$wiki_cate = list_sort_by($wiki_cate, 'c_sort', 'desc');
	// 把传入的字符串转为数组
	$c_id = explode(',', $c_id);
	$return = array();
	foreach($wiki_cate as $v){
		if(in_array($v['c_id'], $c_id)){
			$return[] = $v[$field];
		}
	}
	return implode(',', $return);
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

/**
 * 获取群组名称
 * 
 * @param string $gcid
 * 
 * @return mixed
 */
function get_group_title($gcid){
	$gcid = intval($gcid);
	$static_group_type = C('STATIC.STATIC_GROUP_TYPE');
	$title = $static_group_type[$gcid];
	if($title == null)
		return FALSE;
	else
		return $title;
}

/**
 * 上传到又拍云
 * 
 * @param mixed $files
 * @param string $path
 * @param string $upyun_config
 * 
 * @return boolean
 */
function upload_file_to_upyun($files, $path = '/', $upyun_config = 'UPYUN_CONFIG_1'){
	import('Com.UpYun.Upyun');
	$config = C($upyun_config);
	$upyun = new UpYun($config['BUCKETNAME'], $config['USERNAME'], $config['PASSWORD']);
	// 如果$files是数组，循环上传
	if(is_array($files)){
		foreach($files as $file){
			$fh = fopen($file, 'r');
			if(!$upyun->writeFile($path . basename($file), $fh, TRUE)){
				fclose($fh);
				return FALSE;
			}else{
				fclose($fh);
			}
		}
		return TRUE;
	}elseif(is_string($files)){
		$fh = fopen($files, 'r');
		if($upyun->writeFile($path . basename($files), $fh, TRUE)){
			fclose($fh);
			return TRUE;
		}else{
			fclose($fh);
			return FALSE;
		}
	}
}

/**
 * 获取视频信息
 * @param string $url 视频地址
 */
function get_video_info($url){
	// 视频抓取类与CURL类
	import('Com.Wilead.VideoUrlParser');
	import('Com.Wilead.Curl');
	$video_info = VideoUrlParser::parse($url, TRUE);
	// 无法获取视频
	if($video_info['img'] === null)
		return FALSE;
	else{
		$img_res = Curl::get($video_info['img']);
		// 下载远程图片
		$temp_path = './Runtime/Temp/' . uniqid() . '.jpeg';
		$pf = fopen($temp_path, 'x+');
		fwrite($pf, $img_res);
		fclose($pf);
		// 同步到又拍云
		$upyun_path = '/video/' . date('Y/m/');
		if(upload_file_to_upyun($temp_path, $upyun_path)){
			unlink($temp_path); // 删除临时文件
			$video_info['local_img'] = 'http://images.youyouxi.com' . $upyun_path . basename($temp_path);
			return $video_info;
		}
	}
}

/**
 * 根据区id获得地区数据
 * 
 * @param int $area_id
 * @param int $level 1省2市3区
 */
function get_area_title($area_id, $level = 3){
	$area = get_area_static_cache();
	
	switch ($level){
		case 1:
			$result = list_search($area, array('area_id' => $area_id));
			$result = list_search($area, array('area_id' => $result[0]['pid']));
			$result = list_search($area, array('area_id' => $result[0]['pid']));
			return $result[0]['title'];
			break;
		case 2:
			$result = list_search($area, array('area_id' => $area_id));
			$result = list_search($area, array('area_id' => $result[0]['pid']));
			return $result[0]['title'];
			break;
		case 3:
			$result = list_search($area, array('area_id' => $area_id));
			return $result[0]['title'];
			break;
	}
}

function get_url_need_login($url){
	if(is_login() === FALSE){
		return 'javascript:doLogin();';
	}else{
		return $url;
	}
}

/**
 * 重组筛选项URL
 * @param string $urlfilter	历史筛选值
 * @param int $c_cid		筛选项分类
 * @param int $c_id			点击的筛选项
 */
function rewrite_cate_url($urlfilter='', $c_cid, $c_id = 0){
	$category = C('STATIC.STATIC_CATE_NAME');
	$wiki_cate = get_wiki_static_cate_cache(); // 获取分类缓存
	if(empty($urlfilter)) {
		$urlfilter = $c_id;
	}else{
		$filterarray = explode('|', $urlfilter);
		foreach($category as $key=>$val) {
			if($c_cid == $val['id']) {
				$catelist = list_to_array(list_search($wiki_cate, array('c_cid' => $c_cid)),'c_id');
				$is_rewrite = false;
				foreach($filterarray as $k=>$v) {
					if(in_array($v, $catelist)) {
						if($c_id) {
							$filterarray[$k] = $c_id;
						}else{
							unset($filterarray[$k]);
						}
						$urlfilter = implode('|', $filterarray);
						$is_rewrite = true;
					}
				}
				if(!$is_rewrite)
					$urlfilter .= "|".$c_id;
			}
		}
	}
	return $urlfilter;
}

/**
 * 获取当前用户的评分
 * @param string $urlfilter	历史筛选值
 * @param int $c_cid		筛选项分类
 * @param int $c_id			点击的筛选项
 */
function get_user_score($uid,$group_id){
	$wiki_score = D('WikiScore');
	$where['uid'] = array('eq', $uid);
	$where['group_id'] = array('eq', $group_id);
	$score = $wiki_score->where($where)->field('score')->find();
	return $score['score'];
}

?>
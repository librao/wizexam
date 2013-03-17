<?php

/**
 * big5Togbk()
 * 繁体中文转简体中文
 * 
 * @param mixed $str 要转换的字符串
 * @return String
 */
function big5_to_gbk($str){
	import('ORG.Util.ChineseCharacter');
	$chinese_character = ChineseCharacter::getInstance();
	return $chinese_character->big5_gb2312($str);
}

/**
 * friendly_diff_time()
 * 显示两个时间的友好时间差
 * 
 * @param mixed $time 对比的时间戳
 * @param mixed $current 现在的时间戳
 * @return String
 */
function friendly_diff_time($time, $current = null){
	if(empty($time))
		return '';
	if($current === null)
		$current = time();
	$diff_time = $current - $time;
	if($diff_time < 60)
		return $diff_time . '秒前';
	elseif($diff_time < 3600)
		return intval($diff_time / 60) . '分钟前';
	elseif($diff_time >= 3600 && $diff_time < 86400)
		return intval($diff_time / 3600) . '小时前';
	elseif($diff_time >= 86400 && $diff_time < 604800)
		return intval($diff_time / 86400) . '天前';
	elseif($diff_time >= 604800 && $diff_time < 2592000)
		return intval($diff_time / 604800) . '星期前';
	elseif($diff_time >= 2592000 && $diff_time < 31536000)
		return intval($diff_time / 2592000) . '个月前';
	else
		return date('Y年m月d日 H:i:s', $time);
}

/**
 * gbkTobig5()
 * 简体中文转繁体中文
 * 
 * @param mixed $str 要转换的字符串
 * @return String
 */
function gbk_to_big5($str){
	import('ORG.Util.ChineseCharacter');
	$chinese_character = ChineseCharacter::getInstance();
	return $chinese_character->gb2312_big5($str);
}

/**
 * get_copyright()
 * 输出Copyright信息
 * 
 * @param string $begin_year Copyright开始年份
 * @return String
 */
function get_copyright($begin_year = '2011'){
	$copyright = 'Copyright&copy;';
	$year = date('Y', time());
	if($begin_year == $year)
		$copyright .= $year;
	else
		$copyright .= $begin_year . '-' . $year;
	$copyright .= ' honghong.biz All Rights Reserved. 广州宏宏网络有限公司版权所有';
	return $copyright;
}

/**
 * get_first_letter_by_string()
 * 获得字符串的首字母,支持中文
 * 
 * @param mixed $str 字符串
 * @param string $default 无法获取时返回的字符
 * @return String
 */
function get_first_letter_by_string($str, $default = '0-9'){
	import('ORG.Util.ChineseCharacter');
	$chinese_character = ChineseCharacter::getInstance();
	return $chinese_character->get_first_char($str, $default);
}

/**
 * msubstr()
 * 字符串截断,支持中文和其他编码
 * 
 * @param mixed $str 需要截断的字符串
 * @param integer $start 开始位置
 * @param mixed $length 截断长度
 * @param bool $suffix 截断后是否跟随字符
 * @param string $suffix_str 截断后跟随的字符
 * @param string $charset 字符串编码格式
 * @return String
 */
function msubstr($str, $start = 0, $length, $suffix = true, $suffix_str = '...', $charset = 'utf-8'){
	if(function_exists('mb_substr'))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')){
		$slice = iconv_substr($str, $start, $length, $charset);
		if($slice === false)
			$slice = '';
	}else{
		$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join('', array_slice($match[0], $start, $length));
	}
	return $suffix?$slice . $suffix_str:$slice;
}

/**
 * is_today()
 * 判断时间戳是否是今天
 * 
 * @param integer $timestamp 时间戳
 * @return bool
 */
function is_today($timestamp){
	$timestamp = intval($timestamp);
	if(empty($timestamp) && !is_integer($timestamp))
		return false;
			// 获得今天的时间戳
	$today = strtotime(date('Y-m-d', time()));
	// 获得传入的时间戳当前的时间戳
	$timestamp = strtotime(date('Y-m-d', $timestamp));
	if($today === $timestamp)
		return true;
	else
		return false;
}

/**
 * rand_string()
 * 产生随机字符串
 * 
 * @param integer $len 产生的随机字符串长度
 * @param integer $type 字符串类型0字母1数字2大写字母3小写字母4混合
 * @return String
 */
function rand_string($len = 6, $type = 0){
	$str = '';
	switch($type){
		case 0:
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			break;
		case 1:
			$chars = str_repeat('0123456789', 3);
			break;
		case 2:
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 3:
			$chars = 'abcdefghijklmnopqrstuvwxyz';
			break;
		default:
			// 默认去掉了容易混淆的字符oOLl和数字01
			$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
			break;
	}
	if($len > 10){ //位数过长重复字符串一定次数
		$chars = $type == 1?str_repeat($chars, $len):str_repeat($chars, 5);
	}
	$chars = str_shuffle($chars);
	$str = substr($chars, 0, $len);
	return $str;
}

/**
 * 把返回的数据集转换成Tree
 * 
 * @param mixed $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param integer $root
 * @return
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0){
	// 创建Tree
	$tree = array();
	if(is_array($list)){
		// 创建基于主键的数组引用
		$refer = array();
		foreach($list as $key => $data){
			$refer[$data[$pk]] = &$list[$key];
		}
		foreach($list as $key => $data){
			// 判断是否存在parent
			$parentId = $data[$pid];
			if($root == $parentId){
				$tree[] = &$list[$key];
			}else{
				if(isset($refer[$parentId])){
					$parent = &$refer[$parentId];
					$parent[$child][] = &$list[$key];
				}
			}
		}
	}
	return $tree;
}

/**
 * 对查询结果集进行排序
 * 
 * @param mixed $list
 * @param mixed $field
 * @param string $sortby
 * @return
 */
function list_sort_by($list, $field, $sortby = 'asc'){
	if(is_array($list)){
		$refer = $resultSet = array();
		foreach($list as $i => $data)
			$refer[$i] = &$data[$field];
		switch($sortby){
			case 'asc': // 正向排序
				asort($refer);
				break;
			case 'desc': // 逆向排序
				arsort($refer);
				break;
			case 'nat': // 自然排序
				natcasesort($refer);
				break;
		}
		foreach($refer as $key => $val)
			$resultSet[] = &$list[$key];
		return $resultSet;
	}
	return false;
}

/**
 * 在数据列表中搜索
 * 
 * @param mixed $list
 * @param mixed $condition
 * @return
 */
function list_search($list, $condition){
	if(is_string($condition))
		parse_str($condition, $condition);
			// 返回的结果集合
	$resultSet = array();
	foreach($list as $key => $data){
		$find = false;
		foreach($condition as $field => $value){
			if(isset($data[$field])){
				if(0 === strpos($value, '/')){
					$find = preg_match($value, $data[$field]);
				}elseif($data[$field] == $value){
					$find = true;
				}
			}
		}
		if($find)
			$resultSet[] = &$list[$key];
	}
	return $resultSet;
}

function list_to_string_by_field($list, $fields, $glue = ','){
	$array = array();
	foreach($list as $v){
		array_push($array, $v[$fields]);
	}
	return implode(',', $array);
}

/**
 * list_to_array
 * 把查询结果集中的指定字段转为一维数组
 * @param array $list 结果集
 * @param string $condition_key 要提取的字段
 * 
 * @return array
 */
function list_to_array($list, $condition_key){
	$result = array();
	foreach($list as $ary){
		foreach($ary as $k => $v)
			if($k == $condition_key){
				$result[] = $v;
			}
	}
	return $result;
}

/**
 * 字符串半角和全角间相互转换
 * @param string $str  待转换的字符串
 * @param int    $type  TODBC:转换为半角；TOSBC，转换为全角
 * @return string  返回转换后的字符串
 */
function convert_str_type($str, $type = 'TOSBC'){
	$dbc = array( // 全角
		'０', '１', '２', '３', '４', '５', '６', '７', '８', '９', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ', '－', '　', '：', '．', '，', '／', '％', '＃', '！', '＠', '＆', '（', '）', '＜', '＞', '＂', '＇', '？', '［', '］', '｛', '｝', '＼', '｜', '＋', '＝', '＿', '＾', '￥', '￣', '｀'
	);
	$sbc = array( // 半角
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '-', ' ', ':', '.', ',', '/', '%', ' #', '!', '@', '&', '(', ')', '<', '>', '"', '\'', '?', '[', ']', '{', '}', '\\', '|', '+', '=', '_', '^', '￥', '~', '`'
	);
	if($type == 'TODBC'){
		return str_replace($sbc, $dbc, $str); // 半角到全角
	}elseif($type == 'TOSBC'){
		return str_replace($dbc, $sbc, $str); // 全角到半角
	}else{
		return $str;
	}
}

/**
 * 获得指定时间的周一和周日时间戳
 * 
 * @param integer $timestamp
 * 
 * @return array 周一和周日时间戳
 */
function get_week_between($timestamp = null){
	// 如果没有传入时间，则取当前时间
	if($timestamp === null)
		$timestamp = time();
	$timestamp = strtotime(date('Y-m-d', $timestamp)); // 只取整时
	$bof_week = $timestamp - (date('w', $timestamp) - 1) * 86400;
	$eof_week = $timestamp - (date('w', $timestamp) - 7) * 86400;
	return array(
		$bof_week, $eof_week
	);
}

/**
 * 图片添加水印
 * 
 * @param string $img 背景图片(待加水印的图片)
 * @param string $watermark 水印图片地址
 * @param string $district 水印位置
 * @param integer $watermarkquality 图片水印质量
 * 
 * @return mixed
 */
function watermark($img, $watermark, $district = 0, $watermarkquality = 100){
	$imginfo = @getimagesize($img);
	$watermarkinfo = @getimagesize($watermark);
	$img_w = $imginfo[0];
	$img_h = $imginfo[1];
	$watermark_w = $watermarkinfo[0];
	$watermark_h = $watermarkinfo[1];
	if($district == 0)
		$district = rand(1, 9);
	if(!is_int($district) or 1 > $district or $district > 9)
		$district = 9;
	switch($district){
		case 1:
			$x = +5;
			$y = +5;
			break;
		case 2:
			$x = ($img_w - $watermark_w) / 2;
			$y = +5;
			break;
		case 3:
			$x = $img_w - $watermark_w - 5;
			$y = +5;
			break;
		case 4:
			$x = +5;
			$y = ($img_h - $watermark_h) / 2;
			break;
		case 5:
			$x = ($img_w - $watermark_w) / 2;
			$y = ($img_h - $watermark_h) / 2;
			break;
		case 6:
			$x = $img_w - $watermark_w;
			$y = ($img_h - $watermark_h) / 2;
			break;
		case 7:
			$x = +5;
			$y = $img_h - $watermark_h - 5;
			break;
		case 8:
			$x = ($img_w - $watermark_w) / 2;
			$y = $img_h - $watermark_h - 5;
			break;
		case 9:
			$x = $img_w - $watermark_w - 20;
			$y = $img_h - $watermark_h - 20;
			break;
	}
	switch($imginfo[2]){
		case 1:
			$im = @imagecreatefromgif($img);
			break;
		case 2:
			$im = @imagecreatefromjpeg($img);
			break;
		case 3:
			$im = @imagecreatefrompng($img);
			break;
	}
	switch($watermarkinfo[2]){
		case 1:
			$watermark_logo = @imagecreatefromgif($watermark);
			break;
		case 2:
			$watermark_logo = @imagecreatefromjpeg($watermark);
			break;
		case 3:
			$watermark_logo = @imagecreatefrompng($watermark);
			break;
	}
	if(!$im or !$watermark_logo)
		return false;
	$dim = @imagecreatetruecolor($img_w, $img_h);
	if(@imagecopy($dim, $im, 0, 0, 0, 0, $img_w, $img_h)){
		imagecopy($dim, $watermark_logo, $x, $y, 0, 0, $watermark_w, $watermark_h);
	}
	$file = dirname($img) . '/w' . basename($img);
	$result = imagejpeg($dim, $file, $watermarkquality);
	imagedestroy($watermark_logo);
	imagedestroy($dim);
	imagedestroy($im);
	if($result){
		return $file;
	}else{
		return false;
	}
}

function get_QR_from_google($url, $width_height = '120', $EC_level = 'L', $margin = '0'){
	$url = urlencode($url);
	return 'http://chart.apis.google.com/chart?chs=' . $width_height . 'x' . $width_height . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $url;
}

function list_group($list, $group_by){
	$result = array();
	//dump($list);
	foreach($list as $k => $v){
		$result[$v[$group_by]][] = $v;
	}
	return $result;
}

/**
 * 分词函数
 * 
 * @param unknown_type $string
 * @param unknown_type $limit
 * @param unknown_type $get_where
 */
function participle($string, $limit = 5, $get_where = FALSE){
	$so = scws_new();
	$so->set_multi(1);
	$so->send_text($string);
	$result = $so->get_tops($limit);
	$so->close();
	if($get_where){
		$where = "w_title LIKE '%{$string}%' OR ";
		foreach($result as $v){
			if($v['word'] != $string){ // 避免词重复
				$where .= "w_title LIKE '%{$v['word']}%' OR ";
			}
		}
		return substr($where, 0, strlen($where) - 4);
	}else{
		return $result;
	}
}

function get_current_url(){
	if(!empty($_SERVER["REQUEST_URI"])){
		$scriptName = $_SERVER["REQUEST_URI"];
		$nowurl = $scriptName;
	}else{
		$scriptName = $_SERVER["PHP_SELF"];
		if(empty($_SERVER["QUERY_STRING"]))
			$nowurl = $scriptName;
		else
			$nowurl = $scriptName . "?" . $_SERVER["QUERY_STRING"];
	}
	return $nowurl;
}

function set_url_value($key, $value, $url = NULL){
	if($url === NULL){
		$url = get_current_url();
	}
	
	$url_cutter = explode('?', $url);
	$url_b = $url_cutter[0];
	$url_e = $url_cutter[1];
	parse_str($url_e, $query);
	$query[$key] = $value;
	return $url_b.'?'.http_build_query($query);
}

/**
 * 字符串的长度
 * 
 * @param string $string
 * @param string $charset
 */
function str_length($string, $charset = 'utf-8'){
	if($charset == 'utf-8') $string = iconv('utf-8', 'gb2312', $string);
	$count = strlen($string);
	$cn_count = 0;
	for($i = 0; $i < $count; $i++){
		if(ord($string, $i+1, $i) > 127){
			$cn_count++;
			$i++;
		}
	}
	$en_count = $count - ($cn_count * 2);
	return (int)ceil(($en_count / 2) + $cn_count);
}
?>
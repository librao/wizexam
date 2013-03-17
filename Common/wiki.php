<?php
function get_wiki_details($d_id, $complex_wrapper_begin = '<div>', $complex_wrapper_over = '</div>'){
	$d_id = intval($d_id);
	
	// 查询详情信息
	$details_model = D('WikiDetailsView');
	$details_info = $details_model->where('d_id='.$d_id)->find();
	$d_other = unserialize($details_info['d_other']);
	
	$details = array();
	// 根据词条类型选择基本信息
	switch ($details_info['w_cate']){
		// 游戏类
		case '1':
			$details[] = array('title' => '中文名称', 'content' => $details_info['w_title']);
			$details[] = array('title' => '简称', 'content' => $details_info['d_alias_1']);
			$details[] = array('title' => '英文名称', 'content' => $details_info['d_alias_2']);
			$details[] = array('title' => '日文名称', 'content' => $details_info['d_alias_3']);
			$details[] = array('title' => '其它外文名称', 'content' => $details_info['d_alias_4']);
			$details[] = array('title' => '独占', 'content' => get_wiki_cate_info(31, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏类型', 'content' => get_wiki_cate_info(2, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏引擎', 'content' => $d_other['d_engine']);
			$details[] = array('title' => '游戏发售时间', 'content' => ymd_format($details_info['d_year'], $details_info['d_month'], $details_info['d_day']));
			$details[] = array('title' => '游戏发售价格', 'content' => $d_other['d_price']);
			$details[] = array('title' => '收费模式', 'content' => get_wiki_cate_info(7, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏语言版本', 'content' => get_wiki_cate_info(25, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏开发商', 'content' => get_wiki_cate_info(27, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏发行商', 'content' => get_wiki_cate_info(26, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏人数', 'content' => get_wiki_cate_info(30, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏官方编号', 'content' => $d_other['d_number']);
			$details[] = array('title' => '游戏支持分辨率', 'content' => get_wiki_cate_info(11, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏画面', 'content' => get_wiki_cate_info(6, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '题材、风格', 'content' => get_wiki_cate_info(8, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏载体', 'content' => get_wiki_cate_info(9, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏声音支持', 'content' => get_wiki_cate_info(10, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏分级', 'content' => get_wiki_cate_info(4, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏容量', 'content' => $d_other['d_capacity']);
			$details[] = array('title' => '游戏说明书', 'content' => get_wiki_cate_info(32, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏附赠物', 'content' => get_wiki_cate_info(33, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '网络支持', 'content' => get_wiki_cate_info(3, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '控制器震动支持', 'content' => get_wiki_cate_info(34, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '输入设备', 'content' => get_wiki_cate_info(23, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => 'FAMI评分', $details_info['d_fami']);
			$details[] = array('title' => 'GAMESPOT评分', $details_info['d_gamespot']);
			$details[] = array('title' => '游戏状态', 'content' => get_wiki_cate_info(5, $details_info['d_info'], $details_info['d_info_other']));
			$details[] = array('title' => '游戏官方网站', 'content' => $d_other['d_website']);
			$details[] = array('title' => '简介', 'content' => $complex_wrapper_begin.$details_info['d_intro'].$complex_wrapper_over, 'complex' => TRUE);
			$details[] = array('title' => '详情', 'content' => $complex_wrapper_begin.$details_info['d_content'].$complex_wrapper_over, 'complex' => TRUE);
			$details[] = array('title' => '来源（出处）', 'content' => $complex_wrapper_begin.$d_other['d_origin'].$complex_wrapper_over, 'complex' => TRUE);
			$details[] = array('title' => '修改原因', 'content' => $complex_wrapper_begin.$details_info['reason'].$complex_wrapper_over, 'complex' => TRUE);
			break;
	}
	//dump($details_info);
	return $details;
}

/**
 * 获得wiki的选项字段信息
 * 
 * @param string $cid
 * @param string $info
 * @param string $info_other
 * @param string $field
 */
function get_wiki_cate_info($cid, $info, $info_other, $field = 'c_name'){
	$cate_lists = get_wiki_static_cate_cache();
	$cate_lists = list_search($cate_lists, array('c_cid' => $cid));
	$cates = array();
	foreach ($cate_lists as $v){
		if(in_array($v['c_id'], explode(',', $info))){
			array_push($cates, $v[$field]);
		}
	}
	
	// other信息加入
	$info_other = unserialize($info_other);
	if(strlen($info_other[$cid]) > 0){
		array_push($cates, $info_other[$cid]);
	}
	return implode(',', $cates);
}

/**
 * 获取附件类型列表
 */
function get_attachment_type_list(){
	return array(
		array('attachment_type' => '1', 'title' => '图片'),
		array('attachment_type' => '2', 'title' => '视频'),
		array('attachment_type' => '4', 'title' => '资源')
	);
}

/**
 * 根据附件类型获取种类
 */
function get_attachment_cate($attachment_type, $attach_cate){
	$attachment_cate_list = C('STATIC.STATIC_ATTACH_CATE');
	$cate = list_search($attachment_cate_list, array('attachment_type' => $attachment_type));
	$cate = list_search($cate, array('attach_cate' => $attach_cate));
	return $cate[0]['title'];
}

/**
 * 获取平台组的平台id
 * 
 * @param string $group_title 组名称
 */
function get_platform_id_list($group_title){
	$cate_list = get_wiki_static_cate_cache();
	$platform_list = list_search($cate_list, 'c_cid=1');
	$result = list_search($platform_list, 'c_group_title='.$group_title);
	return list_to_string_by_field($result, 'c_id');
}
?>
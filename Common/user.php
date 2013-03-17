<?php

// 用户相关操作函数
/**
 * 获取用户的个人主页地址
 * 
 * @param int $uid 用户的uid
 */
function get_user_home_url($uid){
	$uid = intval($uid);
	return U('/i/' . $uid);
	//return 'javascript:void(0)';
}

/**
 * 判断用户是否是否管理员
 * 
 * @param int $uid 用户id
 * 
 * @return bool
 */
function is_admin($uid = NULL){
	// 如果没有指定用户id，则取当前登陆用户的uid
	if(is_null($uid)){
		$uid = intval(session('auth'));
	}else{
		$uid = intval($uid);
	}
	// 实时查询数据库，避免出现管理员已取消但是用户仍然具有管理员权限的问题
	$model = M('Users');
	if((bool)$model->where('uid = ' . $uid)->getField('is_admin')){
		return TRUE;
	}else{
		return FALSE;
	}
}

/**
 * 判断用户是否已登陆
 * 
 * @return bool
 */
function is_login(){
	// 根据session判断用户是否已登陆
	if(is_null(session('auth'))){
		return FALSE;
	}else{
		return TRUE;
	}
}

/**
 * 根据状态码获取用户状态
 * 
 * @param int $status_code 状态码
 * 
 * @return string
 */
function get_user_status_by_code($status_code){
	$status_code = intval($status_code);
	switch($status_code){
		case 1:
			return '正常';
		case 2:
			return '封禁';
		case 3:
			return '删除';
	}
}

/**
 * 获得用户创建词条数量
 * 
 * @param int $uid
 */
function get_user_create_wiki_count($uid){
	$uid = intval($uid);
	$model = M('Wiki');
	return $model->where('author_id=' . $uid . '&w_status=1')->count();
}

/**
 * 获得当前用户uid
 */
function get_uid(){
	return intval(session('auth'));
}

/**
 * 根据area_id返回用户地区
 * 
 * @param int $area_id
 */
function get_user_area($area_id, $type = '1', $delimiter = '-'){
	if(intval($area_id) == 0) return '';
	switch ($type){
		case 1:
			return get_area_title($area_id, 1);
			break;
		case 2:
			return get_area_title($area_id, 1).$delimiter.get_area_title($area_id, 2);
			break;
	}
}
?>
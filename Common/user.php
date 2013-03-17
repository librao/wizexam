<?php

// 用户相关操作函数
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
 * 获得当前用户uid
 */
function get_uid(){
	return intval(session('auth'));
}
?>
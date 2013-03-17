<?php
/**
 * 获得群组分类
 * 
 * @param int $pid
 * 
 * @return array
 */
function get_group_cate($pid = null){
	$group_cate_list = get_group_cate_cache();
	
	// 如果pid是null，代表获取顶级分类
	if($pid === NULL){
		$group_cate_list = list_search($group_cate_list, array('cat_pid' => '0')); // 顶级分类
	}else{
		$group_cate_list = list_search($group_cate_list, array('cat_pid' => $pid));
	}
	
	$group_cate_list = list_sort_by($group_cate_list, 'cat_sort', 'desc'); // 先按照sort排序
	$group_cate_list = list_sort_by($group_cate_list, 'cat_id', 'asc'); // 再按照id排序
	
	return $group_cate_list;
}

/**
 * 根据id获得群组分类指定字段
 * 
 * @param int $cat_id 群组分类id
 * @param string $field 要获取的字段
 * 
 * @return string
 */
function get_groupCateField_by_id($cat_id, $field = 'cat_title'){
	$cat_id = intval($cat_id);
	
	$cate_list = get_group_cate_cache();
	$cate = list_search($cate_list, array('cat_id' => $cat_id));
	return $cate[0][$field];
}

/**
 * 判断指定群组分类是否顶级分类
 * 
 * @param int $cat_id 群组分类id
 * 
 * @return bool
 */
function is_groupCateRoot($cat_id){
	if(get_groupCateField_by_id($cat_id, 'cat_pid') === '0'){
		return TRUE;
	}else{
		return FALSE;
	}
}

/**
 * 根据群组分类id获得上级分类指定字段
 * 
 * @param int $cat_id
 * @param string $field
 * 
 * @return string
 */
function get_groupParentField_by_id($cat_id, $field = 'cat_title'){
	// 获得上级id
	$pid = intval(get_groupCateField_by_id($cat_id, 'cat_pid'));
	
	return get_groupCateField_by_id($pid, $field);
}

	
/**
 * 用户加入群组
 * 
 * @param int $group_id
 * @param int $uid
 * @param int $role 身份0未验证1普通用户2管理员3创建者
 */
function add_user_to_group($group_id, $uid, $role){
	$model = M('GroupMember');
	
	// 清空原数据
	$model->where("uid=%d AND gid=%d", $uid, $group_id)->delete();
	
	// 重新插入成员数据
	$data['uid'] = intval($uid);
	$data['gid'] = intval($group_id);
	$data['role'] = intval($role);
	$data['create_datetime'] = time();
	
	if($model->add(($data))){
		return TRUE;
	}else{
		return FALSE;
	}
}

/**
 * 用户退出群组
 * 
 * @param int $group_id
 * @param int $uid
 */
function del_user_to_group($group_id, $uid){
	$model = M('GroupMember');
	$model->where("uid=%d AND gid=%d AND (role IN (0,1,2))", $uid, $group_id)->delete();
}

/**
 * 判断用户是否某小组组员
 * 
 * @param int $uid
 * @param int $group_id
 */
function is_user_in_group($uid, $group_id){
	if($uid == NULL) return FALSE;
	$model = M('GroupMember');
	$count = $model->where("uid=%d AND gid=%d AND (role IN (1,2,3))", $uid, $group_id)->count();
	if($count == 0){
		return FALSE;
	}else{
		return TRUE;
	}
}

/**
 * 设置小组TAG
 * 
 * @param int $group_id
 * @param string $tag_string
 */
function set_group_tag($group_id, $tag_string){
	// 格式化tag数据
	$tag_string = trim($tag_string);
	$tag_string = strtolower($tag_string);
	$tag_string = str_replace('，', ',', $tag_string); // 替换全角分隔号
	$tag_string = str_replace(' ', ',', $tag_string);
	$tag_string = explode(',', $tag_string);
	
	// 删除小组原来的TAG
	$group_tag_model = M('GroupHasTag');
	$group_tag_model->where('group_id=%d', $group_id)->delete();
	
	// 遍历tag数组并插数据库
	$tag_model = M('Tag');
	$data = array();
	$tag_mark = array();
	foreach ($tag_string as $v){
		$v = trim($v);
		if(strlen($v) === 0) continue; // 如果为空自动跳过
		else{
			$row = array();
			$tag = $tag_model->field('tag_id')->where("tag_title='%s'", $v)->find();
			// 如果没有找到已存在tag，则创建新tag
			if($tag == NULL){
				$tag_data = array();
				$tag_data['tag_title'] = $v;
				$tag_data['create_datetime'] = time();
				$tag_id = $tag_model->add($tag_data);
			}else{
				$tag_id = $tag['tag_id'];
			}
			
			// 判断tag是否已存在
			if(!in_array($tag_id, $tag_mark)){
				array_push($tag_mark, $tag_id);
				$row['group_id'] = intval($group_id);
				$row['tag_id'] = intval($tag_id);
				$data[] = $row;
			}
		}
	}
	
	// 批量插入
	$group_tag_model->addAll($data);
}

/**
 * 获得小组创建人uid
 * 
 * @param unknown_type $group_id
 */
function get_group_founder_uid($group_id){
	$model = M('Group');
	$uid = intval($model->where("group_id=%d", $group_id)->getField('uid'));
	return $uid?intval($uid):FALSE;
}

/**
 * 判断群组gcid是否存在
 * 
 * @param int $gcid
 */
function is_gcid_exist($gcid){
	if(count(get_group_cate($gcid)) === 0){
		return FALSE;
	}else{
		return TRUE;
	}
}

/**
 * 验证小组是否合法
 * 
 * @param int $group_id
 */
function valid_group($group_id){
	$model = M('Group');
	$count = $model->where('group_id=%d AND group_status=1', $group_id)->count();
	
	if(intval($count) !== 1){
		return FALSE;
	}else{
		return TRUE;
	}
}

/**
 * 获得小组标签
 * 
 * @param int $group_id 小组id
 */
function get_group_tag($group_id){
	$model = D('GroupTagView');
	return list_to_array($model->field('tag_id,tag_title')->where("group_id=%d", $group_id)->select(), 'tag_title');
}

/**
 * 获得群组排序数据
 * 
 * @param string $type
 */
function get_group_sort($type = 'topicList'){
	switch ($type){
		case 'topicList':
			return array(
				'1' => '最新回复',
				'2' => '最新发表',
				'3' => '浏览最多',
				'4' => '回复最多'
			);
	}
}

/**
 * 判断当前用户是否有帖子编辑权限
 * 
 * @param int $post_id
 */
function is_can_edid_post($post_id){
	$post_model = M('GroupPosts');
	$post_info = $post_model->where('post_id=%d AND post_status=1', $post_id)->field('post_id,post_author,group_id')->find();
	// 作者可编辑
	if($post_info['post_author'] == session('auth')){
		return TRUE;
	}else{
		$admin_array = get_group_admin_uid($post_info['group_id']);
		if(in_array(session('auth'), $admin_array)) return TRUE;
		else return FALSE;
	}
}

/**
 * 获取群组全部管理员uid
 * @param int $group_id
 * @return array
 */
function get_group_admin_uid($group_id){
	$model = M('GroupMember');
	$list = $model->where('gid=%d AND role IN (2,3)', $group_id)->field('uid')->select();
	return list_to_array($list, 'uid');
}
?>
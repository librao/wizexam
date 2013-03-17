<?php
/**
 * 根据id获得分类项名称
 * @param string $id
 * @param string $field 要获取的字段
 * 
 * @return String
 */
function get_catename_by_id($id, $field = 'c_name'){
	$cate = get_wiki_static_cate_cache();
	$result = list_search($cate, array('c_id' => $id));
	return $result[0][$field];
}
?>
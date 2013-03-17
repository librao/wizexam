<?php
/**
 * 建立Admin管理菜单结点缓存
 * 
 * @category Cache
 */
function build_menu_node_cache(){
	$model = M('MenuNode');
	$list = $model->order('pid asc, ordernum asc')->select();
	S('DB_Menu_Node_CACHE', $list);
}

/**
 * 获得Admin管理菜单结点缓存
 * 
 * @category Cache
 */
function get_menu_node_cache(){
	if(S('DB_Menu_Node_CACHE') == FALSE) build_menu_node_cache();
	return S('DB_Menu_Node_CACHE');
}

/**
 * 建立Wiki分类数据缓存
 * 
 * @category Cache
 */
function build_wiki_categories_cache(){
	$model = M('WikiCategories');
	$list = $model->order('c_id asc')->select();
	S('DB_WIKI_CATEGORIES_CACHE', $list);
}

/**
 * 获得Wiki分类缓存
 * 
 * @category Cache
 */
function get_wiki_categories_cache(){
	if(S('DB_WIKI_CATEGORIES_CACHE') == FALSE) build_wiki_categories_cache();
	return S('DB_WIKI_CATEGORIES_CACHE');
}

/**
 * 重建Wiki选项缓存
 */
function build_wiki_static_cate_cache(){
    $model = M('WikiCate');
    $list = $model->order('CONVERT(c_name USING gbk) COLLATE gbk_chinese_ci ASC')->select();
    S('DB_WIKI_CATE_CACHE', $list);
}

/**
 * 获取Wiki选项缓存
 */
function get_wiki_static_cate_cache(){
    if(S('DB_WIKI_CATE_CACHE') == false) build_wiki_static_cate_cache();
    return S('DB_WIKI_CATE_CACHE');
}

/**
 * 建立群组分类缓存
 */
function build_group_cate_cache(){
	$model = M('GroupCategories');
	$list = $model->select();
	S('DB_GROUP_CATE_CACHE', $list);
}

/**
 * 获取群组分类缓存
 */
function get_group_cate_cache(){
	if(S('DB_GROUP_CATE_CACHE') == false) build_group_cate_cache();
	return S('DB_GROUP_CATE_CACHE');
}

/**
 * 建立地区缓存
 */
function build_area_static_cache(){
	$model = M('Area');
	$list = $model->select();
	S('DB_AREA_CACHE', $list);
}

/**
 * 获取地区缓存
 */
function get_area_static_cache(){
	if(S('DB_AREA_CACHE') == false) build_area_static_cache();
	return S('DB_AREA_CACHE');
}
?>
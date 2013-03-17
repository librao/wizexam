<?php
function get_entries_nav_class($action){
	if(strpos(strtolower(ACTION_NAME), $action) !== false){
	   return 'current';
	}
}

/**
 * 获得附件类型选择框
 *
 * @param string $wiki_type
 * @param string $attachment_type
 */
function output_wikiAttachmentType_radio($wiki_type, $attachment_type){
	$attachment_type_list = C('STATIC.STATIC_ATTACH_CATE');
	$attachment_type_list = list_search($attachment_type_list, 'wiki_type='.$wiki_type);
	$attachment_type_list = list_search($attachment_type_list, 'attachment_type='.$attachment_type);
	
	$html = '';
	$checked_mark = FALSE;
	foreach ($attachment_type_list as $v){
		if(!$checked_mark){
			$html .= '<label class="radio inline"><input name="attach_cate" type="radio" checked="checked" class="form-radio" value="'.$v['attach_cate'].'">'.$v['title'].'</label>';
			$checked_mark = TRUE;
		}
		else $html .= '<label class="radio inline"><input name="attach_cate" type="radio" class="form-radio" value="'.$v['attach_cate'].'">'.$v['title'].'</label>';
	}
	return $html;
}

/**
 * 首页顶部平台最新入库html
 *
 * @param unknown_type $list 数据集
 * @param unknown_type $substr 截断字符数
 */
function output_home_platform_list($list, $substr = 10){
	$html .= '<ul class="clearfix unstyled">';
	foreach ($list as $v){
		$html .= '<li class="stbody">';
		$html .= '<a class="stimg" href="/entries/'.$v['w_id'].'" title="'.$v['w_title'].'"><img src="'.get_image_default($v['front_cover'], 'wiki').'-w130" alt="'.$v['w_title'].'" /></a>';
		$html .= '<div class="sttext">';
		$html .= '<h3 class="title">'.msubstr($v['w_title'], 0, $substr, FALSE);
		// 判断是否需要显示年份
		if(strlen($v['d_year']) > 0){
			$html .= '<small>('.$v['d_year'].')</small>';
		}
		$html .= '</h3>';
		$html .= '<div class="des">[<a href="javascript:void(0)">'.get_cate_name_by_cid($v['platform_id'], 'c_alias').'</a>]';
		// 网络
		if(strlen(get_wiki_cate_info(3, $v['d_info'], '')) > 0){
			$html .= '|<a href="javascript:void(0)">'.get_wiki_cate_info(3, $v['d_info'], '').'</a>';
		}
		// 游戏类型
		if(strlen(get_wiki_cate_info(2, $v['d_info'], '')) > 0){
			$html .= '|<a href="javascript:void(0)">'.get_wiki_cate_info(2, $v['d_info'], '').'</a>';
		}
		$html .= '</div></div></li>';
	}
	$html .= '</ul>';
	return $html;
}

/**
 * 输出词条类型OPTION项
 * 
 * @param mixed $select
 * @return
 */
function output_wikicate_option($select = null){
    $options = C('STATIC.STATIC_WIKI_CATE');
    $html = '';
    foreach($options as $k => $v){
        if($select !== null && $select == $k) $html .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
        else $html .= '<option value="'.$k.'">'.$v.'</option>';
    }
    return $html;
}

function output_catename_option($select = null){
    $options = C('STATIC.STATIC_CATE_NAME');
    $html = '';
    foreach($options as $v){
        if($select !== null && $select == $v['id']) $html .= '<option value="'.$v['id'].'" selected="selected" class="'.$v['c_cate'].'">'.$v['title'].'</option>';
        else $html .= '<option value="'.$v['id'].'" class="'.$v['c_cate'].'">'.$v['title'].'</option>';
    }
    return $html;
}

function output_admin_cate_tab($cate_id = null){
    $tabs = C('STATIC.STATIC_WIKI_CATE');
    $html = '';
    foreach($tabs as $k => $v){
        if($cate_id !== null && $cate_id == $k) $html .= '<li><a href="'.U('Admin/Wiki/cate?cid='.$k).'" class="on">'.$v.'</a></li>';
        else $html .= '<li><a href="'.U('Admin/Wiki/cate?cid='.$k).'">'.$v.'</a></li>';
    }
    return $html;
}

function output_wiki_game_platform_option($select = null){
    $cate_list = get_wiki_static_cate_cache();
    $platform_list = list_search($cate_list, array('c_cid' => '1'));
    $html = '';
    foreach($platform_list as $v){
        $html .= '<option value="'.$v['c_id'].'">['.$v['c_group_title'].']'.$v['c_name'].'</option>';
    }
    return $html;
}

function output_wiki_game_cate_option($select = null){
    $cate_list = get_wiki_static_cate_cache();
    $options = list_search($cate_list, array('c_cid' => '2'));
    $html = '';
    foreach($options as $option){
        $html .= '<option value="'.$option['c_id'].'">'.$option['c_alias'].$option['c_name'].'</option>';
    }
    return $html;
}

/**
 * 
 * 根据词条分类id获得option选项
 * 
 * @param integer $cid
 * @param integer $type
 * @param boolean $has_empty_select 是否含有空option项
 * @param integer $select
 * @param boolean $group 是否开启分组
 */
function output_option_with_cate_cid($cid = null, $select = null, $has_empty_select = TRUE, $group = FALSE){
    $cate_list = get_wiki_static_cate_cache();
    
    // 过滤，排序
    $options = list_search($cate_list, array('c_cid' => $cid));
    $options = list_sort_by($options, 'c_sort', 'desc');
    
    $html = '';
    
    if($group){
    	// 分组模式
    	$optgroup_array = array();
    	foreach ($options as $option){
    		if(!array_key_exists($option['c_group_title'], $optgroup_array)){
    			$optgroup_array[$option['c_group_title']] = array(); 
    		}
    		$optgroup_array[$option['c_group_title']][] = array($option['c_id'], $option['c_name']);
    	}
    	
    	$mark_group = array();
    	$count = 0;
    	foreach ($optgroup_array as $group_title => $opt_group){
    		if(!in_array($group_title, $mark_group)){
    			$mark_group[] = $group_title;
    			if($count !== 0){
    				$html .= '</optgroup>';
    			}
    			$html .= '<optgroup label="'.$group_title.'">';
    			$count++;
    		}

    		foreach ($opt_group as $option){
    			if($select !== null && in_array($option[0], explode(',', $select))){
    				$html .= '<option value="'.$option[0].'" selected="selected">'.$option[1].'</option>';
    			}else{
    				$html .= '<option value="'.$option[0].'">'.$option[1].'</option>';
    			}
    		}
    	}
    	$html .= '</optgroup>';
    }else{
    	// 普通模式
    	foreach($options as $option){
			if($select !== null && in_array($option['c_id'], explode(',', $select))) $html .= '<option value="'.$option['c_id'].'" selected="selected">'.$option['c_name'].'</option>';
			else $html .= '<option value="'.$option['c_id'].'">'.$option['c_name'].'</option>';
    	}
    }
    
    // 判断是否需要在首项目加入空option项
    if($has_empty_select){
    	$html = '<option value=""></option>'.$html;
    }
    
    return $html;
}

/**
 * Wiki表单Other输入框
 * 
 * @param integer $c_cid
 */
function out_put_wiki_other($c_cid){
	return '<input type="text" name="other_'.$c_cid.'" placeholder="'.C('STATIC.STATIC_TEXT_WIKI_OTHER').'" class="form-text input-xlarge"/>';
}

/**
 * 获得商品购买地址的URL编码

 * @param unknown_type $search 搜索的内容
 * @param unknown_type $type 跳转的网站
 */
function get_buy_url($search, $type){
	$link = '';
	switch ($type){
		case 'taobao':
			$link .= 'http://s.taobao.com/search?q=';
			break;
		case 'paipai':
		case 'amazon_com':
			$link .= 'http://www.amazon.com/s/field-keywords=';
			break;
		case 'amazon_cn':
			$link .= 'http://www.amazon.cn/s/field-keywords=';
			break;
		case 'yahoo':
			$link .= 'http://auctions.search.yahoo.co.jp/search?p';
	}
	return $link .= urlencode($search);
}

/**
 * 用户个人页面导航菜单
 * @param string $type
 * 
 * @return String
 */
function out_put_ucenter_nav($type){
	$html = '<div class="accordion ptm">';
	// 读取菜单数据
	$nav_array = C('NAV_'.strtoupper($type).'_MENU');
	
	// 菜单生成
	foreach ($nav_array as $accordion_group){
		$class = array();
		$class[] = 'accordion-group';
		$items_count = count($accordion_group['items']); // 统计子栏目数量
		if($items_count === 0) $class[] = 'accordion-no-body'; // 菜单没有子项
		if(in_array(strtolower(ACTION_NAME), explode(',', strtolower($accordion_group['module'])))) $class[] = 'sHover'; // 当前项高亮
		if(strlen($accordion_group['class']) > 0) $class[] = $accordion_group['class']; // 附加类名
		$class = implode(' ', $class);
		$html .= '<div class="'.$class.'">'; // 组开始
		if($items_count === 0){ // 独立组
			$html .= '<h2 class="accordion-heading"><a href="'.U($accordion_group['link']).'" title="'.$accordion_group['title'].'">'.$accordion_group['title'].'</a></h2>';
		}else{
			$html .= '<h2 class="accordion-heading">'.$accordion_group['title'].$accordion_group['sub_link'].'</h2>';
			$html .= '<div class="accordion-body"><ul class="unstyled">';
			$i = 1;
			foreach ($accordion_group['items'] as $item){
				$class = array();
				if($i === 1) $class[] = 'top';
				elseif($i === $items_count) $class[] = 'bottom';
				if(strtolower(ACTION_NAME) === strtolower($item['module'])) $class[] = 'current';
				
				$class = implode(' ', $class);
				if(strlen($class) !== 0) $class = ' class="'.$class.'"';
				
				$html .= '<li'.$class.'><a href="'.U($item['link']).'" title="'.$item['title'].'">'.$item['title'].'</a></li>';
				$i++;
			}
			$html .= '</ul></div>';
		}
		$html .= '</div>'; // 组结束
	}
	return $html;
}

/**
 * 群组导航HTML部分
 */
function out_put_group_nav(){
	$html .= '<div id="navigation" class="js_group_nav clearfix"><ul id="nav" class="unstyled fl">';
	if(strtolower(ACTION_NAME) === 'index'){
		$html .= '<li class="active"><a href="'.U('/community').'" title="社区首页">社区首页</a></li>';
	}else{
		$html .= '<li><a href="'.U('/community').'" title="社区首页">社区首页</a></li>';
	}
	
	$group_type_list = C('STATIC.STATIC_GROUP_TYPE');
	foreach ($group_type_list as $gcid => $group_title){
		$class = ' class="js_group_nav"';
		if(intval($_GET['gcid']) === $gcid){
			$class = ' class="active js_group_nav"';
		}
		$html .= '<li'.$class.'>';
		$html .= '<a href="'.U('/Group/gindex?gcid='.$gcid).'" title="'.$group_title.'">'.$group_title.'</a>';
		$html .= '<ul class="unstyled clearfix sub-menu">';
		$html .= '<li><a href="'.U('/Group/groups?gcid='.$gcid).'" title="浏览小组">浏览小组</a></li>';
		$html .= '<li><a href="'.U('/Group/topicsList?gcid='.$gcid).'" title="浏览话题">浏览话题</a></li>';
		//$html .= '<li><a href="'.U('/Group/Official?gcid='.$gcid).'" title="官方小组">官方小组</a></li>';
		$html .= '</ul>';
		$html .= '</li>';
	}
	
	$html .= '</ul><a id="create-group" class="fr" href="'.U('My/createGroup').'">创建小组</a></div>';
	return $html;
}

/**
 * 全局导航HTML部分
 */
function out_put_global_nav(){
	$html = '<ul id="nav-menu" class="nav nav-menu clearfix fr unstyled">';
	$navs = C('NAV_GLOBAL_NAV');
	foreach ($navs as $nav){
		// 只显示有链接参数的导航
		if(strlen($nav['link']) !== 0){
			$html .= '<li><a href="'.U($nav['link']).'" class="'.$nav['class'].'" title="'.$nav['title'].'"><i class="icon"></i><span>'.$nav['title'].'</span></a></li>';
		}
	}
	$html .= '</ul>';
	return $html;
}

function output_imageAttachment_html($list){
	$attachment_type = 1;
	$html = '';
	foreach ($list as $cate_id => $cate_list){
		if(count($cate_list) == 0) continue; // 如果该分类没有图片,直接跳过
		
		$big_images_count = 1;
		$img_list_count = 5;
		$html .= '<div class="sub-block">';
		$html .= '<h4 class="sub-block-title">'.get_attachment_cate($attachment_type, $cate_id).'</h4>';
		$html .= '<div class="sub-block-content"><ul class="unstyled game-image-info">';
		
		foreach ($cate_list as $attachment){
			if($big_images_count != 0){
				$html .= '<li class="stbody">';
				$html .= '<a class="stimg" href="javascript:void(0)" title="'.$attachment['attachment_title'].'"><img src="'.$attachment['url'].'-w960" /></a>';
				$html .= '</li>';
				if($big_images_count == 1){
					$html .= '</ul>';
					$html .= '<h5 class="title">全部'.get_attachment_cate($attachment_type, $cate_id).'</h5>';
					$html .= '<ul class="unstyled game-alls">';
				}
				$big_images_count--;
			}else{
				if($img_list_count > 0){
					$html .= '<li class="stbody">';
					$html .= '<a class="stimg" href="javascript:void(0)" title="'.$attachment['attachment_title'].'"><img src="'.$attachment['url'].'-w200" /></a>';
					$html .= '<div class="sttext">';
					$html .= '<span class="view">'.$attachment['view_count'].'</span>';
					$html .= '<span class="comments">'.$attachment['comment_count'].'</span>';
					$html .= '<span class="like">'.$attachment['favorite_count'].'</span>';
					$html .= '</div>';
					$html .= '</li>';
					$img_list_count--;
				}else{
					$html .= '</ul>';
					break;
				}
			}
		}
		$html .= '</div>';
		$html .= '</div>';
	}
	return $html;
}
/*
        <div class="sub-block" id="game-cover-block">
            <h4 class="sub-block-title">游戏封面</h4>

            <div class="sub-block-content">
                <ul class="unstyled game-image-info">
                    <li class="stbody">
                        <a class="stimg" href="#"><img src="/pic/935x620" alt=""></a>
                        <dl class="sttext">
                            <dt>图片信息：</dt>
                            <dd>2012-02-12由<a href="" class="author">Admin</a>上传，查看次数：<a
                                    href="#">2535877</a>,大小：<a href="#">2.7MB</a>、尺寸：<a href="#">1440px*980px</a>、评论：<a
                                    href="#">869</a>、赞：<a href="#">669</a></dd>
                        </dl>
                    </li>
                </ul>
                <h5 class="title">全部封面</h5>
                <ul class="unstyled game-alls ">
                    <li class="stbody">
                        <a class="stimg" href=""><img src="/pic/120x75" alt=""></a>

                        <div class="sttext">
                            <a class="view" href="#">362</a>
                            <a class="comments" href="#">25</a>
                            <a class="like" href="#">52</a>
                        </div>
                    </li>
                </ul>
                <div class="more-links"><a href="#">[查看所有<span>18</span>]张封面</a></div>
            </div>
        </div>
*/

function output_videoAttachment_html($list){
	$attachment_type = 2;
	foreach ($list as $cate_id => $cate_list){
		$html .= '<div class="sub-block">';
		$html .= '<h4 class="sub-block-title">'.get_attachment_cate($attachment_type, $cate_id).'</h4>';
		$count = 1;
		$list_count = 5;
		$cate_count = count($cate_list);
		foreach ($cate_list as $attachment){
			if($count !== 0){
				if($attachment_type == 2){ // 如果是视频,加入播放器
					$html .= output_flash_video($attachment['info']['swf']);
					$html .= '<dl class="sttext">';
					$html .= '<dd>'.date('Y-m-d', $attachment['create_datetime']).'由<a href="/i/'.$attachment['uid'].'" class="author">'.$attachment['user_nickname'].'</a>上传，查看次数：'.$attachment['view_count'].',评论：'.$attachment['comment_count'].'、收藏：'.$attachment['favorite_count'].'</dd>';
					$html .= '</dl>';
					// 判断是否最后一个
					if($count === 1){
						$html .= '<div class="sub-block-content"><ul class="unstyled clearfix game-alls">';
					}
				}
				$count--;
			}else{
				if($list_count > 0){
					$html .= '<li class="stbody">';
					$html .= '<a class="stimg" href="javascript:void(0)" title="'.$attachment['attachment_title'].'"><img src="'.$attachment['url'].'" /></a>';
					$html .= '<div class="sttext">';
					$html .= '<span class="view">'.$attachment['view_count'].'</span>';
					$html .= '<span class="comments">'.$attachment['comment_count'].'</span>';
					$html .= '<span class="like">'.$attachment['favorite_count'].'</span>';
					$html .= '</div>';
					$html .= '</li>';
					$list_count--;
				}else{ // 循环结束
					$html .= '</ul>';
					break;
				}
			}
		}
		$html .= '</div>';
		$html .= '<div class="more-links"><a href="/entries/'.$_GET['w_id'].'/video">[查看所有<span>'.count($cate_list).'</span>个]'.get_attachment_cate($attachment_type, $cate_id).'</a></div>';
		$html .= '</div>';
	}
	return $html;
}

/**
 * 视频html代码
 * Enter description here ...
 * @param string $url
 * @param string $auto_play
 * @param integer $width
 * @param integer $height
 * @param string $id
 */
function output_flash_video($url, $auto_play = FALSE, $width = 500, $height = 400, $id = 'show_flash_div_flash'){
	$html = '<div style="text-align:center" class="sub-block-content">';
	$html .= '<object width="'.$width.'" height="'.$height.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="'.$id.'">';
	$html .= '<param value="'.$url.'" name="movie">';
	$html .= '<param value="Opaque" name="wmode"><param value="true" name="allowFullScreen">';
	$html .= '<param value="true" name="menu">';
	if($auto_play){
		$html .= '<param value="autoPlay=true&isAutoPlay=true&auto=1" name="FlashVars">';
	}else{
		$html .= '<param value="autoPlay=false&isAutoPlay=false&auto=0" name="FlashVars">';
	}
	$html .= '<param value="sameDomain" name="allowscriptaccess">';
	if($auto_play){
		$html .= '<embed width="'.$width.'" height="'.$height.'" allowfullscreen="true" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" menu="true" flashvars="autoPlay=true&isAutoPlay=true&auto=1" wmode="Opaque" src="'.$url.'" name="'.$id.'">';
	}else{
		$html .= '<embed width="'.$width.'" height="'.$height.'" allowfullscreen="true" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" menu="true" flashvars="autoPlay=false&isAutoPlay=false&auto=0" wmode="Opaque" src="'.$url.'" name="'.$id.'">';
	}
	$html .= '</object>';
	$html .= '</div>';
	return $html;
}
?>
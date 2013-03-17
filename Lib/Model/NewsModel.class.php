<?php

class NewsModel extends Model {

    const STATUS_ACTIVE = 'ACTIVE';

	/**
	 * 获得最新新闻
	 * 
	 * @param int $id	新闻ID
	 */
	public function getDetail($id){
		$where['id'] = array('eq', $id);
		$newsinfo = $this->where($where)->find();
		return $newsinfo;
	}

}

?>

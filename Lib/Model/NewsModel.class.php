<?php

class NewsModel extends Model {

    const STATUS_ACTIVE = 'ACTIVE';

protected $_auto = array(
        array('status', self::STATUS_ACTIVE),
        array('created_at', 'getCurrentDateTime', Model::MODEL_INSERT, 'function'),
        array('updated_at', 'getCurrentDateTime', Model::MODEL_BOTH, 'function')
    );

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
	protected $_auto = array(
        array('status', self::STATUS_ACTIVE),
        array('created_at', 'getCurrentDateTime', Model::MODEL_INSERT, 'function'),
        array('updated_at', 'getCurrentDateTime', Model::MODEL_BOTH, 'function')
    );

}

?>

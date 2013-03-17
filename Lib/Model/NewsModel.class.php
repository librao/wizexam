<?php

class NewsModel extends Model {

    const STATUS_ACTIVE = 'ACTIVE';

    // 自动验证
    protected $_validate = array(
        array('title', 'require', '标题必须填写', 1, 'regex', 1),
        array('content', 'require', '内容必须填写', 1, 'regex', 1)
    );
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
    public function getDetail($id) {
        $where['id'] = array('eq', $id);
        $newsinfo = $this->where($where)->find();
        return $newsinfo;
    }

}

?>

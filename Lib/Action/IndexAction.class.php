<?php

class IndexAction extends Action {

    public function index() {
        $news_model = M('News');
        $where['status'] = array('eq', NewsModel::STATUS_ACTIVE);
        $list = $news_model->where($where)->order('created_at desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

}
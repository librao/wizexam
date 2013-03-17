<?php

class AdminAction extends Action {

    public function index() {
		$news_model = M('News');
        $where['status'] = array('eq', NewsModel::STATUS_ACTIVE);
        $list = $news_model->where($where)->order('created_at desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

	public function edit() {
        $this->display();
    }

    public function insert() {
        $model = D('News');
        $date = $model->create();
        if ($date) {
            if ($model->add()) {
                $this->success('操作成功');
            } else {
                $this->error('发布失败');
            }
        } else {
            $this->error('请填写带*内容');
        }
    }

}

?>

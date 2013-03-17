<?php

class AdminAction extends Action {

    public function index() {
        $news_model = M('News');
        $where['status'] = array('eq', NewsModel::STATUS_ACTIVE);
        $list = $news_model->where($where)->order('id desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function add() {
        $this->assign('action', U('Admin/insert'));
        $this->display('form');
    }

    public function edit() {
        $model = D('News');
        $news = $model->getDetail(intval($_REQUEST['id']));
        if ($news) {
            $this->assign('news', $news);
            $this->assign('action', U('Admin/update'));
            $this->display('form');
        } else {
            $this->error('Could not found news!');
        }
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

    public function update() {
        
    }

    public function delete() {
        $id = intval($_REQUEST['id']);
    }

}

?>

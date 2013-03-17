<?php

class IndexAction extends Action {
	public $n_id;
	public $n_title;

    public function index() {
        $news_model = M('News');
        $where['status'] = array('eq', NewsModel::STATUS_ACTIVE);
        $list = $news_model->where($where)->order('created_at desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

	/**
	 * 新闻祥情页
	 */
	public function detail() {
		$news_model = new NewsModel();

		$_GET['n_id'] = intval($_GET['n_id']);
		$this->n_id = (isset($_GET['n_id']) && $_GET['n_id'] != null)?$_GET['n_id']:null;
		
		$news_info = $news_model->getDetail($this->n_id);

		$links = D('link');
		$where['news_id'] = $this->n_id;
		$link_list = $links->where($where)->select();

		//dump($news_info);
		$this->assign('news_info', $news_info);
		$this->assign('link_list', $link_list);
		$this->assign('n_id', $this->n_id);
		$this->display();
	}

	/**
	 * 新闻Like操作
	 */
	public function addLike() {
		$news_model = new NewsModel();
		$id = !empty($_POST['n_id']) ? $_POST['n_id'] : '';
		if(IS_AJAX){//判断ajax请求
			$data['like'] = array('eq', +1);
			$where['id'] = array('eq', $id);
			if ($this->where($where)->save()) {
				$this->ajaxReturn($result,"操作成功",1);
			}else{
				$this->ajaxReturn(0,"操作失败",0);
			}
        }
	}

}
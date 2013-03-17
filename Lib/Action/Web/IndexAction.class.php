<?php
class IndexAction extends GlobalAction {
	public $n_id;
	public $n_title;

	/**
	 * 控制器初始化
	 * 
	 * @return void
	 */
	public function _initialize() {
		parent::_initialize();

		$_GET['n_id'] = intval($_GET['n_id']);
		$this->n_id = (isset($_GET['n_id']) && $_GET['n_id'] != null)?$_GET['n_id']:null;

		$cate_array = array();
		$this->assign('cate_array', $cate_array);

		$this->assign('n_id', $this->n_id);
	}
    /**
	 * IndexAction::index()
     * 首页
	 * @return void
	 */
	public function index() {
		$news_model = new NewsModel();
		
		$news_list = $news_model->getNews();
		//dump($news_list);
		$this->assign('news_list', $news_list);
		$this->_display('首页');
	}

	/**
	 * 新闻祥情页
	 */
	public function detail() {
		$news_model = new NewsModel();
		
		$news_info = $news_model->getDetail($this->n_id);
		//dump($news_info);
		$this->assign('news_info', $news_info);
		$this->_display('祥情页');
	}

	/**
	 * 新闻Like操作
	 */
	public function updateLike() {
		$news_model = new NewsModel();
		$type = !empty($_POST['type']) ? $_POST['type'] : '';
		$id = !empty($_POST['id']) ? $_POST['id'] : '';
		if(IS_AJAX){//判断ajax请求
			$result = $news_model->updateLike($type, $id);
            if($result){
				$this->ajaxReturn($result,"操作成功",1);
			}else{
				$this->ajaxReturn(0,"操作失败",0);
			}
        }
	}
}
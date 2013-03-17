<?php
class IndexAction extends CommonAction {
	/**
	 * IndexAction::index()
     * 后台首页
	 * @return void
	 */
    public function index() {
		$news_model = new NewsModel();
		
		$news_list = $news_model->getNews();
		//dump($news_list);
		$this->assign('news_list', $news_list);
        $this->_display('新闻列表');
    }

	/**
     * 添加-修改新闻
     * @return void
     */
    public function edit() {
		$this->_display('修改新闻');
	}
}
?>
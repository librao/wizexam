<?php
class NewsModel extends Model{
	/**
	 * 新增新闻
	 */
	public function addNews(){
		// 非空检测
		if(strlen($_POST['title']) == 0){
			$this->error = '标题不能为空';
			return false;
		}
		if(strlen($_POST['content']) == 0){
			$this->error = '内容不能为空';
			return false;
		}
		// 创建数据数组
		$data = $this->create();
		
		$data['created_at'] = time();
		$data['created_by'] = session('auth');
		$data['updated_at'] = time();
		$data['updated_by'] = session('auth');
		$data['status'] = 1;
		
		// 数据插入
		if($this->add($data)){
			//foreach($_POST['link'])
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/**
	 * 修改新闻
	 */
	public function editNews(){
		// 非空检测
		if(strlen($_POST['title']) == 0){
			$this->error = '标题不能为空';
			return false;
		}
		if(strlen($_POST['content']) == 0){
			$this->error = '内容不能为空';
			return false;
		}
		$data = $this->create();

		$data['updated_at'] = time();
		$data['updated_by'] = session('auth');

		if($this->where('id=%d', $_POST['id'])->save()) return TRUE;
		else{
			$this->error = '编辑失败';
			return FALSE;
		}
	}

	/**
	 * 删除新闻
	 */
	public function delNews(){
		$data = $this->create();

		$data['updated_at'] = time();
		$data['updated_by'] = session('auth');
		$data['status'] = 0;

		if($this->where('id=%d', $_POST['id'])->save()) return TRUE;
		else{
			$this->error = '删除失败';
			return FALSE;
		}
	}

	/**
	 * 获得最新新闻
	 * 
	 * @param int $order 排序
	 * @param int $limit 每个群显示的帖子个数
	 */
	public function getNews($order = 'created_at DESC', $limit = NULL){
		$where['status'] = array('eq', 1);
		$newslist = $this->where($where)->order($order)->limit($limit)->select();
		return $newslist;
	}

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

	/**
	 * 更新Like操作
	 * 
	 * @param int $tyoe 操作类型
	 * @param int $id	新闻ID
	 */
	public function updateLike($type, $id){
		if ($type == 'like') {
			$where['like'] = array('eq', +1);
		} else if ($type == 'notlike') {
			$where['notlike'] = array('eq', +1);
		}
		$where['id'] = array('eq', $id);
		$newslist = $this->where($where)->save();
		return $newslist;
	}
}
?>
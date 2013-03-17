<?php
class LinksModel extends Model{
	/**
	 * 新增链接
	 */
	public function addLinks($nid){
		// 避免重复关联,先删除原有关联
		$where['nid'] = array('eq', $nid);
		$this->where($where)->delete();
		
		$data = $this->create();
		// 新增关联
		$data['nid'] = intval($nid);
		$data['created_at'] = time();
		$data['updated_at'] = time();
		$data['status'] = 1;
		if($model->add($data)){
			return TRUE;
		}else{
			$this->error = '新增关联失败';
			return FALSE;
		}
	}

	/**
	 * 修改链接
	 */
	public function getLinks($nid){
		$where['nid'] = array('eq', $nid);
		return $this->where($where)->select();
	}

	/**
	 * 删除链接
	 */
	public function delLinks(){
		$data = $this->create();

		$data['updated_at'] = time();
		$data['updated_by'] = session('auth');
		$data['status'] = 0;

		if($this->where('nid=%d', $nid)->save()) return TRUE;
		else{
			$this->error = '删除失败';
			return FALSE;
		}
	}

}
?>
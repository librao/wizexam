<?php
class IndexAction extends CommonAction {
	/**
	 * IndexAction::index()
     * 后台首页
	 * @return void
	 */
    public function index() {
        $this->_display('Librao - 新闻管理中心');
    }
    
    /**
     * 新闻管理
     * @return void
     */
    public function main() {
		$this->_display('新闻管理');
	}

	/**
     * 新闻列表Ajax数据
     */
    public function doNewsList(){
    	$model = M('News');
    	$fields = array('id', 'title', 'category', 'summary', 'from', 'view', 'like', 'notlike', 'created_at', 'created_by');

    	$where['status'] = array('eq', '1');
    	$count = $model->where($where)->count();
    	
    	$data_table = new DataTable('News', $fields, $where);
    	$results = $data_table->select();
    	$aa_data = array();
    	foreach ($results as $v){
    		$row = array();
    		$row[0] = $v['id'];
    		$row[1] = $v['title'];
    		$row[2] = $v['category'];
    		$row[3] = $v['summary'];
    		$row[5] = $v['view'];
    		$row[6] = $v['like'];
    		$row[7] = $v['notlike'];
    		$row[8] = date('Y-m-d H:i:s', $v['create_datetime']);
    		$row[9] = $v['created_by'];
			$row[10] = '<a href="'.U('Admin/Index/doEditNews?id='.$v['id']).'" title="修改新闻">修改</a>&nbsp;';
    		$row[10] .= '<a href="'.U('Admin/Index/doDelUser?id='.$v['id']).'" title="删除新闻" onclick="if(confirm(\'确定删除\') == false) return false;">删除</a>';

    		$aa_data[] = $row;
    	}
    	echo $data_table->jsonOutput($count, $aa_data);
    }
}
?>
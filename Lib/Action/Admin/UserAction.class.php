<?php
class UserAction extends CommonAction {
    /**
     * 用户列表
     */
    public function index() {
    	$this->_display('用户管理');
    }
    
    /**
     * 用户列表Ajax数据
     */
    public function doUserList() {
    	$model = M('Users');
    	$fields = array('uid', 'user_email', 'is_admin', 'score', 'exp', 'currency', 'create_datetime', 'last_login_ip', 'user_status', 'user_nickname', 'user_avatar');

    	$where['user_status'] = array('neq', '3');
    	$count = $model->where($where)->count();
    	
    	$data_table = new DataTable('Users', $fields, $where);
    	$results = $data_table->select();
    	$aa_data = array();
    	foreach ($results as $v){
    		$row = array();
			//$row[0] = '<input type="checkbox" name="row_sel" />';
    		$row[0] = $v['uid'];
    		$row[1] = '<div style="float:left;margin-right:10px;border:1px solid #8098A8;height:30px;padding:1px;width:30px;"><img style="width:30px; height:30px;" src="'.get_image_default($v['user_avatar'], 'avatar').'-wh48" /></div>';
    		$row[1] .= '<div style="float:left"><a href="/i/'.$v['uid'].'" target="_blank" class="fn">'.$v['user_nickname'].'</a><br />'.$v['user_email'].'</div>';
    		$row[2] = ($v['is_admin'] == '1')?'是':'';
    		$row[3] = $v['score'];
    		$row[4] = $v['exp'];
    		$row[5] = $v['currency'];
    		$row[6] = date('Y-m-d H:i:s', $v['create_datetime']);
    		$row[7] = $v['last_login_ip'];
    		$row[8] = get_user_status_by_code($v['user_status']);
    		if($v['is_admin'] == '1')
    			$row[9] = '<a href="'.U('Admin/User/doSetAdmin?admin=0&uid='.$v['uid']).'" title="取消管理员">取消管理员</a>&nbsp;';
    		else{
    			$row[9] = '<a href="'.U('Admin/User/doSetAdmin?admin=1&uid='.$v['uid']).'" title="设为管理员">设为管理员</a>&nbsp;';
    			$row[9] .= '<a href="'.U('Admin/User/doDelUser?uid='.$v['uid']).'" title="删除用户" onclick="if(confirm(\'确定删除\') == false) return false;">删除</a>&nbsp;';
    		}
    		if($v['is_admin'] == '0'){
    			if($v['user_status'] == '2')
    				$row[9] .= '<a href="'.U('Admin/User/doStopUser?stop=1&uid='.$v['uid']).'" title="解封">解封</a>&nbsp;';
    			else
    				$row[9] .= '<a href="'.U('Admin/User/doStopUser?stop=2&uid='.$v['uid']).'" title="封禁">封禁</a>&nbsp;';
    		}
    		$aa_data[] = $row;
    	}
    	echo $data_table->jsonOutput($count, $aa_data);
    }
    
    /**
     * 删除用户操作
     */
    public function doDelUser() {
    	$uid = intval($_GET['uid']);
    	$model = M('Users');
    	$where['uid'] = array('eq', $uid);
    	$data['user_status'] = '3';
    	if($model->where($where)->save($data)){
    		$this->__kickUserByUid($uid);
    		$this->success('删除用户成功');
    	}else{
    		$this->error('删除用户失败');
    	}
    }
    
    /**
     * 设置管理员操作
     */
    public function doSetAdmin() {
    	$uid = intval($_GET['uid']);
    	if(in_array($uid, explode(',', C('APP_ALONG_ADMIN')))){
    		$this->error('无法设置创始人账号');
    	}
    	$model = M('Users');
    	$where['uid'] = array('eq', $uid);
        $data['is_admin'] = $_GET['admin'];
    	if($model->where($where)->save($data)){
    		$this->__kickUserByUid($uid);
    		$this->success('操作成功');
    	}else{
    		$this->error('操作失败');
    	}
    }
    
    /**
     * 封禁解封用户操作
     */
    public function doStopUser() {
        $uid = intval($_GET['uid']);
    	$model = M('Users');
    	$where['uid'] = array('eq', $uid);
    	$data['user_status'] = $_GET['stop'];
    	if($model->where($where)->save($data)){
    		$this->__kickUserByUid($uid);
    		$this->success('用户状态修改成功');
    	}else{
    		$this->error('用户状态修改失败');
    	}
    }
    
    /**
     * 用户积分记录页面
     */
    public function creditLog() {
    	$this->_display();
    }
    
    /**
     * 用户积分记录Ajax数据
     */
    public function doCreditLogList() {
    	$model = D('UserCreditLogView');
    	
    	$fields = array('time', 'user_email', 'info');

    	$count = $model->count();
    	$data_table = new DataTable('UserCreditLogView', $fields);
    	$results = $data_table->select();
    	$aa_data = array();
    	foreach($results as $v){
    		$row = array();
    		$row[0] = date('Y年m月d日 H时i分s秒', $v['time']);
    		$row[1] = $v['user_email'];
    		$row[2] = $v['info'];
    		$aa_data[] = $row;
    	}
    	echo $data_table->jsonOutput($count, $aa_data);
    }
    
    /**
     * 踢出在线用户
     * @param string $uid
     */
    private function __kickUserByUid($uid) {
    	$uid = intval($uid);
    	$model = M('Session');
    	$user = M('Users');
    	$session_id = $user->where('uid='.$uid)->getField('session_id');
    	$where['session_id'] = array('eq', $session_id);
    	$model->where($where)->delete();
    }
}
?>
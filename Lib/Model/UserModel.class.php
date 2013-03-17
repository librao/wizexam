<?php
class UserModel extends Model{
	/**
     * UsersModel::getInfoByLogin()
     * 根据Email获取用户信息
     * @param string $email
     * @return mixed
     */
    public function getInfoByLogin($user_login) {
        $where['user_login'] = array('eq', $user_login);
        $where['status'] = array('eq', '1');
        $info = $this->where($where)->find();
        if($info == null) return false;
        else return $info;
    }
    
    /**
     * 获得正常状态用户信息
     * @param int $uid
     */
    public function getUserInfoByUid($uid) {
    	$where['id'] = array('eq', intval($uid));
    	$where['status'] = array('eq', 1);
    	$info = $this->where($where)->find();
    	if($info == null) return FALSE;
    	else return $info;
    }
    
    /**
     * UsersModel::getNicknameByUid()
     * 获得用户呢称
     * @param mixed $uid
     * @return
     */
    public function getNicknameByUid($uid) {
        $where['uid'] = array('eq', intval($uid));
        $info = $this->where($where)->field('user_name')->find();
        if($info != null)
            return $info['user_name'];
        else
            return false;
    }

	/**
     * UsersModel::login()
     * 用户登陆操作
     * @param integer $uid
     * @return void
     */
    public function login($uid) {
        $sessions = array();
        $where['id'] = array('eq', intval($uid));
        $where['status'] = array('eq', '1');
        $info = $this->where($where)->find();
        
        $sessions['auth'] = intval($info['id']);
        $sessions['user_name'] = $info['user_name'];
        foreach($sessions as $sess_key => $sess_value){
            session($sess_key, $sess_value);
        }
    }
    
    /**
     * 用户退出
     * 
     * @return void
     */
    public function logout() {
        $uid = session('auth');
        
		session('auth', null);
		session('nickname', null);
        session('avatar', null);
        session('admin', null);
        
        // 销毁session
        session('[destroy]');
        
        // 删除自动登录信息
        cookie('user_auth', null);
    }

}
?>
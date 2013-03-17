<?php
class PublicAction extends GlobalAction {
	/**
	 * PublicAction::login()
	 * 用户登陆
	 * @return void
	 */
	public function login() {
		if(!is_login()) {
			$this->_display('登陆');
		}else{
			$this->redirect('/');
		}
	}

	/**
	 * PublicAction::logout()
	 * 退出
	 * @return void
	 */
	public function logout() {
	   $model = D('User');
       $model->logout();
       
       $redirect_url = $this->_get('request_url', 'trim', '/');
       
       $this->redirect($redirect_url);
	}

	/**
	 * PublicAction::check_login()
	 * 用户登陆
	 * @return void
	 */
	public function doLogin() {
		$user_login = trim($_POST['user_login']);
		$user_pass = trim($_POST['user_pass']);
		if(isset($_POST['auto']) && $_POST['auto'] == 'on') {
			$auto = true;
		} else { $auto = false; }

		$model = D('User');
		$info = $model->getInfoByLogin($user_login);
		if(false === $info) {
			$this->error('该账号不存在或者已被管理员封禁');
		} else {
			// 登陆成功
			if($info['user_pass'] == md5($user_pass)) {
                // 记录cookie信息
				cookie('user_login', $user_login, 3600 * 24 * 7);
                
                // 自动登录
                if($auto){
                    import('ORG.Crypt.Crypt');
                    $crypt = Crypt::encrypt('%CRYPT%'.$info['user_login'].'%CRYPT%', C('CRYPT_KEY'), true);
                    cookie('user_auth', $crypt, 3600 * 24 * 30);
                }
                
				// SESSION信息记录
				$model->login($info['id']);
				
				// 判断是否需要跳转到登陆前的页面，默认跳转到首页
				$redirect_url = trim($_POST['redirect_url']);
				if(strlen($redirect_url) > 0){
					$this->jump_url = $redirect_url;
				}else{
					$this->jump_url = '/';
				}
				
				$this->success('登陆成功', $this->jump_url);
			} else {
				$this->error('密码错误，请重新输入');
			}
		}
		//dump($_SESSION);
	}
}
?>
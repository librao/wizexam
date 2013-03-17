<?php
class PublicAction extends CommonAction {
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
	   $model = D('Users');
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
		$email = trim($_POST['user_email']);
		$password = trim($_POST['user_pass']);
		if(isset($_POST['auto']) && $_POST['auto'] == 'on') {
			$auto = true;
		} else { $auto = false; }

		$model = D('Users');
		$info = $model->getInfoByEmail($email);
		if(false === $info) {
			$this->error('该账号不存在或者已被管理员封禁');
		} else {
			// 登陆成功
			if($info['user_pass'] == md5($password)) {
                // 记录cookie信息
				cookie('user_email', $email, 3600 * 24 * 7);
                
                // 自动登录
                if($auto){
                    import('ORG.Crypt.Crypt');
                    $crypt = Crypt::encrypt('%CRYPT%'.$info['user_email'].'%CRYPT%', C('CRYPT_KEY'), true);
                    cookie('user_auth', $crypt, 3600 * 24 * 30);
                }
                
				// SESSION信息记录
				$model->login($info['uid']);
				
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
		dump($_SESSION);
	}

	/**
	 * PublicAction::register()
	 * 用户注册操作
	 * @return void
	 */
	public function register() {
		$model = D('Users');
		if($model->create() === false) {
			$this->error($model->getError());
		} else {
			if($model->register()) {
				$this->jump_url = '/';
				$this->success('注册成功');
			}
		}
	}

    // 后台首页 查看系统信息
    public function main() {
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
        $this->display();
    }
}
?>
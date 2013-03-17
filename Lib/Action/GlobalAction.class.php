<?php
class GlobalAction extends Action {
	public $jump_url; // 页面跳转参数
	protected $_error = '';

	public function _initialize() {
		// 加载项目配置数据
		load_app_option();
		
		// 自动登录
		if(!is_login())
			if(cookie('user_auth') != null){
				$this->__autoLogin();
			}
		// 过滤$_POST数组中的前后空格
		foreach ($_POST as $k => $v){
			if(!is_array($v)){
				$_POST[$k] = trim($v);
			}
		}
	}

	/**
	 * 页面常量赋值
	 * @param 页面标题
	 */
	protected function _assign_define($title) {
		$module_name = $title;
		// 后台不显示二级标题
		if(strtolower(GROUP_NAME) !== 'admin'){
			$module_name .= ($module_name == '')?C('APP_WEBSITE_NAME').'【'.C('APP_WEBSITE_DESCRIPTION').'】':' - '.C('APP_WEBSITE_NAME').'【'.C('APP_WEBSITE_DESCRIPTION').'】';
		}
		$this->assign('SUBTITLE', $title);
		$this->assign('TITLE', $module_name);
	}

	/**
	 * 模板渲染
	 * @param mixed $title 页面标题名称
	 * @param string $template_file 模板文件路径
	 * @return void
	 */
	protected function _display($title = null, $template_file = '') {
		$this->_assign_define($title);
        $this->display($template_file);
	}

	/**
	 * CommonAction::__autoLogin()
	 * 用户自动登录
	 * @return void
	 */
	private function __autoLogin() {
		$auth = cookie('user_auth');
		if($auth == null && empty($auth))
			return false;
		else {
			import('ORG.Crypt.Crypt');
			$auth = str_replace('%CRYPT%', '', Crypt::decrypt($auth, C('CRYPT_KEY'), true));
			$model = D('User');
			$where['user_login'] = array('eq', $auth);
			$where['status'] = array('eq', 1);
			$uid = $model->field('id')->where($where)->find();
			if($uid != null) {
				$model->login($uid['id'], false);
			}else{
				cookie('user_auth', null);
			}
		}
	}
	
	/**
	 * 上传文件
	 * @param string $type 文件类型
	 * @param boolean $watermark 是否自动加水印,图片文件有效
	 */
	protected function _upload($type = 'images', $watermark = FALSE) {
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		switch ($type){
			case 'images':
				$upload->maxSize = C('APP_IMAGES_MAXSIZE'); // 文件大小
				$upload->allowExts = explode(',', C('APP_IMAGES_ALLOWEXTS')); // 上传文件类型
				break;
			case 'attach':
				$upload->maxSize = C('APP_ATTACH_MAXSIZE'); // 文件大小
				$upload->allowExts = explode(',', C('APP_ATTACH_ALLOWEXTS')); // 上传文件类型
				break;
			case 'flash':
				$upload->maxSize = C('APP_FLASH_MAXSIZE'); // Flash文件大小
				$upload->allowExts = explode(',', C('APP_FLASH_ALLOWEXTS')); // 上传文件类型
		}
		$upload->savePath = './Uploads/'.ucfirst($type).'/'; // 保存路径
		$upload->saveRule = 'uniqid';
		if(!$upload->upload()){
			$this->_error = $upload->getErrorMsg();
			return FALSE;
		}else{
			$file_info = $upload->getUploadFileInfo();
			if($type === 'images' && $watermark){
				$watermark_url = C('APP_WATER_MARK_PATH');
				$images_url = watermark($file_info[0]['savepath'].$file_info[0]['savename'], $watermark_url, 9, 100);
				unlink($file_info[0]['savepath'].$file_info[0]['savename']);
				$file_info[0]['savename'] = basename($images_url);
			}
			return $file_info;
		}
	}
	
	/**
	 * 空操作
	 */
	public function _empty() {
		$this->error('你访问的页面不存在');
	}
}

?>
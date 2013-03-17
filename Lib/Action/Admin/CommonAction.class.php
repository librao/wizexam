<?php
import('@.ORG.Util.DataTable');
class CommonAction extends GlobalAction {
    /**
     * 后台公共初始化函数
     * @return void
     */
    public function _initialize() {
        parent::_initialize();
        
        if(!is_login()) {
            $this->redirect('/login');
            exit();
        }
    }
}
?>
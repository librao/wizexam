<?php
import('@.ORG.Util.DataTable');
class CommonAction extends GlobalAction {
    /**
     * 后台公共初始化函数
     * @return void
     */
    public function _initialize() {
        parent::_initialize();
        
        if(!is_admin()) {
            //$this->redirect('/');
            //exit();
        }

		$menu = list_to_tree(get_menu_node_cache());
		$this->assign('menu',$menu);
    }
}
?>
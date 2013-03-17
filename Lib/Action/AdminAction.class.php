<?php

class AdminAction extends Action {

    public function index() {
        $this->display();
    }

    public function insert() {
        $model = D('News');
        $date = $model->create();
        if ($date) {
            if ($model->add()) {
                $this->success('操作成功');
            } else {
                
            }
        } else {
            
        }
    }

}

?>

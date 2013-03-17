<?php

class AjaxAction extends Action {

    public function digg() {
        $id = intval($_POST['id']);
        $json = array();

        $model = M('News');
        $map['id'] = array('eq', $id);
        if ($model->where($map)->setInc('digg')) {
            $json['status'] = TRUE;
        } else {
            $json['status'] = FALSE;
        }
        echo json_encode($json);
    }

}

?>

<?php

class NewsModel extends Model {

    const STATUS_ACTIVE = 'ACTIVE';

    protected $_auto = array(
        array('status', self::STATUS_ACTIVE),
        array('created_at', 'getCurrentDateTime', Model::MODEL_INSERT, 'function'),
        array('updated_at', 'getCurrentDateTime', Model::MODEL_BOTH, 'function')
    );

}

?>

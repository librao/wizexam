<?php

$db_config = include_once 'db.inc.php';
$config = array(
    'URL_MODEL' => 2
);

return array_merge($db_config, $config);
?>
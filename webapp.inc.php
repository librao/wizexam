<?php
/**
 * 项目配置
 */
return array(    
    /**
     * Cookie&Session
     */
    // SESSION设置
    //'SESSION_AUTO_START' => true,
    //'SESSION_TYPE' => 'db',
    //'SESSION_TABLE' => 'gh_session',
    //'SESSION_EXPIRE' => 3600,
    //'SESSION_PREFIX' => 'gamehouse_',
    
    // COOKIE设置
    'COOKIE_EXPIRE' => 3600 * 12,
    'COOKIE_PREFIX' => 'librao_',
    
    /**
     * 密匙部分
     */
    // Xxtea
	'CRYPT_KEY' => 'librao_news',
);
?>
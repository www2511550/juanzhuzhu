<?php
$config = array(
    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址    
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '',  // 密码
    'DB_PORT'   => '3306', // 端口    
    'DB_NAME'   => 'r_system', // 数据库名    
    'DB_PREFIX' => 'sys_', // 数据库表前缀 

    /* 加密前缀 */
    'PWD_KEY'   => 'cc_baguawu_520',   // 用户注册加密前缀
    'ADMIN_KEY' => 'cc_!@#$%_admin',   // 后台加密串

    'DEFAULT_AJAX_RETURN' => 'JSON',  // $this->ajaxRetrun配置
);
if (DEV) {
    return $config;die;
}
return array(
    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'qdm111421206.my3w.com', // 服务器地址    
    'DB_USER'   => 'qdm111421206', // 用户名
    'DB_PWD'    => 'a2511550',  // 密码
    'DB_PORT'   => '3306', // 端口    
    'DB_NAME'   => 'qdm111421206_db', // 数据库名    
    'DB_PREFIX' => 'sys_', // 数据库表前缀 

    /* 加密前缀 */
    'PWD_KEY'   => 'cc_baguawu_520',   // 用户注册加密前缀
    'ADMIN_KEY' => 'cc_!@#$%_admin',   // 后台加密串

    'DEFAULT_AJAX_RETURN' => 'JSON',  // $this->ajaxRetrun配置
);
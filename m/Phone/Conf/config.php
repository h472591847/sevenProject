<?php
return array(
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' =>  __ROOT__ . '/' . APP_NAME . '/Tpl/Public'
    ),    
    'TMPL_ACTION_ERROR'=>'./Phone/Tpl/Common/error.html',//自定义错误提示页面
    'TMPL_ACTION_SUCCESS'=>'./Phone/Tpl/Common/success.html',//自定义成功提示页面         
    //数据库链接相关
    'DB_HOST' => 'localhost',//数据库链接地址
    'DB_USER' => 'root',//数据库用户名
    'DB_PWD' => '123456',//数据库密码    
    // 'DB_HOST' => '127.0.0.1',//数据库链接地址
    // 'DB_USER' => 'p2p222',//数据库用户名
    // 'DB_PWD' => 'jinyongbofa2015',//数据库密码    
    // 'DB_PORT' => '43697',//数据库端口
    'DB_PORT' => '3306',//数据库端口
    'DB_NAME' => 'tp',//数据库名字
    'DB_PREFIX' => 'tp_',//数据库前缀
    'LOAD_EXT_CONFIG' => 'system', //添加配置文件
    'LOG_RECORD'=>true,  // 进行日志记录
    'LOG_EXCEPTION_RECORD'  => true,    // 是否记录异常信息日志
    'LOG_LEVEL'       =>   'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL',  // 允许记录的日志级别
    'DB_FIELDS_CACHE'=> false, // 字段缓存信息
    'APP_FILE_CASE'  =>   true, // 是否检查文件的大小写 对Windows平台有效
    'TMPL_CACHE_ON'    => false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_STRIP_SPACE'      => false,       // 是否去除模板文件里面的html空格与换行
    'SHOW_ERROR_MSG'        => true,    // 显示错误信息
    //'SHOW_PAGE_TRACE' => TRUE,  //显示调试文件
    'HOST' => 'http://p2p222.local'//服务器端地址    
);
?>
<?
//公用配置文件
return array(
	//数据库链接相关
	'DB_HOST' => '127.0.0.1',//数据库链接地址
	// 'DB_USER' => 'p2p222',//数据库用户名
    // 'DB_PWD' => 'jinyongbofa2015',//数据库密码  
    'DB_PWD' => '123456',//数据库密码
    'DB_USER' => 'root',//数据库用户名
	'DB_PORT' => '3306',//数据库端口  
	'DB_NAME' => 'tp',//数据库名字
	'DB_PREFIX' => 'tp_',//数据库前缀
	'LOAD_EXT_CONFIG' => 'system', //添加配置文件
	'TMPL_CACHE_ON' => false,  // 每次都重新编译模板       
	'ACTION_CACHE_ON'  => false,  // 默认关闭Action 缓存   
	'HTML_CACHE_ON'   => false,   // 默认关闭静态缓存     
	'DB_FIELD_CACHE'=>false,  //默认关闭字段缓存
//'SHOW_PAGE_TRACE' => TRUE  显示调试文件
    'HOST' => 'http://p2p222.local'//服务器端地址
)
?>
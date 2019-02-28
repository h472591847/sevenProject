<?php
$config = array(
	'TMPL_PARSE_STRING' => array(
				'__PUBLIC__' =>  __ROOT__ . '/' . APP_NAME . '/Tpl/Public'
	),
	'URL_HTML_SUFFIX' => '',
	//rbac权限参数
	'RBAC_SUPERADMIN' => 'admin',		//超级管理员账号
	'ADMIN_AUTH_KEY' => 'superadmin',		//超级管理员识别号
	'USER_AUTH_ON' => true,					//是否开启权限验证
	'USER_AUTH_TYPE' => 1,					//验证类型（1、登录验证 2、时时验证）
	'USER_AUTH_KEY' => 'uid',				//用户识别号
	'NOT_AUTH_MODULE' => 'Index',			//无需验证的控制器
	'NOT_AUTH_ACTION' => '',				//无需验证的动作方法
	'RBAC_ROLE_TABLE' => 'tp_role',			//角色表
	'RBAC_USER_TABLE' => 'tp_role_user',	//角色与用户中间表名称
	'RBAC_ACCESS_TABLE' => 'tp_access',		//权限表名称
	'RBAC_NODE_TABLE' => 'tp_node',			//节点表名称
);
return array_merge(include('./Conf/config.php'),$config);
?>
<?php
//用户关联角色模型
class UserModel extends RelationModel{
	//定义主表名称
	//Protected $tableName = 'user';
	//定义关联关系
	Protected $_link = array(
		'role' => array(
			'mapping_type' => MANY_TO_MANY,    //关联关系 ｛HAS_ONE、一对一 HAS_MANY、一对多 MANY_TO_MANY、多对多 ｝
			'foreign_key' => 'user_id', //主表外键
			'relation_key' => 'role_id', //关联表外键
			'mapping_fields' => 'id,name,remark', //取得相应键名值
			'relation_table' => 'tp_role_user'  //中间表名称、默认是  tp_user_role
		),
	);
}
?>
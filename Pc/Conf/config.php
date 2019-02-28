<?php
    $config = array(
        'TMPL_PARSE_STRING' => array(
            '__PUBLIC__' =>  __ROOT__ . '/' . APP_NAME . '/Tpl/Public',            
        ),    
     'SESSION_EXPIRE'=>'1',  
    'TMPL_ACTION_ERROR'=>'./Pc/Tpl/Common/error.html',//自定义错误提示页面
    'TMPL_ACTION_SUCCESS'=>'./Pc/Tpl/Common/success.html',//自定义成功提示页面          
    'TOKEN_ON'=>true,  // 是否开启令牌验证
    'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
    'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则默认为MD5      
    );
    return array_merge(include  '/Conf/config.php',$config);    
?>
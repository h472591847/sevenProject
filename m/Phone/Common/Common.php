<?php
include('./Common/Common.php');

/**
 * @todo  get_http    获取协议 
 */
function get_http()
{
    return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}


/**
 * @todo   get_domain    获取域名   
 */
function get_domain()
{
    /* 协议 */
    $protocol = get_http();

    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}


/**
 * @todo   get_host 获取域名主机头
 */
function get_host()
{


    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'];
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'];
        }
    }
    return $host;
}



/**
 * @todo   isMobile   判断是否移动设备
 * @return boolean    true 移动设备  false 非移动设备
 */
function isMobile() {
   // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
   if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
    return true;
   }
   //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
   if (isset ($_SERVER['HTTP_VIA'])) {
    //找不到为flase,否则为true
    return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
   }
   //脑残法，判断手机发送的客户端标志,兼容性有待提高
   if (isset ($_SERVER['HTTP_USER_AGENT'])) {
    $clientkeywords = array (
     'nokia',
     'sony',
     'ericsson',
     'mot',
     'samsung',
     'htc',
     'sgh',
     'lg',
     'sharp',
     'sie-',
     'philips',
     'panasonic',
     'alcatel',
     'lenovo',
     'iphone',
     'ipod',
     'blackberry',
     'meizu',
     'android',
     'netfront',
     'symbian',
     'ucweb',
     'windowsce',
     'palm',
     'operamini',
     'operamobi',
     'openwave',
     'nexusone',
     'cldc',
     'midp',
     'wap',
     'mobile'
    );
    // 从HTTP_USER_AGENT中查找手机浏览器的关键字
    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
     return true;
    }
   }
   //协议法，因为有可能不准确，放到最后判断
   if (isset ($_SERVER['HTTP_ACCEPT'])) {
    // 如果只支持wml并且不支持html那一定是移动设备
    // 如果支持wml和html但是wml在html之前则是移动设备
    if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
     return true;
    }
   }
   return false;
}

/**
* 301重定向
*/
$the_host = $_SERVER['HTTP_HOST'];//取得进入所输入的域名
$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';//判断地址后面部分
if($the_host == 'p2p222.com' || $the_host == 'p2p222.net' || $the_host == 'www.p2p222.net')//这是我要以前的域名地址
{
  header('HTTP/1.1 301 Moved Permanently');//发出301头部 
  header('Location: http://m.p2p222.com'.$request_uri);//跳转到我的新域名地址
} 


/*
*判断点击按钮跳转电脑站
 */
if(!empty($_GET['is_change'])){
    $request_uri = str_replace('is_change','i_change',$request_uri);     
    $redirect_uri = 'http://www.p2p222.com'.$request_uri;           
    header('Location:'.$redirect_uri);     
}


/**
 * @todo     editor_replace_imgurl     编辑器内容图片地址替换
 * @param    [type] $content             编辑器内容
 */
function editor_replace_imgurl($content){    
    $img_count = preg_match_all("/\<img.*?src\=\"(.*?)\"[^>]*>/i", $content, $match);    
    $content = ($img_count>0)?str_replace('src="/Uploads/', 'src="'.C('HOST').'/Uploads/', $content):$content;        
    return $content; 
}

/**
* 保存来路
*/
if(!$_COOKIE['referer_url'])
{       
    //echo urlencode($_SERVER["HTTP_REFERER"]);die;
    if(!preg_match("/".urlencode(get_domain())."/",urlencode($_SERVER["HTTP_REFERER"])))
    setcookie("referer_url",$_SERVER["HTTP_REFERER"]);
}
$GLOBALS['referer'] = $_COOKIE["referer_url"];
?>
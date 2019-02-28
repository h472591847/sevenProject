<?php
//设置脚本执行时间最多为120秒
set_time_limit(120);
//公用函数文件
defined('THINK_PATH') or exit();
/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $string 需要转换的字符串
 * @param string $start 开始位置
 * @param string $sublen 截取长度
 * @param string $code 编码格式 
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */

function msubstr($string,$start=0,$sublen,$code='UTF-8'){
    if($code == 'UTF-8'){
    $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    preg_match_all($pa, $string, $t_string);
    if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."..";
    return join('', array_slice($t_string[0], $start, $sublen));
    }else{
    $start = $start*2;
    $sublen = $sublen*2;
    $strlen = strlen($string);
    $tmpstr = '';
    for($i=0; $i< $strlen; $i++){
    if($i>=$start && $i< ($start+$sublen)){
    if(ord(substr($string, $i, 1))>129){
    $tmpstr.= substr($string, $i, 2);
    }else{
    $tmpstr.= substr($string, $i, 1);
    }
    }
    if(ord(substr($string, $i, 1))>129) $i++;
    }
    if(strlen($tmpstr)< $strlen ) $tmpstr.= "..";
    return $tmpstr;
    }
}

/**
 * @todo 过滤编辑器的危险代码
 */
function getEditorContent($v){
	import('@.ORG.Util.Input');
	return Input::safeHtml($v);
}
/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list,$field, $sortby='asc') {
	if(is_array($list)){
		$refer = $resultSet = array();
		foreach ($list as $i => $data)
			$refer[$i] = &$data[$field];
		switch ($sortby) {
			case 'asc': // 正向排序
				asort($refer);
				break;
			case 'desc':// 逆向排序
				arsort($refer);
				break;
			case 'nat': // 自然排序
				natcasesort($refer);
				break;
		}
		foreach ( $refer as $key=> $val)
			$resultSet[] = &$list[$key];
		return $resultSet;
	}
	return false;
}

/*
 * 获取键值
* */
function getArraykey($arr=array(),$key){
	$newarr=array_keys($arr);
	return $newarr[$key];
}
/*
 * 模版样式路径缩短
*/
function STYLE_PATH($value=''){
	return strtolower($value)=='public' ? 
	'http://'.$_SERVER['HTTP_HOST']. __ROOT__.C('SKIN_PATH').'/'.strtoupper(APP_NAME).'/Public' 
	:
	'http://'.  $_SERVER['HTTP_HOST'].__ROOT__.C('SKIN_PATH').'/'.strtoupper(APP_NAME).'/'.C('DEFAULT_THEME');
}
/*用户名合法性检测*/
function NoUserName($value){
	$arr=explode(",",C('NO_USER_NAME'));
	if(in_array(strtolower($value), $arr)){
		return false;
	}else{
		return true;
	}
}
//获取加密数据
function getEncrypt($string){
//	import('@.ORG.Aes');
    import('Class.Aes','./');
	$aes_obj = new Aes();
	return $aes_obj->aes_encode($string);
}
//获取解密数据
function getDecrypt($string){
    import('Class.Aes','./');    
	//import('@.ORG.Aes');
	$aes_obj = new Aes();
	return $aes_obj->aes_decode($string);
}

/**
 * @todo 获取ip信息
 */

function getIpInfo($ip){
	import('@.ORG.Net.IpLocation');// 导入IpLocation类
	$Ip = new IpLocation(); // 实例化类
	return $location = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
}

/**
 * 转码为UTF-8
 * @param $keyword	要转码的字符串
 */
function iconv_to_utf8($keyword, $to='UTF-8'){
	$encode = mb_detect_encoding($keyword, array('ASCII','UTF-8','GBK','GB2312'));
	if($encode != $to){
		$keyword = iconv($encode, $to, $keyword);
	}
	return $keyword;
}

function array_implode($arrays, &$target = array()) {
	foreach ($arrays as $item) {
		if (is_array($item)) {
			array_implode($item, $target);
		} else {
			$target[] = $item;
		}
	}
	return $target;
}
/**
 * @todo 获取图片缩略图
 * @param string $pic
 * @param array $key_array
 * @return string
 */
function getSmallPic($pic='',$key_array=array()){
	$pic_arr=explode('/',$pic);
	for($i=0;$i<count($key_array);$i++){
		$return_pic_array[$key_array[$i]] = '/'.$pic_arr[1].'/'.$pic_arr[2].'/'.$key_array[$i].$pic_arr[3];
	}
	return $return_pic_array;
}

/**
 * 获取远程文件大小
 * @param unknown $uri
 * @param string $user
 * @param string $pw
 * @return string
 */
function remote_filesize($uri,$user='',$pw=''){
	ob_start();
	$uri =str_replace('\\', '', $uri);
	$ch = curl_init($uri);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	if (!empty($user) && !empty($pw)){
		$headers = array('Authorization: Basic ' . base64_encode($user.':'.$pw));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	$okay = curl_exec($ch);
	curl_close($ch);
	$head = ob_get_contents();
	ob_end_clean();
	$regex = '/Content-Length:\s([0-9].+?)\s/';
	$count = preg_match($regex, $head, $matches);
	if (isset($matches[1])){
		$size = $matches[1];
	}else{
		$size = 'unknown';
	}
	return $size;
	$last=round($size/(1024*1024),3);
	return $last.' MB';
}
/**
 * 验证是否存在
 * string $table 操作表
 * array $field 字段名
 * array $info 数据
 * 返回 select
 */
function getInfoSelect($table,$field,$info)
{
	$obj=M($table);
	$where=array();
	for($i=0;$i<count($field);$i++)
	{
		$where[$field[$i]]=$info[$i];
	}
	$res_count=$obj->where($where)->count();
	//echo $obj->getLastSql();
	if($res_count>0)
	{
		return $obj->where($where)->select();
	}
	else
	{
		return false;
	}
}

/**
 * 验证是否存在
 * string $table 操作表
 * array $field 字段名
 * array $info 数据
 */
function getInfo($table,$field,$info)
{
	$obj=M($table);
	$where=array();
	for($i=0;$i<count($field);$i++)
	{
		$where[$field[$i]]=$info[$i];
	}
	$res_count=$obj->where($where)->count();
	if($res_count>0)
	{
		return $obj->where($where)->find();
	}
	else
	{
		return false;
	}
}
/**
 * 添加数据
 * string $table 操作表
 * array $field 字段名
 * array $info 数据
 */
function getAdd($table,$field,$info)
{
	$obj=M($table);
	$where=array();
	for($i=0;$i<count($field);$i++)
	{
		$data[$field[$i]]=$info[$i];
	}
	if($obj->add($data))
	{
		return true;
	}
	else
	{
		return false;
	}
}
/**
 * 修改数据
 * string $table 操作表
 * array $field 字段名
 * array $info 数据
 */
function getSave($table,$field,$info)
{
	$obj=M($table);
	$where=array();
	for($i=0;$i<count($field);$i++)
	{
		$where[$field[$i]]=$info[$i];
	}
	if($obj->save($where))
	{
		return true;
	}
	else
	{
		return false;
	}
}
/**
 * 删除数据
 * string $table 操作表
 * array $field 字段名
 * array $info 数据
 */
function getDel($table,$field,$info)
{
	$obj=M($table);
	$where=array();
	for($i=0;$i<count($field);$i++)
	{
		$where[$field[$i]]=$info[$i];
	}
	if($obj->where($where)->delete())
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 导出数据方法
 * array $res 查询数据的结果集
 * array $title 导出表格的中文标题
 * array $res_value 要导出数据的字段名
 */
// 用法：ExcelOut($result,array('序号','处理结果'),array('id','resultCode'));
function ExcelOut($res,$title,$res_value){
	ini_set('memory_limit','1024M');
	set_time_limit(0);
	vendor ( 'Class.PHPExcel.PHPExcel','./');
	vendor ( 'Class.PHPExcel.PHPExcel.IOFactory','./');

	$objPHPExcel = new PHPExcel ();
	$objActSheet = $objPHPExcel->getActiveSheet();
	$arr=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$k=1;

	foreach ($res as $key=>$value)
	{
		if($k==1)
		{
			for($i=0;$i<count($title);$i++)
			{
				$objActSheet->setCellValue($arr[$i].$k,$title[$i]);
				$objActSheet->getColumnDimension($arr[$i])->setAutoSize(true);
			}
		}
		$k++;
		for($j=0;$j<count($title);$j++)
		{
			$objActSheet->setCellValue($arr[$j].$k,DeleteHtml(strip_tags($value[$res_value[$j]])));
		}
	}

	@$excelName = rand(1000, 9999).date('Ymd').'.xls';
	@$objWriter = @PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
    ob_end_clean();//清除缓冲区,避免乱码
	@header ( "Content-Type: application/force-download" );
	@header ( "Content-Type: application/octet-stream" );
	@header ( "Content-Type: application/download" );
	// @header("Content-Disposition: attachment; filename=".urlencode($excelName));
	@header ( "Content-Disposition: attachment; filename=" . $excelName );


	@header ( "Content-Transfer-Encoding: binary" );
	@header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	@header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
	@header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	@header ( "Pragma: no-cache" );
	@$objWriter->save ( 'php://output' );
}

/**
 * 删除HTML空格回车等字符
 */
function DeleteHtml($str)
{
	$str = trim($str);
	$str = strip_tags($str,"");
	$str = ereg_replace("\t","",$str);
	$str = ereg_replace("\r\n","",$str);
	$str = ereg_replace("\r","",$str);
	$str = ereg_replace("\n","",$str);
	$str = ereg_replace(" "," ",$str);
	return trim($str);
}
/**
 * 获取中文首字母
 */
function pykey( $py_key)     
{     
    $pinyin = 65536 + pys($py_key);     
    if ( 45217 <= $pinyin && $pinyin <= 45252 )     
    {     
        $zimu = "A";     
        return $zimu;     
    }     
    if ( 45253 <= $pinyin && $pinyin <= 45760 )     
    {     
        $zimu = "B";     
        return $zimu;     
    }     
    if ( 45761 <= $pinyin && $pinyin <= 46317 )     
    {     
        $zimu = "C";     
        return $zimu;     
    }     
    if ( 46318 <= $pinyin && $pinyin <= 46825 )     
    {     
        $zimu = "D";     
        return $zimu;     
    }     
    if ( 46826 <= $pinyin && $pinyin <= 47009 )     
    {     
        $zimu = "E";     
        return $zimu;     
    }     
    if ( 47010 <= $pinyin && $pinyin <= 47296 )     
    {     
        $zimu = "F";     
        return $zimu;     
    }     
    if ( 47297 <= $pinyin && $pinyin <= 47613 )     
    {     
        $zimu = "G";     
        return $zimu;     
    }     
    if ( 47614 <= $pinyin && $pinyin <= 48118 )     
    {     
        $zimu = "H";     
        return $zimu;     
    }     
    if ( 48119 <= $pinyin && $pinyin <= 49061 )     
    {     
        $zimu = "J";     
        return $zimu;     
    }     
    if ( 49062 <= $pinyin && $pinyin <= 49323 )     
    {     
        $zimu = "K";     
        return $zimu;     
    }     
    if ( 49324 <= $pinyin && $pinyin <= 49895 )     
    {     
        $zimu = "L";     
        return $zimu;     
    }     
    if ( 49896 <= $pinyin && $pinyin <= 50370 )     
    {     
        $zimu = "M";     
        return $zimu;     
    }     
    if ( 50371 <= $pinyin && $pinyin <= 50613 )     
    {     
        $zimu = "N";     
        return $zimu;     
    }     
    if ( 50614 <= $pinyin && $pinyin <= 50621 )     
    {     
        $zimu = "O";     
        return $zimu;     
    }     
    if ( 50622 <= $pinyin && $pinyin <= 50905 )     
    {     
        $zimu = "P";     
        return $zimu;     
    }     
    if ( 50906 <= $pinyin && $pinyin <= 51386 )     
    {     
        $zimu = "Q";     
        return $zimu;     
    }     
    if ( 51387 <= $pinyin && $pinyin <= 51445 )     
    {     
        $zimu = "R";     
        return $zimu;     
    }     
    if ( 51446 <= $pinyin && $pinyin <= 52217 )     
    {     
        $zimu = "S";     
        return $zimu;     
    }     
    if ( 52218 <= $pinyin && $pinyin <= 52697 )     
    {     
        $zimu = "T";     
        return $zimu;     
    }     
    if ( 52698 <= $pinyin && $pinyin <= 52979 )     
    {     
        $zimu = "W";     
        return $zimu;     
    }     
    if ( 52980 <= $pinyin && $pinyin <= 53640 )     
    {     
        $zimu = "X";     
        return $zimu;     
    }     
    if ( 53689 <= $pinyin && $pinyin <= 54480 )     
    {     
        $zimu = "Y";     
        return $zimu;     
    }     
    if ( 54481 <= $pinyin && $pinyin <= 62289 )     
    {     
        $zimu = "Z";     
        return $zimu;     
    }     
    $zimu = $py_key;     
    return $zimu;     
}     
function pys( $pysa )     
{     
    $pyi = "";     
    $i= 0;     
    for ( ; $i < strlen( $pysa ); $i++)     
    {     
        $_obfuscate_8w= ord( substr( $pysa,$i,1) );     
        if ( 160 < $_obfuscate_8w)     
        {     
            $_obfuscate_Bw = ord( substr( $pysa, $i++, 1 ) );     
            $_obfuscate_8w = $_obfuscate_8w * 256 + $_obfuscate_Bw - 65536;     
        }     
        $pyi.= $_obfuscate_8w;     
    }     
    return $pyi;     
}  




/**
 * google api 二维码生成【QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式】
 * @param string $chl 二维码包含的信息，可以是数字、字符、二进制信息、汉字。不能混合数据类型，数据必须经过UTF-8 URL-encoded.如果需要传递的信息超过2K个字节，请使用POST方式
 * @param int $widhtHeight 生成二维码的尺寸设置
 * @param string $EC_level 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
 *                         L-默认：可以识别已损失的7%的数据
 *                         M-可以识别已损失15%的数据
 *                         Q-可以识别已损失25%的数据
 *                         H-可以识别已损失30%的数据
 * @param int $margin 生成的二维码离图片边框的距离
 */
function generateQRfromGoogle($chl, $widhtHeight = '150', $EC_level = 'L', $margin = '0') {
    $chl = urlencode($chl);
    return '<img src="http://chart.apis.google.com/chart?chs=' . $widhtHeight . 'x' . $widhtHeight . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $chl . '" alt="QR code" widhtHeight="' . $widhtHeight . '" widhtHeight="' . $widhtHeight . '"/>';
}

/**
 * @todo 生成src的路径 存入数据库中
 * @param unknown $chl
 * @param string $widhtHeight
 * @param string $EC_level
 * @param string $margin
 * @return string
 */
function creatGenerateQrFromGoogle($chl, $widhtHeight = '150', $EC_level = 'L', $margin = '0') {
    $img_src = 'http://chart.apis.google.com/chart?chs=' . $widhtHeight . 'x' . $widhtHeight . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $chl;
    $imagebin = file_get_contents($img_src);
    $filedir = "./Uploads/creatGenerateQrFromGoogle/";
    if (!is_dir($filedir))
        mkdir($filedir);
    $img_name = '/Uploads/creatGenerateQrFromGoogle/' . time() . rand(00000000000000000, 99999999999999999) . '.png';
    if ($imagebin) {
        if (file_put_contents($img_name, $imagebin)) {
            return $img_name;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// 二维码函数
function creatQrCode($value, $errorCorrectionLevel = 'L', $matrixPointSize = '7') {
    vendor('phpqrcode.qrlib');
    //$value    传入的值
    //$filename 文件名称
    //$errorCorrectionLevel 类型：L M Q H；
    //$matrixPointSize      大小  1 2 3 4 5 6 7 8 9 10 中的一个
    //$info=QRcode::png($value,$filename,$errorCorrectionLevel, $matrixPointSize, 2);
    //  $PNG_TEMP_DIR ='/Upload/'.'Qrcode'.'/';
    //根据时间创建目录 每天创建一个
    $time = date("Y-m-d", time());
    $PNG_TEMP_DIR = 'Uploads/QrCode/' . $time . '/';
    $now = strtotime($time);
    $PNG_WEB_DIR = 'Uploads/QrCode/' . $time . '/';
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $filename = $PNG_TEMP_DIR . 'test.png';
    $errorCorrectionLevel = $errorCorrectionLevel;
    $matrixPointSize = $matrixPointSize;
    if (isset($value)) {
        if (trim($value) == '')
            die('data cannot be empty! <a href="?">back</a>');
        $filename = $PNG_TEMP_DIR . '_Msj_' . md5($value . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
        $bb = QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    } else {
        echo '可能值为空所以生成失败！';
    }
    $qrcode_path = $PNG_WEB_DIR . basename($filename);
    return $qrcode_path;
}

// 生成商家名片二维码函数
function creatQrCodeCompany($value, $errorCorrectionLevel = 'L', $matrixPointSize = '7') {
    vendor('phpqrcode.qrlib');
    //$value    传入的值
    //$filename 文件名称
    //$errorCorrectionLevel 类型：L M Q H；
    //$matrixPointSize      大小  1 2 3 4 5 6 7 8 9 10 中的一个
    //$info=QRcode::png($value,$filename,$errorCorrectionLevel, $matrixPointSize, 2);
    //  $PNG_TEMP_DIR ='/Upload/'.'Qrcode'.'/';
    //根据时间创建目录 每天创建一个
    $time = date("Y-m-d", time());
    $PNG_TEMP_DIR = 'Uploads/QrCodeCompany/' . $time . '/';
    $now = strtotime($time);
    $PNG_WEB_DIR = 'Uploads/QrCodeCompany/' . $time . '/';
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $filename = $PNG_TEMP_DIR . 'test.png';
    $errorCorrectionLevel = $errorCorrectionLevel;
    $matrixPointSize = $matrixPointSize;
    if (isset($value)) {
        if (trim($value) == '')
            die('data cannot be empty! <a href="?">back</a>');
        $filename = $PNG_TEMP_DIR . '_Msj_' . md5($value . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
        $bb = QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    } else {
        echo '可能值为空所以生成失败！';
    }
    $qrcode_path = $PNG_WEB_DIR . basename($filename);
    return $qrcode_path;
}



/**
 * @todo 字符串、文件转换为二进制流
 * @author Claire<sunna@m-365.com>
 */
function bin2bstr($input) {

    if (!is_string($input))
        return null;

    $input = str_split($input, 4);
    $str = '';
    foreach ($input as $v) {
        $str .= base_convert($v, 2, 16);
    }

    $str = pack('H*', $str);

    return $str;
}

/**
 * @todo 二进制流还原为字符串、文件
 * @author Claire<sunna@m-365.com>
 */
function bstr2bin($input) {
    if (!is_string($input))
        return null;

    $value = unpack('H*', $input);

    $value = str_split($value[1], 1);
    $bin = '';
    foreach ($value as $v) {
        $b = str_pad(base_convert($v, 16, 2), 4, '0', STR_PAD_LEFT);

        $bin .= $b;
    }

    return $bin;
}



/**
 * @todo 指定字符之前的所有字符串
 * @auther zyh
 */
function substr_start($str, $sign = '-') {
    $n = strpos($str, $sign, 0); //寻找位置
    $str2 = substr($str, 0, $n);
    return $str2;
}

/**
 * @todo 指定字符之后的所有字符串
 * @auther zyh
 */
function substr_last($str, $sign = '-') {
    $site = strrpos($str, $sign);   // 找出字符串中字符最后出现的位置
    $site = $site + 1;
    $substr = substr($str, $site);   // 截取

    return $substr;
}

/**
 * @todo 永不重复随机串
 * @auther 
 */
function rands() {
    return "A" . time() . rand(1000, 9999) . "C";
}

/**
 * @todo 模拟提交数据函数
 * @author Claire <sunna@m-365.com>
 */
function vpost($url, $data) {
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        $tmpInfo = 'Errno' . curl_error($curl); //捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}


/**
 * @todo 打印数组结构(用于调试)
 * @param  array $arr
 * @return arrary 返回数组结构
 */
function p($arr){
	echo  '<pre>'.print_r($arr,true).'</pre>';
}
//获取盘符下站点根目录
function DocumentRoot(){
	if(!empty($_SERVER['DOCUMENT_ROOT'])){
		return $_SERVER['DOCUMENT_ROOT'];
	}else{
		return str_replace('\\','/',realpath(dirname(__FILE__).'/'));
	}
}
//获取区间内容
function ModStr($strstar,$strend,$str,$s=0) {
    $sstr=strstr($str,$strstar);
	if(empty($sstr)){ return false;}
	$sstr=substr($sstr,strlen($strstar));
	if($s==0){ $if=strpos($sstr,$strend); }else{ $if=strrpos($sstr,$strend); }//第一次//最后一次
	if($if){ return substr($sstr,0,$if); }else{ return false;}
}


/**
 * format_price           格式化价格
 * @param  [type] $price  价格
 */
function format_price($price){
    return "￥".sprintf("%.2f", substr(sprintf("%.3f",$price), 0, -1));
}


/**
 * 剩余时间
 */
function remain_time($remain_time){
    $d = intval($remain_time/86400);
    $h = intval(($remain_time%86400)/3600);
    $m = intval(($remain_time%3600)/60);    
    return $d.'天'.$h.'时'.$m.'分';
    
}


/**
 * @todo   send_sms      发送短信方法
 * @param  $send_to      目的手机号,多手机号用","分隔
 * @param  $message      短信内容之间用"*"连接
 * @param  $type         1通知类短信 2 个性群发短信 3 模版短信通道 对应（sendTemplateSMS 方法）
 * @param  $signature    短信签名 如【金湧博发】
 * @param  $datas_1      (验证码或用户名)
 * @param  $datas_2      (有效时间或充值金额)
 * @param  $tempId       模板ID
 * {
 *  已申请的模版id列表(参数) ：
 *  注册验证码:16228,array("验证码","有效时间X分钟")
 *  登录后修改绑定电话号码:16072,array("验证码","有效时间X分钟")
 *  登录后修改交易密码:16071,array("验证码","有效时间X分钟")
 *  登录后设置交易密码:16070,array("验证码","有效时间X分钟")
 *  登录后取现验证码:16066,array("验证码","有效时间X分钟")
 *  取现审核成功:16087,array("用户名")
 *  提现审核失败:16079,array("用户名")
 *  登录后充值成功通知:16073,array("用户名","充值金额")
 *  项目还款通知 16360   arr还款金额
 *  手机找回密码验证码 16365   arr验证码 、 有效时间
 *  注册成功提示 16353   arr用户名
 *  注册成功温馨提示 16354  arr用户名
 * }
 * @return  
 * 个性化返回参数：
 * $res string  
 *  0   发送成功
 * -1  用户名或密码错误
 * -2  发送短信余额不足
 * -6  参数有误
 * -7  权限受限
 * -8  Ip失败
 * -11 内部数据库错误 
 */
function send_sms($send_to,$message='',$tempstr="",$type=1){
 //    $ip = get_client_ip();
 //    $host = $_SERVER['HTTP_HOST'];    
 //    if($ip == '127.0.0.1' || $host != 'www.p2p222.com'){
 //       echo get_client_ip().'<br>'.$host;exit();
 //    }
 //    //通知类短信通道
 //    if($type == 3){   
 //    $message =urlencode($message.'【金湧博发】') ;//内容解码 
 //        $username = "SDK-JC-0054";//平台帐号
 //        $password = "HFEW40";    //平台密码        
 //        $url="http://114.215.202.188:8081/SmsAndMms/mt?";
 //        //$url="http://124.173.70.59:8081/SmsAndMms/mt?";//旧接口地址
 //        $curlPost = 'Sn='.$username.'&Pwd='.$password.'&mobile='.$send_to.'&content='.$message.'';        
 //        $ch = curl_init();//初始化curl
 //        curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
 //        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
 //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
 //        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //允许curl提交后,网页重定向  
 //        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
 //        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
 //        $data = curl_exec($ch);//运行curl
 //        curl_close($ch);              
 //    }

 //    //个性化短信通道
 //    if($type == 2){        
	// $message =urlencode($message.'【金湧博发】') ;//内容解码    
 //        $username = "jinchong";//平台帐号
 //        $password = "bvff12wz";    //平台密码        
 //        $res = file_get_contents("http://sms.4006555441.com/webservice.asmx/mt?Sn=$username&Pwd=$password&mobile=$send_to&content=$message");    //gxmt
 //        if($res == '0'){
 //            return true;
 //        }else{
 //            return false;
 //        }
 //    }
 //    //模版短信通道
 //    if($type == 1){
 //        $datas = explode(",",$message);        
 //        if($tempstr == ""){
 //            return false;
 //        }
 //        /*模版ID解析*/
 //        switch ($tempstr) {
 //            case '注册验证码':
 //                $tempId = 16228;
 //                break;
 //            case '修改绑定电话号码':
 //                $tempId = 16072;
 //                break;
 //            case '修改交易密码':
 //                $tempId = 16071;
 //                break;
 //            case '设置交易密码':
 //                $tempId = 16070;
 //                break;
 //            case '取现验证码':
 //                $tempId = 16066;
 //                break;                                                                            
 //            case '提现审核成功':
 //                $tempId = 16087;
 //                break;
 //            case '提现审核失败':
 //                $tempId = 16079;
 //                break;
 //            case '充值成功':
 //                $tempId = 16073;
 //                break;  
 //            case '项目还款通知':
 //                $tempId = 16360;
 //                break;  
 //            case '手机找回密码':
 //                $tempId = 16365;
 //                break;  
 //            case '注册成功提示':
 //                $tempId = 16353;
 //                break;  
 //            case '注册成功温馨提示':
 //                $tempId = 16354;
 //                break;                                                                                                                      
 //        }
 //        //对应（sendTemplateSMS 方法）
 //        if(!sendTemplateSMS($send_to,$datas,$tempId)){
 //            return false;
 //        }
 //    }
 //    return true;
}


 /**
  * @todo     send_mail       发送邮件方法   
  * @return   $mail_to        发送到的email
  * @return   $mail_author    发件人（站点名称）  
  * @return   $mail_subject   邮件主题
  * @return   $mail_content   邮件内容  
  */
 function send_mail($mail_to,$mail_author,$mail_subject,$mail_content,$status=1){
    import('Class.mail','./');
    $ip = get_client_ip();
    $host = $_SERVER['HTTP_HOST'];    
    // if($ip == '127.0.0.1' || $host != 'www.p2p222.com'){
    //     echo get_client_ip().'<br>'.$host;exit();
    // }
    switch ($status) {
        case '1':
            $username = "web@p2p222.com";            
            // $username = "356989639@qq.com";
            break;
        case '2':
            $username = "web01@p2p222.com";            
            break;
        case '3':
            $username = "web02@p2p222.com";            
            break;  
        case '4':
            $username = "web03@p2p222.com";            
            break;  
        case '5':
            $username = "web04@p2p222.com";            
            break;  
        case '6':
            $username = "web05@p2p222.com";            
            break;    
        case '7':
            $username = "web06@p2p222.com";            
            break;    
        case '8':
            $username = "web07@p2p222.com";            
            break;    
        case '9':
            $username = "web08@p2p222.com";            
            break;    
        case '10':
            $username = "web09@p2p222.com";            
            break;    
        case '11':
            $username = "web10@p2p222.com";            
            break;   
        case '12':
            $username = "web11@p2p222.com";            
            break;   
        case '13':
            $username = "web12@p2p222.com";            
            break;   
        case '14':
            $username = "web13@p2p222.com";            
            break;   
        case '15':
            $username = "web14@p2p222.com";            
            break;   
        case '16':
            $username = "web15@p2p222.com";            
            break;   
        case '17':
            $username = "web16@p2p222.com";            
            break;   
        case '18':
            $username = "web17@p2p222.com";            
            break;   
        case '19':
            $username = "web18@p2p222.com";            
            break;   
        case '20':
            $username = "web19@p2p222.com";            
            break;    
        case '21':
            $username = "web20@p2p222.com";            
            break;                                                                                                                                                                                                                                       
    }
    if($status <= 21){
        $smtp = new smtp("smtp.ym.163.com","25",true,$username,"jinyongbofa2014");
        // $smtp = new smtp("smtp.qq.com","25",true,$username,"DEngmaodeyu");
        if($smtp->sendmail($mail_to,$mail_author,$username, $mail_subject, $mail_content, "HTML")){
            return true;
        }else{        
            $new_status = $status + 1;
            send_mail($mail_to,$mail_author,$mail_subject,$mail_content,$new_status);
            return true;
        }
    }else{
        return false;
    }
 }



/**
 * @todo   insert_deal_log     插入到资金记录
 * @param  array()介绍如下：;
         *   $uid               用户ID
         *   $lid               项目ID
         *   $invested_id       投资ID
         *   $money             交易金额
         *   $type              交易类型：1充值 2投资 3提现 4还款本金 5还款利息 6管理费 7奖励金额 8系统操作 9邀请奖励 10积分兑换
         *   $pay_status        收支类型
         *   $detail            交易明细
         *   $create_time       交易时间
 */
 function insert_deal_log($data){
    if(!is_array($data)){
        return false;
    }
    $insert = M('deal_log')->addAll($data);
    if($insert !== false){
        return true;        
    }else{
        return false;
    }
 }


/**
 * @todo   insert_score_log     插入到积分记录
 * @param  array()介绍如下：;
         *   $from_uid          来源用户ID 
         *   $uid               用户ID
         *   $lid               项目ID
         *   $invested_id       投资ID
         *   $score             交易积分
         *   $type              交易类型：1签到 2邀请注册 3认证操作 4投资  5系统操作 6积分兑换
         *   $pay_status        收支类型
         *   $detail            交易明细
         *   $create_time       交易时间
 */
 function insert_score_log($data){
    if(!is_array($data)){
        return false;
    }
    $insert = M('score_log')->addAll($data);
    if($insert !== false){
        return true;        
    }else{
        return false;
    }
 } 


/**
 * @todo   insert_msg_box         插入到操作记录表
 * @param  array();
         *   $title               动作 标题
         *   $content             内容
         *   $user_id             操作人ID web_status=1时 对应tp_web_user主键,web_status=2时 对应tp_user主键
         *   $create_time         操作时间  
         *   $type                操作类型 1 账户操作 2 信息操作
         *   $web_status          操作状态 1 前台操作 2 后台操作
         *   $sql                 执行操作的SQL语句
 */
 function insert_msg_box($data){    
    if(!is_array($data)){
        return false;
    }
    $in_msg_box = M('msg_box')->add($data);    
    if($in_msg_box !== false){
        return true;        
    }else{
        return false;
    }
 }


/**
 * @todo   format_order_time    格式化充值订单日期
 * @param  $str                 需格式化的日期字符串
 * @return 带有格日期
 */
function format_order_time($str){
    $year = substr($str,0,4);
    $month = substr($str,4,2);
    $day = substr($str,6,2);
    $hour = substr($str,8,2);               
    $minute = substr($str,10,2);                        
    $second = substr($str,12,2);                        
    return $year."-".$month."-".$day." ".$hour.":".$minute.":".$second;
}


/**
 * @todo    deal_statistics          用户资金统计方法
 * @param   $uid                     用户ID
 * @return  array()
 *             key                    value
 *             money                  用户余额
 *             true_money             可用余额
 *             lock_money             冻结金额
 *             bonus_money            用户奖励总金额
 *             sum_income             累计总收益 (已收总额)
 *             sum_amount_all         累计投资总额              
 *             count_user_money       资产总额 
 *             sum_repay              待收总额 
 *             count_accrual          待收总利息 
 *             sum_amount             待收本金 
 *             sum_income_amount      已收本金 
 *             sum_income_accrual     收入利息
 *             money_ratio            可用余额占总额百分比 
 *             money_amount_ratio     在投本金占总额百分比 
 *             money_lock_ratio       冻结金额占总额百分比                                                                                                                    
 */
function deal_statistics($uid){
    if(empty($uid)){
        return false;
        exit();
    }
    $user = M('web_user')->where('id='.$uid)->find();
    $data['money'] = $user['money'];//用户余额    
    //可用余额 = 用户余额+奖励金额
    $data['true_money'] = $data['money'] + $user['bonus_money'];
    $data['lock_money'] = $user['lock_money'];//冻结金额
    $data['bonus_money'] = $user['bonus_money'];//用户奖励总金额 

    $investe_where['uid'] = $uid;
    $investe_where['status'] = 0;//状态为待收                
    $sum_invested = M('invested')->field("sum(repayment_amount) as sum_repay,SUM(amount) as sum_amount")->where($investe_where)->find();//获取用户在投本息                       
    $investe_ar = M('invested')->field('amount,repayment_amount')->where($investe_where)->select();
    foreach ($investe_ar as $key => $value) {
        $value['accrual'] = $value['repayment_amount'] - $value['amount'];//利息            
        $count_accrual += $value['accrual'];//待收总利息
    }  
    $income_where = array(
        'uid'=>$uid,
        'type'=>5,//交易类型：1充值 2投资 3提现 4还款本金 5还款利息 6管理费
        'pay_status'=>1//收支状态 1 收入 2 支出
    );

    //累计总收益
    $log_ar = M('deal_log')->field('sum(money) as sum_income')->where($income_where)->find();                
    if(empty($log_ar))
       $log_ar['sum_income'] = 0;   
    //累计总收益(已收总额)= 总还款利息-(总还款利息*管理费率)
    $data['sum_income'] = $log_ar['sum_income'] - ($log_ar['sum_income'] * 0.1);
    /*累计收益*/
    $sum_where = array(
        'uid'=>$uid,
         //所有从平台赚的钱
        'type'=>array('IN',array(5,7,9,10)),
        'pay_status'=>1        
    );
    $log_sum = M('deal_log')->field('sum(money) as sum_money')->where($sum_where)->find();        
    $data['sum_money'] = $log_sum['sum_money']?$log_sum['sum_money']:0;//累计收益        
    $sum_invested_all = M('invested')->field('sum(amount) as sum_amount_all')->where('uid='.$uid)->find();
    //累计投资总额  
    $data['sum_amount_all'] = $sum_invested_all['sum_amount_all']?$sum_invested_all['sum_amount_all']:0;
    //资产总额 = 待收总利息+冻结金额+可用余额
    $data['count_user_money'] = $count_accrual + $data['lock_money'] + $data['true_money'];        
    //待收总额
    $data['sum_repay'] = $sum_invested['sum_repay']?$sum_invested['sum_repay']:0;    
    //待收总利息
    $data['count_accrual'] = $count_accrual?$count_accrual:0;
    //待收本金
    $data['sum_amount'] = $sum_invested['sum_amount']?$sum_invested['sum_amount']:0;
    $income_amount_where = array(
        'uid'=>$uid,
        'type'=>4,//还款本金
        'pay_status'=>1//收入
    );        
    $log_amount = M('deal_log')->field('sum(money) as sum_income_amount')->where($income_amount_where)->find();        
    $income_accrual_where = array(
        'uid'=>$uid,
        'type'=>5,//还款利息(不含管理费)
        'pay_status'=>1//收入
    );        
    $log_accrual = M('deal_log')->field('sum(money) as sum_income_accrual')->where($income_accrual_where)->find();        
    $log_accrual['sum_income_accrual'] = $log_accrual['sum_income_accrual']?$log_accrual['sum_income_accrual']:0;
    //已收本金 
    $data['sum_income_amount'] = $log_amount['sum_income_amount']?$log_amount['sum_income_amount']:0;
    //收入利息  
    $data['sum_income_accrual'] = $log_accrual['sum_income_accrual'];
    //可用余额占总额百分比           
    $data['money_ratio'] = sprintf("%.2f", substr(sprintf("%.3f", $data['true_money'] / $data['count_user_money'] * 100), 0, -1));              
    //在投本金占总额百分比           
    $data['money_amount_ratio'] = sprintf("%.2f", substr(sprintf("%.3f", $data['sum_amount'] / $data['count_user_money'] * 100), 0, -1));                      
    //冻结金额占总额百分比           
    $data['money_lock_ratio'] = sprintf("%.2f", substr(sprintf("%.3f", $data['lock_money'] / $data['count_user_money'] * 100), 0, -1));  
    return $data;
}


/**
 * @todo    insert_bonus     写入奖励表
 * @param   $arr             
 *              bonus_money   奖励金额
 *              content       备注
 *              type          奖励类型：1 注册奖励 2 邀请奖励 3 认证奖励
 *              create_time   创建时间
 *              status        使用状态：0未使用 1已使用
 *              limit_days    有效期 单位（天）
 */
function insert_bonus($arr){    
    if(!is_array($arr)){
        return false;
    }
    M('bonus')->add($arr);    
}

/**
 * get_deal_log_type    获取资金交易类型
 * @param  [type] $type 数据库类型字段
 */
function get_deal_log_type($type){
    switch ($type) {
        case '1':
            $text = '充值成功';
            break;
        case '2':
            $text = '投资成功';
            break;
        case '3':
            $text = '提现';
            break;
        case '4':
            $text = '回收还款本金';
            break;
        case '5':
            $text = '回收还款利息';
            break;   
        case '6':
            $text = '扣除管理费';
            break;   
        case '7':
            $text = '奖励金额';
            break;   
        case '8':
            $text = '系统操作';
            break;   
        case '9':
            $text = '邀请奖励';
            break;   
        case '10':
            $text = '积分兑换';
            break;                                                                            
    } 
    return $text;
}



/**
 * get_score_log_type    获取积分交易类型
 * @param  [type] $type  数据库类型字段
 */
function get_score_log_type($type){
    switch ($type) {
        case '1':
            $text = '签到';
            break;
        case '2':
            $text = '邀请注册';
            break;
        case '3':
            $text = '认证操作';
            break;
        case '4':
            $text = '投资';
            break;
        case '5':
            $text = '系统操作';
            break;    
        case '6':
            $text = '积分兑换';
            break;  
        case '7':
            $text = '意见奖励';
            break;                                                             
    } 
    return $text;
}


/**
 * get_deal_log_pay_status    获取收支类型
 * @param  [type] $web_status 数据库收支类型字段
 */
function get_deal_log_pay_status($web_status){
    switch ($web_status) {
        case '1':
            $text = '收入';
            break;
        case '2':
            $text = '支出';
            break;                                                 
    } 
    return $text;
}



/**
 * @todo     update_score     修改会员积分
 * @param     $uid            会员ID
 * @param     $score          会员积分         
 */
function update_score($uid,$score){
    $db = M('web_user');
    $db->where('id='.$uid)->setInc("score",(int)$score);
}


/**
 * @todo     update_bonus_money     修改会员奖励金额
 * @param     $uid                  会员ID
 * @param     $money                奖励金额         
 */
function update_bonus_money($uid,$money){
    $db = M('web_user');
    $update = $db->where('id='.$uid)->setInc("bonus_money",(float)$money);
    if($update !== false){
        return true;
    }else{
        return false;
    }
}


/**
 * @todo    check_user_auth   计算会员安全等级
 * @param  [int] $user_id     会员ID
 * @return [int] $auth        安全比例
 */
function check_user_auth($user_id){  
  if(empty($user_id)){    
     return false;
     exit();
  }
  $user_info = M('web_user')->field('idno,is_phone,is_email,trade_pwd')->where('id='.$user_id)->find();  
  /*----安全等级 计算开始----*/
  $auth = 0 ;
  //实名认证
  if(!empty($user_info['idno'])){
      $auth += 1;
  }
  //手机认证
  if($user_info['is_phone'] == '1'){
      $auth += 1;
  }
  //邮箱设置
  if($user_info['is_email'] == '1'){
      $auth += 1;
  }
  //交易密码认证
  if(!empty($user_info['trade_pwd'])){
      $auth += 1;
  }   
  return $auth;
}



/**
 * @todo    insert_web_user_rate     写入会员附加利率
 * @param   $arr             
 *              uid                  会员ID
 *              add_rate             附加利率
 *              effect_deadline      有效期 单位：天
 *              deadline_time        到期时间(时间戳)
 *              use_status           使用状态  0 未使用 1 已使用
 *              create_time          创建时间
 */
function insert_web_user_rate($arr){    
    if(!is_array($arr)){
        return false;
    }
    $in_bonus = M('web_user_rate')->add($arr);    
    if($in_bonus !== false){
        return true;        
    }else{
        return false;
    }
}


/**
 * @todo  insert_system_alert  插入系统消息表数据(工作人员用)
 * @param  [array] $arr 
 */
function insert_system_alert($arr){    
    if(!is_array($arr)){
        return false;
    }
    $in_alert = M('system_alert')->add($arr);    
    if($in_alert !== false){
        return true;        
    }else{
        return false;
    }
}


/**
 * @todo   score_statistics    积分交易统计
 * @param  [int] $uid          用户ID  对应tp_web_user主键
 * @param  [int] $lid          项目ID 对应tp_loan表 主键
 * @param  [int] $invested_id  投资ID 对应tp_invested表主键
 * @param  [int] $type         交易类型：1签到 2邀请注册 3认证操作 4投资  5系统操作 6积分兑换 7意见奖励
 * @param  [int] $pay_status   收支状态  1收入 2支出
 * @return [type]              [description]
 */
function score_statistics($uid,$lid,$invested_id,$type,$pay_status,$check_status){
    $score_log_db = M('score_log');
    if(!empty($uid))
        $score_log_where['uid'] = $uid;
    if(!empty($lid))
        $score_log_where['lid'] = $lid;
    if(!empty($invested_id))
        $score_log_where['invested_id'] = $invested_id;
    if(!empty($type))
        $score_log_where['type'] = $type;
    if(!empty($pay_status))
        $score_log_where['pay_status'] = $pay_status;                            
    /*计算用户积分总和*/
    if($check_status == 'sum'){
        $score_log_field = 'sum(score) as sum_score';
        $score_res = $score_log_db->field($score_log_field)->where($score_log_where)->find();        
        $data['sum_score'] = $score_res['sum_score']?$score_res['sum_score']:0;
    }        
    return $data;
}



/**
 * @todo   retain_array     保留数组中指定个数的元素
 * @param  array   $ar      要截取的数组
 * @param  int     $len     要保留的元素个数
 * @return array          筛选后的结果
 * @author liying <472591847@qq.com>
 */
function retain_array($ar,$len){
  $i = 0;
  $ars = array();
  foreach ($ar as $key => $value) {
    $i++;
    if($i<= $len ){
      $ars[] = $value;
    }            
  }     
  if(is_array($ars)){
    return $ars;
  }else{
    return false;
  }
}
//
///短信验证码及通知函数
//$to：手机号码，$datas：替换内容数组，$tempId：模板ID 
//例：sendTemplateSMS("18604414340",array('1593','15'),16066); 
//已申请的模版id列表(参数) ：
//						注册验证码:16228,array("验证码","有效时间X分钟")
//						登录后修改绑定电话号码:16072,array("验证码","有效时间X分钟")
//						登录后修改交易密码:16071,array("验证码","有效时间X分钟")
//						登录后设置交易密码:16070,array("验证码","有效时间X分钟")
//						登录后取现验证码:16066,array("验证码","有效时间X分钟")
//						取现审核成功:16087,array("用户名")
//						提现审核失败:16079,array("用户名")
//						登录后充值成功通知:16073,array("用户名","充值金额")
function sendTemplateSMS($to,$datas,$tempId){
    import('Class.CCPRestSmsSDK','./');
	//主帐号,对应开官网发者主账号下的 ACCOUNT SID
	$accountSid= '8a48b5514c6a0c54014c6e1df1920253';
	//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
	$accountToken= '88d4582c1b724df993695280ad487fbe';
	//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
	//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
	$appId='8a48b5514c6a0c54014c73f2abd2069f';
	//请求地址
	//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
	//生产环境（用户应用上线使用）：app.cloopen.com
	$serverIP='app.cloopen.com';
	//请求端口，生产环境和沙盒环境一致
	$serverPort='8883';
	//REST版本号，在官网文档REST介绍中获得。
	$softVersion='2013-12-26';
     // 初始化REST SDK
     //global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
     // 发送模板短信
     //echo "Sending TemplateSMS to $to <br/>";
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     if($result == NULL ) {
         return false;
		 //echo "result error!";
         //break;
     }
     if($result->statusCode!=0) {
         return $result->statusCode;
         //return false;
		 //echo "error code :" . $result->statusCode . "<br>";
         //echo "error msg :" . $result->statusMsg . "<br>";
         //TODO 添加错误处理逻辑
     }else{
		 return true;
         //echo "Sendind TemplateSMS success!<br/>";
         // 获取返回信息
         //$smsmessage = $result->TemplateSMS;
         //echo "dateCreated:".$smsmessage->dateCreated."<br/>";
         //echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO 添加成功处理逻辑
     }
}
//
///语音验证码函数
//voiceVerify("接收号码","验证码内容","循环播放次数","显示的主叫号码","营销外呼状态通知回调
function voice_verify($to,$verifyCode,$playTimes = 3,$displayNum = '',$respUrl = ''){
	import('Class.CCPRestSDK','./'); 
	//主帐号
	$accountSid= '8a48b5514c6a0c54014c6e1df1920253';
	//主帐号Token
	$accountToken= '88d4582c1b724df993695280ad487fbe';
	//应用Id
	$appId='8a48b5514c6a0c54014c73f2abd2069f';
	//请求地址，格式如下，不需要写https://
	$serverIP='sandboxapp.cloopen.com';
	//请求端口 
	$serverPort='8883';
	//REST版本号
	$softVersion='2013-12-26';
	$rest = new REST($serverIP,$serverPort,$softVersion);
	$rest->setAccount($accountSid,$accountToken);
	$rest->setAppId($appId);
	//调用语音验证码接口
	//echo "Try to make a voiceverify,called is $to <br/>";
	$result = $rest->voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl);
	 if($result == NULL ) {
		 return false;
		//echo "result error!";
		//break;
	}
	
	if($result->statusCode!=0) {
		return false;
		//echo "error code :" . $result->statusCode . "<br>";
		//echo "error msg :" . $result->statusMsg . "<br>";
		//TODO 添加错误处理逻辑
	} else{
		return true;
		//echo "voiceverify success!<br>";
		// 获取返回信息
		//$voiceVerify = $result->VoiceVerify;
		//echo "callSid:".$voiceVerify->callSid."<br/>";
		//echo "dateCreated:".$voiceVerify->dateCreated."<br/>";
	   //TODO 添加成功处理逻辑
	} 
}
//////////////////////////////////////
///////////////////////程序锁（列队处理）
//////////////////////////////////////
function start_lock($action){ //打开文件
    $fp = fopen("./lock/".$action."Lock.txt", "w+");
    if($fp){
        return $fp ;
    }else{
        return false;
        die('Open failed.');
    }
}
function if_lock($file_lock){  // 进行排它型锁定
    if(flock($file_lock, LOCK_EX)){  
        return true ;
    }else{
        return false;
    }
}
function close_lock($file_lock){ //关闭文件锁定
    flock($file_lock, LOCK_UN);
    fclose($file_lock);
}

/**
 * @todo  trimall      去掉字符串空格
 */
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
    return str_replace($qian,$hou,$str);    
}
?>
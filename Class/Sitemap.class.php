<?php 
/*
 * -------------------------------------------------------------------------------
 * @编写者：方淞
 * @编写时间：2013-10-01
 * @联系方式：QQ：913623723
 * @类功能：Sitemap生成
 * @使用方法：dir_list($path);
 * @参数：路径
 * @返回值：数组
 * @说明：感谢互联网提供检索所有的目录的代码
 * -------------------------------------------------------------------------------
 */

class f {

    //网址
    private $weburl='http://www.p2p222.com/';
    //更新的频率
    private $frequency='weekly'; //always、hourly、daily、weekly、monthly、yearly、never
    //需要显示出来的扩展名
    private $kzm=array('php','html');
    //不显示的目录(如果总目录没有排除，内部目录可检索)
    private $nopath=array('Manage','Class','Common','TP','Ueditor','Conf','/Phone/Conf','/Phone/Common','/PC/Runtime','/Phone/Runtime');

    //路径的处理:::::::::::::::::::::::::::::::::::::::::::::::::::::
    private function dir_path($path) {
        $path = str_replace('\\', '/', $path);
        if (substr($path, -1) != '/') $path = $path . '/';
        return $path;
    }

    //目录&文件的处理(返回true不显示,false为显示)::::::::::::::::::::::
    private function dir_file($file) {
        if (is_dir($file)) {
            $stra = explode('/',$file);
            //判断路径中的深层次级别是否包含不显示的目录
            foreach ($this->nopath as $ss) {
                if (strstr($file,$ss)){
                    return true;
                    break;
                }
            }
            //判断一级路径中是否包含不显示的目录
            if (in_array($stra[1], $this->nopath)) {
                return true;
            } else {
                return false;
            }
        } else {
            $strb=substr(strrchr($file, '.'), 1);
            if (!in_array($strb,$this->kzm)) {
                return true;
            } else {
                return false;
            }
        }
    }

    //网址的级别:::::::::::::::::::::::::::::::::::::::::::::::::::::
    private function webLevel($weburl) {
        $url_a = explode('/',$weburl);
        $dian=array('1.0','0.9','0.8','0.7','0.6','0.5','0.4','0.3','0.2','0.1');
        $urls=$dian[count($url_a)-2];
        return $urls;
    }

    //列举:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    function dir_list($path, $exts = '', $list = array()) {
        //路径的处理------------------
        $path = $this->dir_path($path);
        //列举当前目录----------------
        $files = glob($path . '*');
        //目录&文件的处理-------------
        foreach($files as $v) {
            if (!($this->dir_file($v))) {    //如果false则显示文件或文件夹
                if (!$exts || preg_match("/\.($exts)/i", $v)) {
                    //数据(网址,时间,更新频率,优先级)
                    if (filemtime($v)) {
                        $webtime=date("Y-m-d",filemtime($v));
                    } else {
                        $webtime=date("Y-m-d",time());
                    }
                    $weblevel=$this->webLevel($v);
                    $list[] = array($v,$webtime,$this->frequency,$weblevel);
                    if (is_dir($v)) {
                        $list = $this->dir_list($v, $exts, $list);
                    }
                }
            }
        }
        //总数据处理并返回------------------
        $lists=array();
        foreach ($list as $s) {
            if (!is_dir($s[0])) {
                //$lists[]=$s;
                $lists[]=str_ireplace('./',$this->weburl,$s);
            }
        }
        return $lists;
    }

}
 ?>
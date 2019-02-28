<?PHP
class UploadAction extends CommonAction {
    /* 赋值Common中的权限处理*/
    //自动运行函数
    public function _initialize(){      
        parent::_initialize();
    }   

    /**
     * @todo   Upload      编辑器图片提交处理
     */
    public function Uploads() {        
        date_default_timezone_set("Asia/chongqing");
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");        
        //$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
        /*修改官方的读取JSON字符串，新增替换 \ 为空，才可以正常解析json 同时兼容php5.2*/
        $CONFIG = json_decode(str_replace("\\" , "" , preg_replace("/\/\*[\s\S]+?\*\//" , "" , file_get_contents("ueditor/php/config.json"))),true);
        $action = $_REQUEST['action'];
        //echo APP_PATH;die;
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;
        
                /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                //$result = include("action_upload.php");
                import('ORG.Net.UploadFile');                                
                $upload = new UploadFile();
                $upload->autoSub = true;
                $upload->subType = 'date';
                $upload->dateFormat = 'Ym';
                if ($upload->upload('./Uploads/')){
                    $info = $upload->getUploadFileInfo();
                    import('Class.Image','./'); 
					//$WATER_IMAGE = (strstr(C('WATER_IMAGE'),__ROOT__)) ? '.'.str_replace(__ROOT__,'',C('WATER_IMAGE')) : '.'.C('WATER_IMAGE') ;
                    //Image::water('./Uploads/'.$info[0]['savename'], $WATER_IMAGE);
                    echo json_encode(array(
                            'url'        =>    __ROOT__.'/Uploads/'.$info[0]['savename'],
                            'title'        =>    htmlspecialchars($_POST['pictitle'], ENT_QUOTES),
                            'original'    =>    $info[0]['name'],
                            'state'        =>    'SUCCESS'
                            ));
                    
                }else{
                    echo json_encode(array(
                            'state'    => $upload->getErrorMsg(),
                            ));
                }
                break;
        
                
        }
        
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                        'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
    /**
     * @todo UploadOne      单个图片上传处理(用于传统上传)
     * @param $dir_name     图片文件夹名称
     * @author Liying 
     */
    public function UploadOne($dir_name){ 
        /*图片上传目录定义*/
        $dir_name = ($dir_name)?$dir_name:"/";
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        $upload->autoSub = true;
        $upload->subType = 'date';
        $upload->dateFormat = 'Ym';
        $data = array();
        if ($upload->upload('./Uploads'.$dir_name)) {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $data['img_url'] = __ROOT__.'/Uploads'.$dir_name.$uploadList[0]['savename'];           
            $data['status'] = '1';
        }else{
            $data['status'] = '0';
            $data['error_info'] = $upload->getErrorMsg();
        }
        return $data;
    }    
}
?>
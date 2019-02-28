<?php 
/**
 * @todo            系统设置控制器
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-8-26
 */
class SystemAction extends UploadAction{
	/**
	 * @todo System   系统设置视图
	 */
	public function System(){			
		$this->main_title = "系统设置";
		$this->display();
	}
	/**
	 * @todo UpdateSystem   系统设置处理
	 */
	public function UpdateSystem(){	
		$update = array();		
		$update = $_POST;
		//使用commonAction中的传统图片上传方法			
		$img_data = $this->UploadOne("/water_image/");
		$img_url = $img_data['img_url'];
		if($img_url){
			//删除不用的图片文件
			$old_img = $_SERVER['DOCUMENT_ROOT'].C("WATER_IMAGE");
			if(is_file($old_img)){
				@unlink($old_img);
			}
			$update['WATER_IMAGE'] = $img_url;
		}else{
			$update['WATER_IMAGE'] = C("WATER_IMAGE");
		}
		if(F('system',$update,CONF_PATH)){
			//同时修改手机站配置
			F('system',$update,'./m/Phone/Conf/');			
			unset($update);
			$this->success('修改成功',U(GROUP_NAME.'/System/system'));
		}else{
			unset($update);
			$this->error('修改失败请修改'.CONF_PATH.'system.php权限');
		}
	}

	
}
 ?>
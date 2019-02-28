<?php
class category{
	//组合一维数组
	static public function UnlimitedForLevel($cate,$html = '├─',$pid = 0,$level = 0){
		$arr=array();
		foreach($cate as $v){
			if($v['pid'] == $pid){
				$v['level'] = $level + 1 ;
				$v['html'] = $html;
				$arr[] = $v;
				$arr = array_merge($arr, self::UnlimitedForLevel($cate,"丨".$html,$v['id'],$level+1));
			}
		}
		return $arr;
	}
	//组合多维数组("Rbac/Node"用了access方法)
	static public function UnlimitedForLayer($cate,$access = NULL,$name = 'child',$pid = 0){
		$arr=array();
		foreach($cate as $v){
			if(is_array($access)){
				$v['access'] = in_array($v['id'],$access) ? 1 : 0 ;
			}
			if($v['pid'] == $pid){
				$v[$name] = self::UnlimitedForLayer($cate,$access,$name,$v['id']);
				$arr[] = $v;
			}
		}
		return $arr;
	}
	//传递子分类id获取全部父级分类
	static public function GetParents($cate,$id){
		$arr=array();
		foreach($cate as $v){
			if($v['id'] == $id){
				$arr[] = $v;
				$arr = array_merge(self::GetParents($cate,$v['pid']),$arr);
			}
		}
		return $arr;
	}
	//传递父级分类id获取全部子分类
	static public function GetChilds($cate,$pid){
		$arr=array();
		foreach($cate as $v){
			if($v['pid'] == $pid){
				$arr[] = $v['id'];
				$arr=array_merge($arr,self::GetChilds($cate,$v['id']));
			}
		}
		return $arr;
	}
	
}
?>
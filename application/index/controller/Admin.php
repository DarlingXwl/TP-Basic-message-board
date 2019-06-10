<?php
namespace app\index\controller;

use think\Controller;
use think\View;

class Admin  extends Controller
{
    public function index()
    {
		//调用函数
		$arr = show("./mysql.txt");
		if($arr){
			$newarr=array_reverse($arr);
		}else{
			$newarr= array(array("没有数据","",""));
		}
	
		//获取页数
		if(isset($_GET['page'])){
			$page=$_GET['page'];
		}else{
			$page=1;
		}
		//前一页
		$prepage=$page-1;
		if($prepage==0){
			$prepage=1;
		}
		//最后一页
		$lastpage=ceil(count($newarr)/8);
		//下一页
		$nextpage=$page+1;
		if($lastpage < $nextpage){
			$nextpage=$lastpage;
		}
			//截取的数组的开始的下标
		$start=($page-1)*5;
			//截取数组
		$arr=array_slice($newarr,$start,5);
		//传值
		$this->assign('page',$page);
		$this->assign('prepage',$prepage);
		$this->assign('nextpage',$nextpage);
		$this->assign('lastpage',$lastpage);
		$this->assign('arr',$arr);
		
		return $this->fetch('admin');
		
	}
	public function delete()
    {
    	if(isset($_GET['id'])){	
    		//获取数据		
			$page=$_GET['page'];
			$id=$_GET['id'];
			//计算删除数组的下标
			$id=(($page-1)*8)+$id-1;
			//一次分割数组
			$arr = show1("./mysql.txt");
			//判断返回值是否为空
			if($arr){
				array_pop($arr);
				$newarr=array_reverse($arr);
				unset($newarr[$id]);
				$arr=array_reverse($newarr);
				$str=implode("*_*",$arr);
				if(strlen($str)!==0){
					$str=$str."*_*";
				}
				$result=file_put_contents("./mysql.txt",$str);				
				echo "删除成功！等待三秒后自动返回首页";
				echo '<meta http-equiv="refresh" content="3; url=./" />';
				echo '<a href="./">首页</a>';			
			}else{
				echo "文件内容更为空,等待三秒后自动返回首页";
				echo '<meta http-equiv="refresh" content="3; url=./" />';
				echo '<a href="./">首页</a>';
			}	
		}
    }
}

function show($file){
	 	//获取资源
	if(file_exists($file)){
	 	$str = file_get_contents($file);
		if($str==""){
			return FALSE;
		}
		//分割字符串
		$arr = explode("*_*",$str);
	 	//判断数组是否为空
	 	if(count($arr)==0){
	 		return FALSE;
	 	}
		 	//二次分割
		for($i = 0 ; $i<count($arr)-1; $i++){
	 		$newarry[$i] = explode("^_^",$arr[$i]);
	 	}
	 	return $newarry;
	}else{
	 	return FALSE;
	}
	 	
}
function show1($file){
	 	//获取资源
	if(file_exists($file)){
	 	$str = file_get_contents($file);
		if($str==""){
			return FALSE;
		}
		 	//分割字符串
		$arr = explode("*_*",$str);
		 	//判断数组是否为空
		if(count($arr)==0){
			return FALSE;
		}
		return $arr;
	}else{
		return FALSE;
	}
	 	
}
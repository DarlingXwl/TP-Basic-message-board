<?php
namespace app\index\controller;

use think\Controller;
use think\View;

class Index  extends Controller
{
    public function index(){
		    	//调用函数
		$arr = show("./mysql.txt");
		if($arr){
			$newarr=array_reverse($arr);
		}else{
			$newarr= array(array("没有数据","",""));
		}
    		//设置时区
		ini_set('date.timezone','Asia/Shanghai');
		//获取页数
		if(isset($_GET['page'])){
			$page=$_GET['page'];
		}else{
			$page=1;
		}
		//前一页
		$prepage=$page-1;
		if($prepage<=1){
			$prepage=1;
		}
		//最后一页
		$lastpage=ceil(count($newarr)/5);
		//下一页
		$nextpage=$page+1;
		if($nextpage>$lastpage){
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

		if(isset($_POST['username'])){
			//获取数据
			$username = $_POST['username'];
			$message = $_POST['message'];
			$username=trim($username);
			$message=trim($message);
			if(strlen($username)==0||strlen($message)==0){
				echo "请输入正确的用户名与留言！等待三秒后自动返回首页";
				echo '<meta http-equiv="refresh" content="3; url=./index" />';
				echo '<a href="./index">首页</a>';
			}else{
								//时间格式
				$date = date("Y-m-d H:i:s");
				
				//拼接数据
				$str = $username.'^_^'.$message.'^_^'.$date.'*_*';
				
				//打开文件
				$handle = fopen('./mysql.txt','a');
				
				//写入数据
				$bool = fwrite($handle,$str);
				
				//判断
				if($bool){
					echo "留言成功！等待三秒后自动返回首页";
					echo '<meta http-equiv="refresh" content="3; url=./index" />';
					echo '<a href="./index">首页</a>';
				}else{
					echo "留言失败！";
				}
				//关闭数据流
				fclose($handle);
			}

			
		}else{
			//跳转
			return $this->fetch('index');
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
		 	$newarry=[];
		 	for($i = 0 ; $i<count($arr)-1; $i++){
		 		$newarry[$i] = explode("^_^",$arr[$i]);
		 	}
		 	return $newarry;
	 	}else{
	 		return FALSE;
	 	}
	 	
 }

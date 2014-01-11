<?php
include('lib/DBHelper.php');
header("content-type:text/html; charset=utf-8");
session_start();
if(array_key_exists('uid', $_SESSION)){
		
	$si = $_SESSION['uid'];
	$validate = true;
	$error = '';
	if(!array_key_exists('td',$_POST)||strlen($_POST['td'])==0||preg_match("/^\d*$/",$_POST['td'])==0){
		$validate = false;
		$error += 'td value incorrect: '.$_POST['td'].'<br/>';
	}
	else{
		$thread = intval($_POST['td']);
	}
	if(!array_key_exists('pn',$_POST)||strlen($_POST['pn'])==0||preg_match("/^\d*$/",$_POST['pn'])==0){
		$validate = false;
		$error += 'pn value incorrect: '.$_POST['pn'].'<br/>';
	}
	else{
		$pn = intval($_POST['pn']);
	}
	if(!array_key_exists('tu',$_POST)||strlen($_POST['tu'])==0||preg_match("/^\d*$/",$_POST['tu'])==0){
		$validate = false;
		$error += 'tu value incorrect: '.$_POST['tu'].'<br/>';
	}
	else{
		$ti = intval($_POST['tu']);
	}
	if(!array_key_exists('content',$_POST)||strlen($_POST['content'])==0){
		$validate = false;
		$error += 'content value incorrect: '.$_POST['content'].'<br/>';
	}
	else{
		mb_internal_encoding("UTF-8");
		$content = mb_substr($_POST['content'],0,300);
		// filter the special words:
		$content = str_replace("'","&apos;",$content);
		$content = str_replace("\"","&quot;",$content);
		$content = str_replace("<","&lt;",$content);
		$content = str_replace(">","&gt;",$content);
		$sql = "SELECT nickname FROM cl_user WHERE id=$ti;";
		$res = DBHelper::execsql($sql);
		$arr = $res->fetch_assoc();
		$tnn = $arr['nickname'];
		$content = "<a href=#$ti >回复$tnn:</a>".$content;
	}
	if($validate){
		$now = new DateTime('now');
		$now = $now->format("Y-m-d H:i:s");
		
		$sql = "INSERT INTO reply (si,ti,content,display,time,thread,mark) VALUES ($si,$ti,'$content',1,'$now',$thread,0);";
		DBHelper::execsql($sql);
		// set the trigger
		$sql = "UPDATE cl_user SET coin=coin+5 WHERE id=$si;";
		DBHelper::execsql($sql);
		$sql = "UPDATE cl_user SET message=message+1 WHERE id=$ti;";
		DBHelper::execsql($sql);	
		$sql = "UPDATE thread SET replyCount=replyCount+1 WHERE id=$thread;";
		DBHelper::execsql($sql);	
		//return to the previous page:
		echo "SUCCESS OF REPLYING FOR THE TOPIC!您已经成功提交回复内容，正在返回刚才的页面！";
		header('Location:content.php?td='.$thread.'&pn='.$pn);
	}	
	else{
		echo "500 REPLY DATA IS UNACCEPTABLE!提交失败！提交数据有错！";
		return;
	}
}
else{
	echo "PLZ LOGIN THEN SUBMIT REPLY! 请先登录在提交回复内容！";
	return;
}

?>

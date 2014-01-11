<?php
include('lib/DBHelper.php');
header("content-type:text/html; charset=utf-8");
session_start();
if(array_key_exists('uid', $_SESSION)){
		
	$launcher = $_SESSION['uid'];
	$validate = true;
	$error = '';
	if(!array_key_exists('tp',$_POST)||strlen($_POST['tp'])==0||preg_match("/^\d*$/",$_POST['tp'])==0){
		$validate = false;
		$error += 'tp value incorrect: '.$_POST['tp'].'<br/>';
	}
	else{
		$tp = intval($_POST['tp']);
		if($tp<1||$tp>6){$tp=1;}
	}
	
	if(!array_key_exists('pn',$_POST)||strlen($_POST['pn'])==0||preg_match("/^\d*$/",$_POST['pn'])==0){
		$validate = false;
		$error += 'pn value incorrect: '.$_POST['pn'].'<br/>';
	}
	else{
		$pn = intval($_POST['pn']);
	}
	
	if(!array_key_exists('topic',$_POST)||strlen($_POST['topic'])==0){
		$validate = false;
		$error += 'content value incorrect: '.$_POST['topic'].'<br/>';
	}
	else{
		mb_internal_encoding("UTF-8");
		$topic = mb_substr($_POST['topic'],0,300);
		// filter the special words:
		$topic = str_replace("'","&apos;",$topic);
		$topic = str_replace("\"","&quot;",$topic);
		$topic = str_replace("<","&lt;",$topic);
		$topic = str_replace(">","&gt;",$topic);
	}
	if($validate){
		$now = new DateTime('now');
		$now = $now->format("Y-m-d H:i:s");
		
		$sql = "INSERT INTO thread (topic,display,launcher,launchTime,type,replyCount) VALUES ('$topic',1,'$launcher','$now',$tp,0);";
		DBHelper::execsql($sql);
		// set the trigger
		$sql = "UPDATE cl_user SET coin=coin+5 WHERE id=$launcher;";
		DBHelper::execsql($sql);
		//return to the previous page:
		echo "SUCCESS OF REPLYING FOR THE TOPIC!您已经成功提交回复内容，正在返回刚才的页面！";
		header('Location:forum.php?tp='.$tp.'&pn='.$pn);
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

<?php
include('lib/DBHelper.php');
header("content-type:text/html; charset=utf-8");

if(array_key_exists('uname', $_POST)&&array_key_exists('password', $_POST)){
	if(array_key_exists('last', $_POST)){
		$last = $_POST['last'];
	}
	else{
		$last = 'index.php';
	}
	$name = $_POST['uname'];
	$pwd = $_POST['password'];
	$sql = "SELECT id,password FROM cl_user WHERE name='$name' LIMIT 1;";
	$res = DBHelper::execsql($sql);
	
	if(mysqli_num_rows($res)==0){
		echo "SORRY! USER [".$name."] NOT REGISTERED YET!<font color=RED>该用户不存在，请先<a href='signup.php'>注册！</a></font>";
	}
	else{
		$arr = $res->fetch_assoc();
		if($arr['password']==$pwd){
			echo "LOGIN SUCCESS! 您已经成功登录，现在跳转到登录后的首页!";	
			// update user's information:
			$now = new DateTime('now');
			$now = $now->format('Y-m-d H:i:s');
			$sql = "UPDATE cl_user SET lastActive='$now' WHERE id=".$arr['id'].";";
			session_start();
			$_SESSION['uid'] = $arr['id'];
			header('Location:'.$last);
		}
		else{
			echo "PASSWORD NOT MATCH! 密码错误!";
		}
	}
	
}
else{header('Location:index.php');}

?>

<?php 

include('lib/DBHelper.php');
	include('lib/DateTimeHelper.php');
	
	header("content-type:text/html; charset=utf-8");
	session_start();
	
	if(array_key_exists('uid', $_SESSION)){
		$uid = $_SESSION['uid'];
		if(!array_key_exists('uid',$_GET)||strlen($_GET['uid'])==0||preg_match("/^\d*$/",$_GET['uid'])==0){
			echo "404 NOT FOUND! ERROR IN PARAMETERS OF POST DATA!";
			return;
		}
		else{
			$fid = $_GET['uid'];
			if($fid==$uid){
				echo "U CAN'T ADD YOURSELF AS A FRIEND! THAT'S REDICULOUS!";
				return;
			}
			else{
				$sql = "SELECT COUNT(*) AS count FROM cl_user WHERE id=$fid LIMIT 1;";
				$res = DBHelper::execsql($sql);
				$arr = $res->fetch_assoc();
				if(intval($arr['count'])==0){
					echo "您这位好友好像不存在吧？";
					return;
				}
				else{
					$sql = "SELECT COUNT(*) AS count FROM friendship WHERE (u1id=$fid AND u2id=$uid) OR (u2id=$fid AND u1id=$uid) LIMIT 1;";
					$res = DBHelper::execsql($sql);
					$arr = $res->fetch_assoc();
					if(intval($arr['count'])>0){
						echo "你们已经是好友了，不能再次添加对方为好友！";
						return;
					}
					else{
						$sql = "INSERT INTO friendship (u1id,u2id,close) VALUES ($uid,$fid,3)";
						DBHelper::execsql($sql);
						echo "添加好友成功！";
						header("Location:myzone.php?uid=$fid");
					}
				}
			}
		}
		
	}
	else{
		echo "404 NOT FOUND! PLZ LOGIN BEFORE REQUEST FOR RESOURCES!请<a href='index.php'>登录</a>或<a href='signup.php'>注册</a>。";
	}

?>

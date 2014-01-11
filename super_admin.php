<?php
	session_start();
	// check if the user is in the session:
	if(!is_string($_GET['sad']))
	{
		echo "404! NOT FOUND! 您所想找的页面不存在!";
		return;
	}
	else{
		$sad = $_GET['sad'];
		if((!is_string($_SESSION[$sad]))||$_SESSION[$sad]!='super-admin-login'){
			echo "404! NOT FOUND! 您所想找的页面不存在!";		
			return;
		}	
	}

	include('lib/DBHelper.php');
	include('lib/encode.php');
	
	// for the request process part
	$sql = "SELECT id,name,email,phone FROM admin WHERE id IN (SELECT admin_id FROM req4admin WHERE mark=0)";
	$res = DBHelper::execsql($sql);
	// for the remain part
	$sql = "SELECT id,name,email,phone FROM admin WHERE forbidden=0";
	$res1 = DBHelper::execsql($sql);
?>
<!DOCTYPE html>
<!--STATUS OK-->
<html>
	<head>
		<title>超级管理员界面</title>
		<meta charset="utf-8" />
		<title>秉烛论坛</title>
		<meta name="keywords" content="秉烛,论坛,秉烛论坛,大学,大学生,forum,christianland,mainland,student,study,university" />
		<meta name="description" content="这是一个为大陆大学生服务的论坛 This is a forum specially serves the university students from mainland" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta http-equiv="mobile-agent" content="format=xhtml; url=http://m.christianland.com" />
		
		<style typpe='text/css'>
			.record{background-color:rgba(20,20,10,0.5);}
		</style>
		
	</head>
	<body>
		<p>这些是需要您给他权利的人：</p>
		<form action='grant.php'>
		<ul style='list-style-type:none;color:#FFF;'>
			<?php
				while($arr = $res->fetch_assoc()){
					echo "<li class='record'><div style='color:#FFF;background-color:#000000;'>name: ".$arr['name']." </div> <div style='color:#FFF;background-color:#00aaee;'>email: ".$arr['email']." </div> <div style='color:#FFF;background-color:#ee0000;'>phone: ".$arr['phone']."</div>Grant Him the Administration?<input name='index' type='checkbox' value='".$arr['id']."' /></li>";
				}
			?>
		</ul>
		<input type='hidden' name='sad' value='<?php echo $sad; ?>' />
		<input type='hidden' name='ids' value='' />
		<div style='width:60px;height:30px;margin:0px auto;background-color:#ff5500;color:#FFF;cursor:pointer;'><input type='submit' name='grant_ids' value='submit' onclick='setData();' style='width:60px;height:30px;margin:0px auto;background-color:#ff5500;color:#FFF;cursor:pointer;' /></div>
		</form>
		
		<p>这些是您可以剥夺他权利的人：</p>
		<form action='disgrant.php'>
		<ul style='list-style-type:none;color:#FFF;'>
			<?php
				while($arr1 = $res1->fetch_assoc()){
					echo "<li class='record'><div style='color:#FFF;background-color:#000000;'>name: ".$arr1['name']." </div> <div style='color:#FFF;background-color:#00aaee;'>email: ".$arr1['email']." </div> <div style='color:#FFF;background-color:#ee0000;'>phone: ".$arr1['phone']."</div>Grant Him the Administration?<input name='index1' type='checkbox' value='".$arr1['id']."' /></li>";
				}
			?>
		</ul>
		<input type='hidden' name='sad' value='<?php echo $sad; ?>' />
		<input type='hidden' name='ids' value='' />
		<div style='width:60px;height:30px;margin:0px auto;background-color:#ff5500;color:#FFF;cursor:pointer;'><input type='submit' name='grant_ids' value='submit' onclick='setData1();' style='width:60px;height:30px;margin:0px auto;background-color:#ff5500;color:#FFF;cursor:pointer;' /></div>
		</form>
		
	</body>
	<script>
	function setData(){
		var id = document.getElementsByName('index');
		var ids = document.getElementsByName('ids')[0];
		
		var len = id.length;
		var arr = [];
		for(var i=0;i<len;i++){
			if(id[i].checked){arr.push(id[i].value);}
		}
		ids.value = arr.join(',');
	};
	function setData1(){
		var id = document.getElementsByName('index1');
		var ids = document.getElementsByName('ids')[1];
		
		var len = id.length;
		var arr = [];
		for(var i=0;i<len;i++){
			if(id[i].checked){arr.push(id[i].value);}
		}
		ids.value = arr.join(',');
	};
	</script>
<html>

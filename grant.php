<?php
	session_start();
	// check if the user is in the session:
	if(!is_string($_GET['sad']))
	{
		echo "404! NOT FOUND!";
		return;
	}
	if(!is_string($_GET['ids']))
	{
		echo "404! NOT FOUND!";
		return;
	}
	else{
		$sad = $_GET['sad'];
		if((!is_string($_SESSION[$sad]))||$_SESSION[$sad]!='super-admin-login'){
			echo "404! NOT FOUND! OUT OF SESSION!";		
			return;
		}	
	}
	$ids = $_GET['ids'];

	include('lib/DBHelper.php');
	
	// for the request process part
	$sql = "UPDATE admin SET forbidden=0 WHERE id in (".$ids.")";
	$res = DBHelper::execsql($sql);
	// mark those processed 
	$sql = "UPDATE req4admin SET mark=1 WHERE admin_id in (".$ids.")";
	$res = DBHelper::execsql($sql);
	
	$sql = "SELECT name FROM admin WHERE id in (".$ids.")";
	$res = DBHelper::execsql($sql);
	while($arr=$res->fetch_assoc()){
		echo $arr['name']."----";
	}
	echo "Has been granted with administration!";
?>

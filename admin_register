<?php
	include('lib/DBHelper.php');
	
	$uname_error="";
	$password_error="";
	$validate_error="";
	$email_error="";
	$phone_error="";
	
	$legal = true;
	if(!array_key_exists('unm',$_GET)){ $legal=false; }
	if(!array_key_exists('email',$_GET)){ $legal=false; }
	if(!array_key_exists('upwd',$_GET)){ $legal=false; }
	if(!array_key_exists('vldt',$_GET)){ $legal=false; }
	if(!array_key_exists('phone',$_GET)){ $legal=false; }
	
	if($legal)
	{
		session_start();
		
		$uname = $_GET['unm'];
		$email = $_GET['email'];
		$password = $_GET['upwd'];
		$validate = $_GET['vldt'];
		$phone = $_GET['phone'];
		
		if(!is_string($phone)){$phone='-';}
	
		// check
		if(is_string($uname)&&is_string($password)&&is_string($validate)
		&&strlen($uname)&&strlen($password)&&is_string($email))
		{
			//check validate code
			$std = $_SESSION['admin-register'];
			if($std!=$validate)
			{
				$validate_error= '<font color=red>*</font>wrong';
			}
		
			else
			{
				//check uname and password
		
				//format the strings:
				$uname = str_replace("\"","0",$uname);
				$email = str_replace("\"","0",$email);
				$password = str_replace("\"","0",$password);
				$phone = str_replace("\"","0",$phone);
				$uname = str_replace("'","0",$uname);
				$email = str_replace("'","0",$email);
				$password = str_replace("'","0",$password);
				$phone = str_replace("'","0",$phone);
			
				$sql= "select password from admin where name="."\"".$uname."\"";
				$res= DBHelper::execsql($sql);
		
				//if the user doesn't exist
				if(mysqli_num_rows($res)>0)
				{
					$uname_error= '<font color=red>*</font>taken';
				}
				else
				{
					// register the user
					$sql= "INSERT INTO admin (name,password,email,phone,forbidden) VALUES ('".$uname."','".$password."','".$email."','".$phone."',1);";
					$res= DBHelper::execsql($sql);
					// send the request to the super administrator
					$sql= "SELECT id FROM admin WHERE name='".$uname."' LIMIT 1;";
					$res= DBHelper::execsql($sql);
					if(mysqli_num_rows($res)==0)
					{
						$uname_error= '<font color=red>*</font>failed';
					}
					else{
						$arr= $res->fetch_assoc();
						$admin_id= $arr['id'];
						$sql= "INSERT INTO req4admin(admin_id,mark) VALUES(".$admin_id.",0)";
						DBHelper::execsql($sql);
					
						header("Location: wait.htm");	
					}
				}
			}
		}	
	}

?>
<!DOCTYPE html>
<!--STATUS OK-->
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<title>管理员注册</title>
		<script type='text/javascript' src='js/jquery.1.6.4.js' ></script>
		<script type='text/javascript'>
		
		function submitForm()
		{
			var un = $('#name').val();
			var pwd = $('#pwd').val();
			var val = $('#vdcode').val();
			var eml = $('#email').val();
			var phone = $('#phone').val();

			var msghead = '请您填写一下';
			var msgbody = '';
			var msgtail = ' ^_^ ';
			var error = 0;
			
			if(un==""||un==null)
			{
				msgbody += ' 用户名 ';
				error += 1;
			}
			if(pwd==""||pwd==null)
			{
				msgbody += ' 密码 ';
				error += 1;
			}
			if(val==""||val==null)
			{
				msgbody += ' 验证码 ';
				error += 1;
			}
			if(eml==""||eml==null)
			{
				msgbody += ' 邮件地址 ';
				error += 1;	
			}

			if(error>0){ alert(msghead+msgbody+msgtail); return false; }
		}
		
		function switchpwd()
		{
			var chkbox = document.getElementById("switch");
			var pwdbox = document.getElementById("pwd");
			if(chkbox.value==0){pwdbox.type="text";chkbox.value=1;}
			else {pwdbox.type="password"; chkbox.value=0;}
		}		
		</script>
		<style>
		a:link {color:#000;}      /* unvisited link */
		a:visited {color:#00FF00;}  /* visited link */
		a:hover {color:#555;}  /* mouse over link */
		a:active {color:#0000FF;}  /* selected link */ 
		*{outline:none;border:0px;}
		input{background-color:#ddd;height:20px;}
		input:focus{background-color:#aae;}
		body{background-image:url(pictures/login_bg.jpg);}
		
		.container{width:500px; height:380px;margin:50px auto 0px auto;background-color:rgba(0,0,0,0.5);color:#fff;}
			.title{width:200px;height:30px;margin:50px auto 0px auto;color:#fff;font-weight:bold;padding-top:20px;}
			.seg{padding:5px 10px 5px 10px;}
			.right-text{text-align:right;}
			.login-sheet{width:500px;height:100px;margin:0px auto 0px auto;color:#fff;}
			.center{width:72px;height:28px;margin:10px auto 0px auto;}
			.submit{width:50px;height:30px;margin:30px auto 0px auto;}
			.error{color:#ffffff;}
		</style>
	</head>
	<body>
			<form id="mFormId" class='container' name="mForm" action='admin_register.php' onsubmit="return submitForm();">
				<p class='title'><a href='admin_login.php' style='color:#763397; text-decoration:none;' >管理员登录</a> | 管理员注册</p>
				
				<table class='login-sheet'>
					<tbody>
						<tr><td class='seg right-text'><必填>用户名：</td><td><input type="text" id="name" name="unm" /></td><td class='error' ><?php echo $uname_error ?></td></tr>
						<tr><td class='seg right-text'><必填>邮件地址：</td><td><input type="text" id="email" name="email" /></td><td class='error' ><?php echo $email_error ?></td></tr>
						<tr><td class='seg right-text'>联系电话：</td><td><input type="text" id="phone" name="phone" /></td><td class='error' ><?php echo $phone_error ?></td></tr>
						<tr><td class='seg right-text'><必填>设置密码：</td><td><input type="password" id="pwd" name="upwd" class="text" /></td><td class='error' ><?php echo $password_error ?></td></tr>
						<tr><td class='seg right-text'>显示密码：</td><td><input type="checkbox" id='switch' value='0' onclick='return switchpwd();' /></td><td class='error' ></td></tr>	
						<tr><td class='seg right-text'><必填>验证码：</td><td><input id="vdcode" type="text" name="vldt"/></td><td class='error' ><?php echo $validate_error ?></td></tr>				
					</tbody>
				</table>
				<div class='center'><a href="#"><img id="vdimgck" align="absmiddle" onClick="this.src=this.src+'#'" style="cursor: pointer;" title="看不清？点击更换" src="validatecode.php?source=admin-register" alt='点击重新加载验证码' /></a></div>
				
				<div class='submit'>
					<input type='submit' style="cursor:pointer;width:50px;height:30px;" value='注册' onmouseover='this.style.backgroundColor="#33c";this.style.color="#fff";' onmouseout='this.style.backgroundColor="#ddd";this.style.color="#000";' />
				</div>
			</form>
	</body>
</html>

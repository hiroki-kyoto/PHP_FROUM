<?php
	include('lib/DBHelper.php');
	include('lib/ImageUploader.php');
	
	header("content-type:text/html; charset=utf-8");
	
	$uname_error="";
	$password_error="";
	$validate_error="";
	$email_error="";
	$nickname_error = "";
	$uicon_error = "";
	$age_error = "";
	$gender_error = "";
	$grade_error = "";
	$teacher_error = "";
	$school_error = "";
	$location_error = "";
	
	$legal = true;
	if(!array_key_exists('unm',$_POST)){ $legal=false; }
	if(!array_key_exists('email',$_POST)){ $legal=false; }
	if(!array_key_exists('upwd',$_POST)){ $legal=false; }
	if(!array_key_exists('vldt',$_POST)){ $legal=false; }
	if(!array_key_exists('age',$_POST)){ $legal=false; }
	if(!array_key_exists('gender',$_POST)){ $legal=false; }
	if(!array_key_exists('grade',$_POST)){ $legal=false; }
	if(!array_key_exists('teacher',$_POST)){ $legal=false; }
	if(!array_key_exists('school',$_POST)){ $legal=false; }
	if(!array_key_exists('location',$_POST)){ $legal=false; }
	if(!array_key_exists('nickname',$_POST)){ $legal =false;}
	
	if($legal)
	{
		session_start();
		
		$uname = $_POST['unm'];
		$email = $_POST['email'];
		$password = $_POST['upwd'];
		$validate = $_POST['vldt'];
		$nickname = $_POST['nickname'];
		$age = $_POST['age'];
		$gender = $_POST['gender'];
		$teacher = $_POST['teacher'];
		$school = $_POST['school'];
		$location = $_POST['location'];
		$grade = $_POST['grade'];
		
		// firstly, we deal with the uploaded image
		$types = array("jpg","gif","png");
		if(array_key_exists('uicon',$_FILES)){
			$img = $_FILES['uicon'];
			if($img==null){$icon = $IMAGE_UPLOAD_FOLDER."/"."default.png";}
			else{
				$check_res = ImageUploader::checkType($img, $types);
				$type = $check_res;
				if(strlen($check_res)==3){
					// means the image file is correct
					$check_res1 = ImageUploader::checkSize($img,$type,200, 200);
					if($check_res1){
						// in case of exposing users' information, encode the user name
						$rnd_str = "";
						for($i=0;$i<3;$i++){
							$rnd_str .= chr(mt_rand(97,122));
						}
						$icon = $rnd_str.$uname.'.'.$type;
						$check_res = ImageUploader::saveImage($img, $icon);
						if($check_res!="OK"){
							echo "保存失败：".$check_res;
							return;
						}
						$icon = settings::$IMAGE_UPLOAD_FOLDER.'/'.$rnd_str.$uname.'.'.$type;
					}
					else{
						echo "上传的图片尺寸不符合要求!";
						return;
					}
				}
				else{
					echo "图片的格式不对，请重新挑选头像进行上传, 错误码为： ".$check_res;
					return;
				}
			}	
		}
		else{
			echo "NO PICTURE UPLOADED!";
			$icon = $IMAGE_UPLOAD_FOLDER."/"."default.png";
		}
		
		if(!is_string($age)){$age='0';}
		if(!is_string($gender)){$gender='女';}
		if(!is_string($teacher)){$teacher='-';}
		if(!is_string($school)){$school='-';}
		if(!is_string($location)){$location='-';}
	
		// check
		if(is_string($uname)&&is_string($password)&&is_string($validate)
		&&strlen($uname)&&strlen($password)&&is_string($email)&&is_string($nickname))
		{
			//check validate code
			$std = $_SESSION['signup'];
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
				$nickname = str_replace("\"","0",$nickname);
				$age = str_replace("\"","0",$age);
				$gender = str_replace("\"","0",$gender);
				$grade = str_replace("\"","0",$grade);
				$teacher = str_replace("\"","0",$teacher);
				$school = str_replace("\"","0",$school);
				$location = str_replace("\"","0",$location);
				
				$uname = str_replace("'","0",$uname);
				$email = str_replace("'","0",$email);
				$password = str_replace("'","0",$password);
				$nickname = str_replace("'","0",$nickname);
				$age = str_replace("'","0",$age);
				$gender = str_replace("'","0",$gender);
				$grade = str_replace("'","0",$grade);
				$teacher = str_replace("'","0",$teacher);
				$school = str_replace("'","0",$school);
				$location = str_replace("'","0",$location);
				
				// filter the > and <
				$nickname = str_replace("<","&lt;",$nickname);
				$grade = str_replace("<","&lt;",$grade);
				$teacher = str_replace("<","&lt;",$teacher);
				$school = str_replace("<","&lt;",$school);
				$location = str_replace("<","&lt;",$location);
				
				$nickname = str_replace(">","&gt;",$nickname);
				$grade = str_replace(">","&gt;",$grade);
				$teacher = str_replace(">","&gt;",$teacher);
				$school = str_replace(">","&gt;",$school);
				$location = str_replace(">","&gt;",$location);
			
				if(strlen($uname)>15){
					$uname_error = "too long";
					$legal = false;
				}
				if(preg_match('/[a-zA-Z0-9_-]/',$uname)==0){
					$uname_error = "invalidate";
					$legal = false;
				}
				if(strlen($password)>20){
					$unm_error = "too long";
					$legal = false;
				}
				if(preg_match('/[a-zA-Z0-9_-]/',$password)==0){
					$password_error = "invalidate";
					$legal = false;
				}
				if(strlen($nickname)>50){
					$nickname_error = "too long";
					$legal = false;
				}
				
				if(strlen($email)>30){
					$email_error = "too long";
					$legal = false;
				}
				else{
					if(preg_match( // email regexp
					"/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i",
					$email)==0){
						$email_error = "invaildate";
						$legal = false;
					}
				}
				if(preg_match("/^\d*$/",$age)==0){
					$age_error = "not a number";
					$legal = false;
				}
				else{
					$tmp = $age;
					$age;
					$age = intval($tmp);
				}
				if($gender!='男'&&$gender!='女'){
					$gender_error = "wrong";
					$legal = false;
				}
				if(strlen($grade)>100){
					$grade_error = "too long";
					$legal = false;
				}
				if(strlen($teacher)>100){
					$teacher_error = "too long";
					$legal = false;
				}
				if(strlen($school)>100){
					$school_error = "too long";
					$legal = false;
				}
				if(strlen($location)>100){
					$location_error = "too long";
					$legal = false;
				}
				if($legal){
					$sql= "select * from cl_user where name="."\"".$uname."\"";
					$res= DBHelper::execsql($sql);
		
					//if the user doesn't exist
					if(mysqli_num_rows($res)>0)
					{
						$uname_error= '<font color=red>*</font>taken';
					}
					else
					{
						// register the user
						$now = getdate();
						$d = $now['mday'];
						$m = $now['mon'];
						$y = $now['year'];
						$h = $now['hours'];
						$i = $now['minutes'];
						$s = $now['seconds'];
						$today= "$y-$m-$d $h:$i:$s"; //getdate converted day
						
						$sql= "INSERT INTO cl_user"."(name,password,email,nickname,forbidden,age,gender,".
						"grade,teacher,school,location,coin,regTime,lastActiveTime,message,icon) ". 
						"VALUES ('$uname','$password','$email','$nickname',0,$age,'$gender','$grade','$teacher',".
						"'$school','$location',30,'$today','$today',0,'$icon');";
						$res= DBHelper::execsql($sql);
						// send the request to the super administrator
						$sql= "SELECT id FROM cl_user WHERE name='".$uname."' LIMIT 1;";
						$res= DBHelper::execsql($sql);
						if(mysqli_num_rows($res)==0)
						{
							$uname_error= '<font color=red>*</font>failed';
						}
						else{
							$record = $res->fetch_assoc();
							$uid = $record['id'];
							$_SESSION['uid']= $uid;
							header("Location: index.php");
						}
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
		<title>秉烛论坛注册</title>
		<script type='text/javascript' src='js/jquery.1.6.4.js' ></script>
		<script type='text/javascript'>
		
		function submitForm()
		{
			var un = $('#name').val();
			var pwd = $('#pwd').val();
			var nickname = $('#nickname').val();
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
			if(nickname==""||nickname==null){
				msgbody += ' 昵称 ';
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
		body{background-image:url(pictures/signup_bg.jpg);padding:0px;margin:0px;}
		
		.container{width:600px; height:auto;margin:-50px auto 0px auto;background-color:rgba(0,0,0,0.5);color:#fff;}
			.title{width:200px;height:30px;margin:50px auto 0px auto;color:#fff;font-weight:bold;padding-top:20px;}
			.seg{padding:5px 10px 5px 10px;}
			.right-text{text-align:right;}
			.login-sheet{width:600px;height:100px;margin:0px auto 0px auto;color:#fff;}
			.center{width:72px;height:28px;margin:10px auto 0px auto;}
			.submit{width:50px;height:30px;margin:30px auto 0px auto;}
			.error{color:#ffffff;width:100px;}
		</style>
	</head>
	<body>
			<form id="mFormId" class='container' name="mForm" action='signup.php' enctype="multipart/form-data" method="post" onsubmit="return submitForm();">
				<p class='title'><a href='index.php' style='color:#7860328;text-decoration:none;' >已有帐号？直接登录</a> | 注册</p>
				
				<table class='login-sheet'>
					<tbody>
						<tr><td class='seg right-text'><必填>用户名(<=15)：</td><td><input type="text" id="name" name="unm" /></td><td class='error' ><?php echo $uname_error ?></td></tr>
						<tr><td class='seg right-text'><必填>邮件地址：</td><td><input type="text" id="email" name="email" /></td><td class='error' ><?php echo $email_error ?></td></tr>
						<tr><td class='seg right-text'><必填>设置密码(<=20)：</td><td><input type="password" id="pwd" name="upwd" class="text" /></td><td class='error' ><?php echo $password_error ?></td></tr>
						<tr><td class='seg right-text'>显示密码：</td><td><input type="checkbox" id='switch' value='0' onclick='return switchpwd();' /></td><td class='error' ></td></tr>
						<tr><td class='seg right-text'><必填>昵称：(<=15)</td><td><input type="text" id="nickname" name="nickname" /></td><td class='error' ><?php echo $nickname_error ?></td></tr>
						<tr><td class='seg right-text'>头像(200x200, 支持格式:jpg,png,gif)：</td><td><input type="file" id="uicon" name="uicon" /></td><td class='error' ><?php echo $uicon_error ?></td></tr>
						<tr><td class='seg right-text'>年龄(数字)：</td><td><input type="text" id="age" name="age" /></td><td class='error' ><?php echo $age_error ?></td></tr>
						<tr><td class='seg right-text'>性别(男/女)：</td><td><input type="text" id="gender" name="gender" /></td><td class='error' ><?php echo $gender_error ?></td></tr>
						<tr><td class='seg right-text'>联系地址(<25)：</td><td><input type="text" id="location" name="location" /></td><td class='error' ><?php echo $location_error ?></td></tr>
						<tr><td class='seg right-text'>所在年级(<25)：</td><td><input type="text" id="grade" name="grade" /></td><td class='error' ><?php echo $grade_error ?></td></tr>
						<tr><td class='seg right-text'>您的老师(<25)（如：黄晓明，高飞）：</td><td><input type="text" id="teacher" name="teacher" /></td><td class='error' ><?php echo $teacher_error ?></td></tr>
						<tr><td class='seg right-text'>所在大学(<25)（如：武汉理工）：</td><td><input type="text" id="school" name="school" /></td><td class='error' ><?php echo $school_error ?></td></tr>
						<tr><td class='seg right-text'><必填>验证码：</td><td><input id="vdcode" type="text" name="vldt"/></td><td class='error' ><?php echo $validate_error ?></td></tr>				
					</tbody>
				</table>
				<div class='center'><a href="#"><img id="vdimgck" align="absmiddle" onClick="this.src=this.src+'#'" style="cursor: pointer;" title="看不清？点击更换" src="validatecode.php?source=signup" alt='点击重新加载验证码' /></a></div>
				
				<div class='submit'>
					<input type='submit' style="cursor:pointer;width:50px;height:30px;" value='注册' onmouseover='this.style.backgroundColor="#33c";this.style.color="#fff";' onmouseout='this.style.backgroundColor="#ddd";this.style.color="#000";' />
				</div>
			</form>
	</body>
</html>

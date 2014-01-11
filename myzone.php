<?php 
	include('lib/DBHelper.php');
	include('lib/DateTimeHelper.php');
	
	header("content-type:text/html; charset=utf-8");
	session_start();
	
	if(array_key_exists('uid', $_SESSION)){
		$mode = 1;
		if(!array_key_exists('uid',$_GET)||strlen($_GET['uid'])==0||preg_match("/^\d*$/",$_GET['uid'])==0){
			$u_tosee = $_SESSION['uid'];
			$mode = 2;
		}
		else{
			$u_tosee = $_GET['uid'];
			$mode = 1;
		}
		$uid = $_SESSION['uid'];
		
		if($uid==$u_tosee){
			$mode = 2;
		}
		else{
			$mode = 1;
		}
		
		$sql = "SELECT * FROM cl_user WHERE id='$u_tosee' LIMIT 1;";
		$res = DBHelper::execsql($sql);
		if(mysqli_num_rows($res)==0){
			echo "IT MAKES NO SENSE TO QUERY ABOUT A NOT EVER EXIST PERSON!";
			return;
		}
		$user = $res->fetch_assoc();
		
		// digout friends:
		$sql = "SELECT id,nickname FROM cl_user WHERE id IN (SELECT u1id FROM friendship WHERE u2id=$u_tosee) OR id IN (SELECT u2id FROM friendship WHERE u1id=$u_tosee);";
		$friends_res = DBHelper::execsql($sql);
		
		if($mode==1){
			$user['message'] = '[SECRET]';
			$user['name'] = '[SECRET]';
			$user['email'] = '[SECRET]';
			$user['location'] = '[SECRET]';
			$user['lastActiveTime'] = '[SECRET]';
		}
		
	}
	else{
		echo "您只有登录之后才能查看他/她的信息，因为这是他/她的隐私，请尊重他们，请<a href='index.php'>登录</a>后在查看.如果您没有帐号请<a href='signup.php'>注册</a>.";
		return;
	}
?>

<!DOCTYPE HTML>
<html class="bbs-page">
	<head>
		<meta charset="utf-8">
		<title>秉烛论坛->我的账户</title>
		<meta name="keywords" content="秉烛,论坛,秉烛论坛,大学,大学生,forum,christianland,mainland,student,study,university" />
		<meta name="description" content="这是一个为大陆大学生服务的论坛 This is a forum specially serves the university students from mainland" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta http-equiv="mobile-agent" content="format=xhtml; url=http://m.christianland.com" />
        <style type='text/css'>
		body{color:#FFF;font-family:'微软雅黑';}
		a{color:#FFF;text-decoration:none;outline:none;border-width:0px;}
			.container{width:970px;background-color:#CBCFD0;margin:0px auto;padding:10px;}
				.header{background-color:#A7B0B1;width:940px;height:80px;margin:0px auto;padding:10px;padding-bottom:0px;}
					.logo{margin-top:5px;border-width:0px;}
					.header-right{width:150px;height:50px;margin-top:-50px;margin-left:780px;text-align:center;color:#FFF;font-family:'微软雅黑';}
						.message{width:150px;height:25px;line-height:25px;}
						.message a{color:#CFA;text-decoration:none;outline:none;border-width:0px;}
						.logout{text-decoration:none;color:#FFF;}
				.content{width:888px;min-height:100%;margin:0px auto;margin-top:10px;}
					.path{}
					.master{width:300px;px;height:100%;;background-color:#E1E6E7;margin:0px auto;color:#88C;}
					.master a{color:#A00;}
					.master img{width:120px;height:120px;}
				.tail{}
					.contact-us{width:340px;;margin:20px auto 5px auto;}
					.copyright{width:250px;margin:0px auto;}
					
		</style>
	</head>
    <body>
        <div class='container'>
        	<div class='header'>
            	<a href='index.php'><img src='images/login_10.gif' class='logo' alt='logo' /></a>
                <div class='header-right'>
                	<div class='message'><a href='myzone.php?uid=<?php echo $uid; ?>'>我的账户</a></div>
                    <a href='logout.php' class='logout'>登出</a>
                </div>
            </div>
            
            <div class='content'>
            	<div class='path'><a href='index.php'>首页</a>>><a href='message.php'>我的空间</a></div>
                <div class='master'>
                	<ul>
                		<li class='icon'>头像：<img src='<?php echo $user['icon']; ?>' alt='头像' /></li>
                    	<li class='message'>收到 <a href='message.php'><?php echo $user['message']; ?></a> 条消息</li>
                    	<li class='unm'>用户名:<?php echo $user['name']; ?></li>
                    	<li class='nickname'>昵称:<?php echo $user['nickname']; ?></li>
                        <li class='gender'>GG/MM:<?php echo $user['gender']; ?></li>
                        <li class='age'>今年<?php echo intval($user['age'])+intval(DateTimeHelper::getAgeByBirthday(new DateTime($user['regTime']))); ?>岁</li>
                        <li class='coin'>攒了金币：<?php echo $user['coin']; ?></li>
                        <li class='friends'>朋友：
                        <?php 
                        	while($farr=$friends_res->fetch_assoc()){
                        		echo "<a href='myzone.php?uid=".$farr['id']."'>".$farr['nickname']."</a><br/>";
                        	}
                         ?></li>
                        <li class='email'>email:<?php echo $user['email']; ?></li>
                        <li class='location'>地址：<?php echo $user['location']; ?></li>
                        <li class='grade'>所在大学年级：<?php echo $user['grade']; ?></li>
                        <li class='school'>就读大学：<?php echo $user['school']; ?></li>
                        <li class='teacher'>喜欢的老师：<?php echo $user['teacher']; ?></li>
                        <li class='regTime'>注册时间：<?php echo $user['regTime']; ?></li>
                        <li class='lastActiveTime'>上次登录时间：<?php echo $user['lastActiveTime']; ?></li>
                    </ul>
                    <form class='add-friend' action='add_friend.php?uid=<?php echo $u_tosee; ?>' method='post' >
                    	<input type='hidden' name='act' value='add-friend' />
                    	<input type='submit' name='button' value='加为好友' />
                    </form>
                </div>
            </div>
            <div class='tail'>
            	<p class='contact-us'><i>联系我们 - Email: bruce.v.shung@gmail.com</i></p>
                <p class='copyright'><i>Powered By Christianland Team</i></p>
            </div>
        </div>
    </body>
</html>

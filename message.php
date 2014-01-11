<?php
header("content-type:text/html; charset=utf-8");
include('lib/DBHelper.php');

session_start();
if(array_key_exists('uid',$_SESSION)){
	$uid = $_SESSION['uid'];
	if(preg_match("/^\d*$/",$uid)==0){
		echo "SEVER ERROR! 500 PLEASE REVISIT OUR WEBSITE WHEN THIS BUG IS FIXED. WE WILL FINISHED IT IN 3 WORK DAYS!";
		return;
	}
	$uid = intval($uid);
	$pn = 1;
	$td = 1;
	// get all unread reply:
	$sql = "SELECT id,content,thread,time,si FROM reply WHERE mark=0 AND ti=$uid LIMIT 99;";
	$res = DBHelper::execsql($sql);
}
else{
	echo "404 NOT FOUND! WEBPAGE U REQUESTED NOT EXIST, PLEASE CHECK YOUR INPUT URL! 所要访问的资源不存在，请<a href='index.php'>登录</a>或者<a href='signup.php'>注册</a>后查看！";
	return;
}

?>
<!DOCTYPE HTML>
<html class="bbs-page">
	<head>
		<meta charset="utf-8">
		<title>秉烛论坛->我的消息</title>
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
					.master{width:888px;min-height:100%;background-color:#E1E6E7;margin-top:8px;}
						.master-header{width:888px;min-height:100%;background-color:#8EA3AE;text-align:center;}
						.master-body{width:888px;height:50px;min-height:100%;color:#39C;}
							.user{width:120px;height:30px;text-align:center;}
							.preview{width:480px;height:60px;margin-top:-30px;margin-left:130px;}
							.date{width:120px;height:30px;text-align:center;margin-top:-60px;margin-left:630px;}
							.link{width:120px;height:30px;text-align:center;margin-top:-30px;margin-left:750px;}
					.page-num{width:300px;height:30px;margin:10px auto 5px auto;}
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
            	<div class='path'><a href='index.php'>首页</a>>><a href='message.php'>我的消息</a></div>
            	<div class='master'>
                	<div class='master-header'><pre>回复人                                    内容预览                                      回复时间            链接    </pre></div>
            	<?php
            	while($reply=$res->fetch_assoc()){	
            		$si = $reply['si'];
            		$sql = "SELECT nickname FROM cl_user WHERE id=$si LIMIT 1;";
            		$res1 = DBHelper::execsql($sql);
            		$replyer = $res1->fetch_assoc();
            		
            		echo "
                    <div class='master-body'>
                        <div class='user'>
                        	<a target='_blank' href='myzone.php?uid=$si'>".$replyer['nickname']."</a></div>
                        <div class='preview'>
                        	".mb_substr($reply['content'],10,30,'UTF-8')."...
                        </div>
                        <div class='date'>".$reply['time']."</div>
                        <div class='link' ><a target='_blank' href='content.php?td=".$reply['thread']."&pn=".$pn."&id=".$reply['id']."'>去看看</a></div>
                    </div>
            		";
            	}
            	?>
            		</div>
              
            </div>
            <div class='tail'>
            	<p class='contact-us'><i>联系我们 - Email: bruce.v.shung@gmail.com</i></p>
                <p class='copyright'><i>Powered By Christianland Team</i></p>
            </div>
        </div>
    </body>
</html>

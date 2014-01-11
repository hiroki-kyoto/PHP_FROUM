<?php
header("content-type:text/html; charset=utf-8");
include('lib/DBHelper.php');
include('lib/DateTimeHelper.php');
include('lib/settings.php');

/** global datasource **/
$icon = 'uicon/default.png';
$nickname = 'Bruce';
$gender = '女';
$age = 0;
$coin = 0;
$message = 0;
$login = false;

session_start();

if(array_key_exists('uid', $_SESSION)){
	$login = true;
	$uid = $_SESSION['uid'];
	$sql = "SELECT nickname,icon,gender,age,coin,message FROM cl_user WHERE id='$uid' LIMIT 1;";
	$res = DBHelper::execsql($sql);
	$arr = $res->fetch_assoc();
	
	$icon = $arr['icon'];
	$nickname = $arr['nickname'];
	$gender = $arr['gender'];
	$age = $arr['age'];
	$coin = $arr['coin'];
	$message = $arr['message'];
}

// fill the list of hot topics
$now = new DateTime('now');
$past = DateTimeHelper::subDay($now, settings::$HOT_DAYS);
$fd = $past->format('Y-m-d H:i:s');
$sql = "SELECT id,topic FROM thread WHERE display=1 AND launchTime>='$fd' ORDER BY replyCount DESC LIMIT 9;";
$thread = DBHelper::execsql($sql);
?>

<!DOCTYPE HTML>
<html class="bbs-page">
	<head>
		<meta charset="utf-8">
		<title>秉烛论坛</title>
		<meta name="keywords" content="秉烛,论坛,秉烛论坛,大学,大学生,forum,christianland,mainland,student,study,university" />
		<meta name="description" content="这是一个为大陆大学生服务的论坛 This is a forum specially serves the university students from mainland" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta http-equiv="mobile-agent" content="format=xhtml; url=http://m.christianland.com" />
        <style type='text/css'>
			body{}
			a{text-decoration:none;outline:none;border-width:0px;}
				.middle-domain{width:950px;height:768px;background-color:rgb(193,198,199);margin:0px auto;}
					.head-part{height:90px;width:950px;}
						.logo{background-image:url(images/login_10.gif);width:130px;height:50px;position:absolute;margin-left:30px;margin-top:20px;}
						.search-box{width:216px;height:30px;background-color:#FFF;position:absolute;margin-left:190px;margin-top:32px;padding:0px;color:rgb(145,152,119);}
							.search-input{width:177px;height:30px;border-width:0px;position:absolute;margin-top:0px;margin-left:0px;padding:0px;line-height:30px;}
							.search-button{height:32px;border-width:0px;position:absolute;margin-top:0px;margin-left:185px;padding:0px;}
						.category-span{width:484px;height:34px;position:absolute;margin-top:30px;margin-left:445px;padding:0px;}
							.study{width:74px;height:34px;background-image:url(images/login_updated_15.gif);padding:0px;position:absolute;}
							.study:hover{margin-top:-3px;}
							.entertainment{width:74px;height:34px;background-image:url(images/login_updated_17.gif);padding:0px;margin-left:84px;position:absolute;}
							.entertainment:hover{margin-top:-3px;}
							.volunteer{width:72px;height:34px;background-image:url(images/login_updated_19.gif);padding:0px;margin-left:168px;position:absolute;}
							.volunteer:hover{margin-top:-3px;}
							.summer{width:74px;height:34px;background-image:url(images/login_updated_21.gif);padding:0px;margin-left:252px;position:absolute;}
							.summer:hover{margin-top:-3px;}
							.high-education{width:74px;height:34px;background-image:url(images/login_updated_23.gif);padding:0px;margin-left:336px;position:absolute;}
							.high-education:hover{margin-top:-3px;}
							.oversea-study{width:74px;height:34px;background-image:url(images/login_updated_25.gif);padding:0px;margin-left:420px;position:absolute;}
							.oversea-study:hover{margin-top:-3px;}
					.neck-part-login{width:950px;height:75px;position:absolute;padding:0px;background-color:rgb(160,168,169);margin-top:5px;display:<?php if($login){echo 'block';}else{echo 'none';}  ?>;}
					.user-info{color:#FFF;height:50px;width:250px;text-align:center;margin-left:75px;margin-top:-65px;font-size:smaller;}
					.message{width:300px;height:30px;margin-left:300px;margin-top:-30px;color:#FFF;}
					.message a{color:#FCA;}
					.message a:hover{color:#FAA;}
					.logout{color:#FFF;width:150px;height:70px;margin-left:600px;margin-top:-40px;font-size:smaller;}
					.logout a{color:#FFF;width:150px;}
					.myzone{color:#FFF;width:150px;height:70px;margin-left:800px;margin-top:-70px;font-size:smaller;}
					.myzone a{color:#FFF;width:150px;}
						
					.neck-part-anonymous{width:950px;height:75px;position:absolute;padding:0px;background-color:rgb(160,168,169);margin-top:5px;display:<?php if(!$login){echo 'block';}else{echo 'none';}  ?>;}
						.register-box{width:477px;height:75px;padding:0px;margin-left:0px;margin-top:0px;position:absolute;border-width:0px;}
							.register-btn{width:120px;height:40px;padding:0px;margin-left:10px;margin-top:18px;position:absolute;border-width:0px;}
							.register-des{width:307px;height:25px;padding:0px;margin-left:140px;margin-top:36px;position:absolute;background-image:url(images/register_des.gif);}
						.login-box{width:375px;height:75px;padding:0px;margin-left:554px;margin-top:0px;position:absolute;}
							.user-name{width:126px;height:32px;border-width:0px;background-color:#FFF;position:absolute;margin-left:10px;margin-top:10px;padding:0px;line-height:32px;}
							.user-pwd{width:126px;height:32px;border-width:0px;background-color:#FFF;position:absolute;margin-left:156px;margin-top:10px;padding:0px;line-height:32px;}
							.login-btn{border-width:0px;position:absolute;margin-left:300px;margin-top:8px;padding:0px;}
							.forget-pwd{width:120px;height:17px;margin-left:158px;margin-top:50px;position:absolute;font-size:12px;text-decoration:none;color:rgb(194,211,192);}
					.body-part{}
						.left-wheel{width:317px;height:276px;/***background-image:url(images/wheel.gif);***/position:absolute;padding:0px;margin-top:160px;margin-left:40px;}
							.wheel-item{border-width:0px;}
						.right-list{width:440px;height:440px;background-image:url(images/login_34.gif);position:absolute;padding:0px;margin-top:120px;margin-left:445px;}
							.hot-topics{list-style:none;margin-left:80px;margin-top:58px;padding:0px;}
								.top-item{margin-top:22px;}
									.top-item a {text-decoration:none;color:#4B881B;}
									.top-item a:hover{background-color:#4B881B;color:#FFF;}
						.encouraging-words{width:323px;height:25px;background-image:url(images/login_updated_68.gif);position:absolute;padding:0px;margin-top:500px;margin-left:40px;}
					.tail-part{}
						.contact-info{width:342px;height:22px;margin:610px auto auto auto;color:#FFF;}
						.copyright{width:250px;height:22px;margin:6px auto auto auto;color:#FFF;}
						
		</style>
	</head>
    <body>
        <div class='middle-domain'>
        	<div class='head-part'>
            	<a href='index.php'><div class='logo'></div></a>
                <form class='search-box' action='search.php'>
                	<input type='text' class='search-input' placeholder='搜索关键词' />
                    <input type='image' class='search-button' src='images/login_12.gif' />
                </form>
                <div class='category-span'>
                	<a href='forum.php?tp=1'><div class='study'></div></a>
                    <a href='forum.php?tp=2'><div class='entertainment'></div></a>
                    <a href='forum.php?tp=3'><div class='volunteer'></div></a>
                    <a href='forum.php?tp=4'><div class='summer'></div></a>
                    <a href='forum.php?tp=5'><div class='high-education'></div></a>
                    <a href='forum.php?tp=6'><div class='oversea-study'></div></a>
                </div>
            </div>
            <!-- when user not login begin -->
            <div class='neck-part-anonymous'>
            	<div class='register-box'>
	                <a href='signup.php'><img src="images/register_btn.gif" class='register-btn' /></a>
                    <div class='register-des'></div>
                </div>
                <form class='login-box' action='login.php' method='post' >
                	<input type='hidden' value='index.php' name='last' />
                	<input type='text' value='' placeholder="用户名" name='uname' class='user-name' />
                    <input type='password' value='' name='password' placeholder="密码" class='user-pwd' />
                    <input type='image' src='images/login_updated_42.gif' class='login-btn' />
                    <a href='find_back_pwd.php' class='forget-pwd'>* 忘记密码？</a>
                </form>                
            </div>
            <!-- when user not login end -->
            <!-- when user logedin start -->
            <div class='neck-part-login'>
            	<a href='myzone.php?uid=<?php echo $uid ?>' ><img class='uicon' alt='头像' style='border-width:0px;height:75px;' src='<?php echo $icon; ?>' /></a>
            	<div class='user-info'>
            		<div class='nickname'><?php echo $nickname; ?>(NICKNAME)</div>
            		<div class='age-gender'><?php echo $age; ?>岁(AGE)&nbsp;&nbsp;&nbsp;<?php echo $gender; ?>(GENDER)</div>
            		<div class='coin'>攒了<?php echo $coin; ?>个金币(COIN)</div>
            	</div>
            	<div class='message'>我的消息(MY MESSAGES)(<a href='message.php'> <?php echo $message; ?> </a>)</div>
            	<div class='logout'><a href='logout.php?uid=<?php echo $uid ?>' >登出<br/>(LOG OUT)</a></div>
            	<div class='myzone'><a href='myzone.php?uid=<?php echo $uid ?>' >查看账户<br/>(VIEW ACCOUNT)</a></div>
            </div>
            <!-- when user logedin end -->            
            
            <div class='body-part'>
            	<!-- according to the middle-domain -->
            	<div class='left-wheel' radius='158px'>
                	<a href='forum.php?tp=6'><img class='wheel-item oversea-pie' id='pie1' src='images/pie_oversea.png' style='position:absolute;margin-left:0px;margin-top:0px;' /></a>
                    <a href='forum.php?tp=1'><img class='wheel-item study-pie' id='pie2' src='images/pie_study.png' style='position:absolute;margin-left:79px;margin-top:0px;' /></a>
                    <a href='forum.php?tp=2'><img class='wheel-item entertain-pie' id='pie3' src='images/pie_entertain.png' style='position:absolute;margin-left:158px;margin-top:0px;' /></a>
                    <a href='forum.php?tp=3'><img class='wheel-item volunteer-pie' id='pie4' src='images/pie_volunteer.png' style='position:absolute;margin-left:0px;margin-top:136px;' /></a>
                    <a href='forum.php?tp=4'><img class='wheel-item summer-pie' id='pie5' src='images/pie_summer.png' style='position:absolute;margin-left:79px;margin-top:136px;' /></a>
                    <a href='forum.php?tp=5'><img class='wheel-item highedu-pie' id='pie6' src='images/pie_highedu.png' style='position:absolute;margin-left:158px;margin-top:136px;' /></a>
                </div>
                <div class='right-list'>
                	<ul class='hot-topics'>
                		<?php
                			while($record = $thread->fetch_assoc()){	
	                			$record['topic'] = mb_substr($record['topic'], 0, 18,'UTF-8')." ... ";
	                			echo "<li class='top-item'><a href='content.php?td="
	                			.$record['id']."&pn=1'>"
	                			.$record['topic']
	                			."</a></li>";
                			}
                		?>
                    </ul>
                </div>
                <div class='encouraging-words' ></div>
            </div>
            <div class='tail-part'>
            	<p class='contact-info' >联系我们 - Email:bruce.v.shung@gmail.com </p>
                <p class='copyright' >Powered by Christianland Team</p>
            </div>
        </div>
    </body>
    
    <script type='text/javascript'>
	var pie = [];
	pie[0] = document.getElementById('pie1');
	pie[1] = document.getElementById('pie2');
	pie[2] = document.getElementById('pie3');
	pie[3] = document.getElementById('pie4');
	pie[4] = document.getElementById('pie5');
	pie[5] = document.getElementById('pie6');
	
	pie[0].onmouseover = function(){
		pie[0].style.marginLeft=(parseInt(pie[0].style.marginLeft) -5) + 'px';
		pie[0].style.marginTop=(parseInt(pie[0].style.marginTop) -5) + 'px';
	};
	pie[0].onmouseout = function(){
		pie[0].style.marginLeft=(parseInt(pie[0].style.marginLeft) +5) + 'px';
		pie[0].style.marginTop=(parseInt(pie[0].style.marginTop) +5) + 'px';
	};
	// the second
	pie[1].onmouseover = function(){
		pie[1].style.marginTop=(parseInt(pie[1].style.marginTop) -8) + 'px';
	};
	pie[1].onmouseout = function(){
		pie[1].style.marginTop=(parseInt(pie[1].style.marginTop) +8) + 'px';
	};
	// the third
	pie[2].onmouseover = function(){
		pie[2].style.marginLeft=(parseInt(pie[2].style.marginLeft) +5) + 'px';
		pie[2].style.marginTop=(parseInt(pie[2].style.marginTop) -5) + 'px';
	};
	pie[2].onmouseout = function(){
		pie[2].style.marginLeft=(parseInt(pie[2].style.marginLeft) -5) + 'px';
		pie[2].style.marginTop=(parseInt(pie[2].style.marginTop) +5) + 'px';
	};
	// the fourth
	pie[3].onmouseover = function(){
		pie[3].style.marginLeft=(parseInt(pie[3].style.marginLeft) -5) + 'px';
		pie[3].style.marginTop=(parseInt(pie[3].style.marginTop) +5) + 'px';
	};
	pie[3].onmouseout = function(){
		pie[3].style.marginLeft=(parseInt(pie[3].style.marginLeft) +5) + 'px';
		pie[3].style.marginTop=(parseInt(pie[3].style.marginTop) -5) + 'px';
	};
	// the fifth
	pie[4].onmouseover = function(){
		pie[4].style.marginTop=(parseInt(pie[4].style.marginTop) +8) + 'px';
	};
	pie[4].onmouseout = function(){
		pie[4].style.marginTop=(parseInt(pie[4].style.marginTop) -8) + 'px';
	};
	// the sixth
	pie[5].onmouseover = function(){
		pie[5].style.marginLeft=(parseInt(pie[5].style.marginLeft) +5) + 'px';
		pie[5].style.marginTop=(parseInt(pie[5].style.marginTop) +5) + 'px';
	};
	pie[5].onmouseout = function(){
		pie[5].style.marginLeft=(parseInt(pie[5].style.marginLeft) -5) + 'px';
		pie[5].style.marginTop=(parseInt(pie[5].style.marginTop) -5) + 'px';
	};
	
	</script>

</html>

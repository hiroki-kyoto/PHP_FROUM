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
// determine the category:
$tp = 1;
$forum_name = '学习';
if(array_key_exists('tp', $_GET)){
	switch($_GET['tp']){
		case '1': $forum_name='学习'; $tp = 1; break;	
		case '2': $forum_name='娱乐'; $tp = 2; break;		
		case '3': $forum_name='志愿者'; $tp = 3; break;		
		case '4': $forum_name='夏令营'; $tp = 4; break;		
		case '5': $forum_name='读博研'; $tp = 5; break;						
		case '6': $forum_name='留学'; $tp = 6; break;
		default: $forum_name='学习'; $tp = 1;
	}
}
else{
	$_GET['tp']='1';
}

$pn = 1;
if(array_key_exists('pn',$_GET)){
	$pn = $_GET['pn'];
	if(preg_match("/^\d*$/",$pn)==0){
		$pn = 1;
		$_GET['pn'] = '1';	
	}
	else{
		$pn = intval($pn);
	}
}

// do as the content page do:
// a page for 8 items in list
$sql = "SELECT COUNT(*) AS count FROM thread WHERE type=$tp AND display=1;";
$all = DBHelper::execsql($sql);
$count = $all->fetch_assoc();
$count = $count['count'];
$page_count = 0;
if($count>0){
	$page_count = ($count-1)/8+1;
	$page_count = (int)$page_count;
	if($pn<=0&&$pn>$page_count){
		echo "404 NOT FOUND! THE PAGE U REQUESTED NOT EXIST! PLEASE TURN AROUND OR VISIT HOME PAGE NOW[<a href='index.php'>回到主页</a>]";
		return;
	}
	$sql = "SELECT id,launcher,topic,launchTime,replyCount FROM thread WHERE display=1 AND type=$tp ORDER BY replyCount DESC LIMIT ".(8*($pn-1)).", ".(8*$pn).";";
	$thread = DBHelper::execsql($sql);	
}
else{
	$sql = "SELECT id,launcher,topic,launchTime,replyCount FROM thread WHERE display=1 AND type=$tp;";
	$thread = DBHelper::execsql($sql);
}

?>

<!DOCTYPE HTML>
<html class="bbs-page">
	<head>
		<meta charset="utf-8">
		<title>论坛分区|Forum Partition</title>
		<meta name="keywords" content="秉烛,论坛,秉烛论坛,大学,大学生,forum,christianland,mainland,student,study,university" />
		<meta name="description" content="这是一个为大陆大学生服务的论坛 This is a forum specially serves the university students from mainland" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta http-equiv="mobile-agent" content="format=xhtml; url=http://m.christianland.com" />
        <style type='text/css'>
			body{color:#FFF;font-family:'微软雅黑';}
			a{text-decoration:none;outline:none;border-width:0px;}
				.middle-domain{width:950px;height:auto;background-color:rgb(193,198,199);margin:0px auto;}
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
						.path{color:#FFF;width:200px;height:auto;margin-top:100px;margin-left:50px;}
						.path a{color:#FFC;text-decoration:none;outline:none;border-width:0px;}
						.table-header{color:#FFF;background-color:#066;width:848px;height:30px;margin:0px auto;}
							.theme{width:548px;height:30px;text-align:center;padding:0px;line-height:30px;margin:0px;;}
							.hotness{width:100px;height:30px;margin:-30px auto 0px 548px;text-align:center;padding:0px;line-height:30px;}
							.date{width:200px;height:30px;margin-top:-30px;margin-left:648px;text-align:center;padding:0px;line-height:30px;}							
							.item{width:848px;height:60px;margin:5px auto;background-color:#aca;}
								.icon{width:48px;height:48px;border-width:0px;}
								.preview{width:450px;height:50px;margin-top:-48px;margin-left:70px;font-size:smaller;}
								.preview a{color:#FFF;}
								.preview a:hover{color:#000;}								
								.hot-degree{width:100px;height:50px;line-height:50px;margin-top:-50px;margin-left:580px;}
								.publish-date{width:150px;height:50px;line-height:50px;margin-top:-50px;margin-left:680px;text-align:center;}
						.table-footer{color:#FFF;background-color:#066;width:848px;height:30px;margin:0px auto;text-align:center;cursor:pointer;}
						.table-footer:hover{color:#FCA;}
						.reply-box{width:700px;height:100px;}
								.reply-box-input{width:600px;height:80px;color:#099;margin-left:180px;}
								.rest-words{width:150px;height:30px;margin-top:-50px;margin-left:50px;}
							.page-num{width:300px;height:30px;margin:10px auto 5px auto;}					
								.current{color:#FAA;}
								.non-current{color:#FFF;}
					.tail-part{}
						.contact-info{width:342px;height:22px;margin:10px auto auto auto;color:#FFF;}
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
					<input type='hidden' name='last' value='forum.php?tp=<?php echo $tp; ?>&pn=<?php echo $pn; ?>' />
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
            <div class='path'><a href='index.php'>首页</a>>><a href='forum.php?tp=entertain'><?php echo $forum_name; ?></a></div>
            <div class='body-part'>
            	<div class='table-header'><p class='theme'>主题</p><p class='hotness'>热度</p><p class='date'>日期</p></div>
                
               <?php
                $i = 0;
                $max = 0;
                while($record=$thread->fetch_assoc()){
                	$i++;
                	if($i==1){$max=intval($record['replyCount']);}
                	if($max==0){$max=1;}
                	// get the replyer's information:
                	$sql = "SELECT icon FROM cl_user WHERE id=".$record['launcher'].";";
                	$res = DBHelper::execsql($sql);
                	$user = $res->fetch_assoc();
                	echo "<div class='item'>
                	<a href='myzone.php?uid=".$record['launcher']."'><img src='".$user['icon']."' alt='头像' class='icon' /></a>
                    <div class='preview'><a href='content.php?td=".$record['id']."'>".mb_substr($record['topic'],0,60,'UTF-8')."...</a></div>
                    <div class='hot-degree'>".(int)(intval($record['replyCount'])/$max*100)."%</div>
                    <div class='publish-date'>".$record['launchTime']."</div>
                </div>";	
                	}	
                ?>
                
                <div class='table-footer' onclick='reply(this);' >创建我的话题+</div>
                <form class='reply-box' action='create_thread.php' style='display:none;' method='post' >
                            <input type='hidden' name='tp' value='<?php echo $tp; ?>' /><!-- this item needs to bind with the data source -->
                            <input type='hidden' name='pn' value='<?php echo $pn; ?>' />
                            <textarea class='reply-box-input' name='topic' value='' maxlength=300 onKeyUp='popTip(this);' ></textarea>
                            <div style='width:80px;height:30px;text-align:center;color:#FFF;background-color:#0CC;margin-top:-80px;margin-left:790px;'><input type='image' src='images/submit.jpg' style='border-width:0px;' name='act' value='提交' /></div>
                            <div style='cursor:pointer;width:80px;height:30px;text-align:center;color:#FFF;background-color:#0CC;margin-top:10px;margin-left:790px;' onclick='hide(this.parentNode);'>取消</div>
                            <div class='rest-words'>还可以输入300字</div>
                </form>
                
                <!-- page number exhibition begin -->
                <?php
                /*** start page segment **/
                echo "<!-- PAGE NUMBER CONTROLLER -->";
                	if($page_count==0){
                		echo "<div class='page-num'>还没人创建新主题呢，还不快抢沙发！</div>";
                	}
                	elseif($page_count<=6){
                		echo "<div class='page-num'>";
                		$i = 0;
                		while($i<$page_count){
                			$i++;
                			if($i==$pn){
                				echo "<a class='current' href='forum.php?&tp=$tp&pn=$i'>$i</a>&nbsp;";
                			}
                			else{
                				echo "<a class='non-current' href='forum.php?&tp=$tp&pn=$i'>$i</a>&nbsp;";
                			}
                		}
                		echo "&nbsp;共".$page_count."页&nbsp;&nbsp;</div>";
                	}
                	else{
                		if($pn==1){
                			echo "<div class='page-num'><a class='current' href='forum.php?&tp=$tp&pn=1'>1</a>&nbsp;<a class='non-current' href='forum.php?&tp=$tp&pn=2'>2</a>&nbsp;<a class='non-current' href='forum.php?&tp=$tp&pn=3'>3</a>&nbsp;&nbsp;共$page_count页&nbsp;&nbsp;转到&nbsp;<input type='text' name='pn' value='' style='width:20px;height:20px;' onKeyPress='jump_key();' />&nbsp;页<button onclick='jump()' >跳转</button></div>";	
                		}
                		elseif($pn==$page_count){
                			echo "<div class='page-num'><a class='non-current' href='forum.php?&tp=$tp&pn=".($page_count-2)."'>".($page_count-2)."</a>&nbsp;<a class='non-current' href='forum.php?&tp=$tp&pn=".($page_count-1)."'>".($page_count-1)."</a>&nbsp;<a class='current' href='forum.php?&tp=$tp&pn=".$page_count."'>".$page_count."</a>&nbsp;&nbsp;共$page_count页&nbsp;&nbsp;转到&nbsp;<input type='text' name='pn' value='' style='width:20px;height:20px;' onKeyPress='jump_key();' />&nbsp;页<button onclick='jump()' >跳转</button></div>";	
                		}
                		else{
                			echo "<div class='page-num'><a class='non-current' href='forum.php?&tp=$tp&pn=".($pn-1)."'>".($pn-1)."</a>&nbsp;<a class='current' href='forum.php?&tp=$tp&pn=".$pn."'>".$pn."</a>&nbsp;<a class='non-current' href='forum.php?&tp=$tp&pn=".($pn+1)."'>".($pn+1)."</a>&nbsp;&nbsp;共$page_count页&nbsp;&nbsp;转到&nbsp;<input type='text' name='pn' value='' style='width:20px;height:20px;' onKeyPress='jump_key();' />&nbsp;页<button onclick='jump()' >跳转</button></div>";
                		}	
                	}
                	
                	?>
                	
                <!-- page number exhibition end -->                
            </div>
            <div class='tail-part'>
            	<p class='contact-info' >联系我们 - Email:bruce.v.shung@gmail.com </p>
                <p class='copyright' >Powered by Christianland Team</p>
            </div>
        </div>
    </body>
    <script type='text/javascript'>
	function jump(){
		var pn = document.getElementsByName('pn')[0];
		if(pn.value.length<1){return;}
		this.location = "content.php?td=128&pn="+pn.value;
	};
	
	function jump_key(){
		if(window.event.keyCode==13){
			jump();	
		}
	};
	function reply(that){
		if(<?php if($login){echo '1';}else{echo '0';} ?>==0){
			return;
		}
		var ua = this.navigator.userAgent;
		if(ua.indexOf("MSIE")==-1){that = that.nextSibling.nextSibling;}
		else{
			that = that.nextSibling;	
		}
		that.style.display = 'block';
	};
	
	function hide(that){
		that.style.display = 'none';
	}
	function popTip(that){
		var words = that.value;
		var ua = this.navigator.userAgent;
		if(ua.indexOf("MSIE")==-1){that = that.parentNode.lastChild.previousSibling;}
		else{
			that = that.parentNode.lastChild;
		}
		var rest = 300 - parseInt(words.length);
		that.innerHTML = "还可以输入<font color=RED>"+ rest + "</font>字";
	}
	
	</script>
</html>

<?php
include('lib/DBHelper.php');
header("content-type:text/html; charset=utf-8");
session_start();
$login = false;
$message = 0;
if(array_key_exists('uid', $_SESSION)){
	$login = true;
	$uid = $_SESSION['uid'];
	
	// deal with mesg:
	if(array_key_exists('id',$_GET)){
		$id = $_GET['id'];
		if(preg_match("/^\d*$/",$id)>0){
			// make the user's message decrease:
			$sql = "SELECT ti FROM reply WHERE id=$id AND display=1 AND mark=0 LIMIT 1;";
			$msg = DBHelper::execsql($sql);
			if(mysqli_num_rows($msg)>0){
				$sql = "UPDATE reply SET mark=1 WHERE id=$id;";
				DBHelper::execsql($sql);
				$sql = "UPDATE cl_user SET message=message-1 WHERE id=$uid;";
				DBHelper::execsql($sql);	
			}
		}	
	}
	
	$sql = "SELECT message FROM cl_user WHERE id='$uid' LIMIT 1;";
	$res = DBHelper::execsql($sql);
	$arr = $res->fetch_assoc();
	$message = $arr['message'];
}

/** receive the td and pn as arguments **/
if(!array_key_exists('td',$_GET)){
	$td = 1;
}
else{
	$td = $_GET['td'];
}
if(!array_key_exists('pn',$_GET)){
	$pn = 1;
}
else{
	$pn = $_GET['pn'];
}

// check if the td and pn are both in validate format
if(preg_match("/^\d*$/",$td)==0){
	$td = 1;	
}
else{
	$td = intval($td);
}
if(preg_match("/^\d*$/",$pn)==0){
	$pn = 1;	
}
else{
	$pn = intval($pn);
}

$forum_name_en = '2';
$forum_name_ch = '娱乐';
$thread = array('launcher'=>4,'topic'=>'something');
$launcher = array('gender'=>'男','nickname'=>'天地回合','icon'=>'uicon/default.png','coin'=>30);

// get the thread information:
$sql = "SELECT launcher,topic,type FROM thread WHERE id=$td AND display=1 LIMIT 1;";
$res = DBHelper::execsql($sql);
if(mysqli_num_rows($res)==0){
	echo "404 NOT FOUND! THE PAGE U REQUESTED NOT EXIST! PLEASE TURN AROUND OR VISIT HOME PAGE NOW[<a href='index.php'>回到主页</a>]";
	return;
}
$thread = $res->fetch_assoc();
$forum_name_en = $thread['type'];

switch($forum_name_en){
	case '1': $forum_name_ch='学习'; break;
	case '2': $forum_name_ch='娱乐'; break;		
	case '3': $forum_name_ch='志愿者'; break;		
	case '4': $forum_name_ch='夏令营'; break;		
	case '5': $forum_name_ch='读博研'; break;						
	case '6': $forum_name_ch='留学'; break;
	default: $forum_name_ch='学习';
}


// get the information of the launcher:
$sql = "SELECT gender,nickname,icon,coin FROM cl_user WHERE id=".$thread['launcher']." LIMIT 1;";
$res = DBHelper::execsql($sql);
$launcher = $res->fetch_assoc();

// a page for 8 replys:
$sql = "SELECT COUNT(*) AS count FROM reply WHERE thread=$td AND display=1;";
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
	$sql = "SELECT time,si,content FROM reply WHERE display=1 AND thread=$td LIMIT ".(8*($pn-1)).", ".(8*$pn).";";
	$reply = DBHelper::execsql($sql);	
}
else{
	$sql = "SELECT si,time,content FROM reply WHERE thread=$td AND display=1;";
	$reply = DBHelper::execsql($sql);
}

?>

<!DOCTYPE HTML>
<html class="bbs-page">
	<head>
		<meta charset="utf-8">
		<title>秉烛论坛->帖子</title>
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
						.master-header{width:888px;min-height:100%;background-color:#8EA3AE;text-align:right;}
						.master-body{width:888px;height:200px;min-height:100%;}
							.user-info{width:120px;height:180px;padding:10px 18px 10px 18px;color:#EE6FB2;font-size:smaller;}
							.user-info img{width:100px;height:100px;}
							.reply{width:600px;padding:3px;margin-top:-180px;margin-left:200px;color:#660;font-size:smaller;}
						.master-tail{width:65px;height:32px;background-image:url(images/reply.jpg);margin-left:750px;cursor:pointer;margin-top:-20px;}
							.reply-box{width:700px;height:100px;}
								.reply-box-input{width:600px;height:80px;color:#099;margin-left:180px;}
								.rest-words{width:150px;height:30px;margin-top:-50px;margin-left:50px;}
					.slave{width:888px;min-height:100%;background-color:#E1E6E7;margin-top:8px;}
						.slave-header{width:208px;min-height:100%;background-color:#8EA3AE;text-align:right;margin-left:680px;}
						.slave-body{width:888px;height:200px;min-height:100%;}
						.slave-tail{width:65px;height:32px;background-image:url(images/reply.jpg);margin-left:750px;cursor:pointer;margin-top:-20px;}
					.page-num{width:300px;height:30px;margin:10px auto 5px auto;}
						.current{color:#FAA;}
						.non-current{color:#FFF;}
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
                <?php 
                if($login){ 
                	echo "
                		<div class='message'>我的消息（<a href='message.php'>$message</a>）</div>
                    	<a href='logout.php' class='logout'>登出</a>
                    	";
                }else{
                	echo "
                		<div class='message' ><a href='index.php' >回到主页|登录</a></div>
                    	<a href='signup.php' class='logout'>注册</a>
                		";
                }
                ?>
                </div>
            </div>
            
            <div class='content'>
            	<div class='path'><a href='index.php'>首页</a>>><a href='forum.php?tp=<?php echo $forum_name_en; ?>&pn=1'><?php echo $forum_name_ch; ?></a>>><a href='content.php?td=<?php echo $td; ?>&pn=<?php echo $pn; ?>'>帖子</a></div>
                
                <div class='master'>
                	<A name="<?php echo $thread['launcher']; ?>"></A>
                	<div class='master-header'>楼主&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2014-1-7 12:30&nbsp;&nbsp;&nbsp;</div>
                    <div class='master-body'>
                    	<div class='user-info'>
                        	<a href='myzone.php?uid=<?php echo $thread['launcher']; ?>'><img src='<?php echo $launcher['icon']; ?>' alt='头像' style='border-width:0px;' /></a>
                            <div name='user-name'>昵称：<?php echo $launcher['nickname']; ?></div>
                            <div name='user-gender'>性别：<?php echo $launcher['gender']; ?></div>
                            <div name='user-coin'>金币：<?php echo $launcher['coin']; ?></div>
                        </div>
                        <div class='reply'>
                        	<?php echo $thread['topic']; ?>
                        </div>
                    </div>
                    <div class='master-tail' onclick='reply(this)'></div>
                    <form class='reply-box' action="reply.php" style='display:none;' method='post' >
                            <input type="hidden" name="tu" value="<?php echo $thread['launcher']; ?>" /><!-- this item needs to bind with the data source -->
                            <input type="hidden" name="td" value="<?php echo $td; ?>" /><!-- this item needs to bind with the data source -->
                            <input type="hidden" name="pn" value="<?php echo $pn; ?>" />
                            <textarea class='reply-box-input' name="content" value="" maxlength=300 onKeyUp="popTip(this);" ></textarea>
                            <div style='width:80px;height:30px;text-align:center;color:#FFF;background-color:#0CC;margin-top:-80px;margin-left:790px;'><input type="image" src='images/submit.jpg' style='border-width:0px;' name="act" value="提交" /></div>
                            <div style='cursor:pointer;width:80px;height:30px;text-align:center;color:#FFF;background-color:#0CC;margin-top:10px;margin-left:790px;' onclick='hide(this.parentNode);'>取消</div>
                            <div class='rest-words'>还可以输入300字</div>
                   	</form>
                </div>
                <!-- slave -->
                <?php
                $i = 0;
                while($record=$reply->fetch_assoc()){
                	$i++;
                	// get the replyer's information:
                	$sql = "SELECT nickname,gender,coin,icon FROM cl_user WHERE id=".$record['si'].";";
                	$res = DBHelper::execsql($sql);
                	$replyer = $res->fetch_assoc();
                	
                	echo "
                	<div class='slave'>
            		<A name='".$record['si']."'></A>
                	<div class='slave-header'>".(($pn-1)*8+$i)." 楼&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$record['time']."&nbsp;&nbsp;&nbsp;</div>
                    <div class='slave-body'>
                    	<div class='user-info'>
                        	<a href='myzone.php?uid=".$record['si']."'><img src='".$replyer['icon']."' alt='头像' style='border-width:0px;' /></a>
	                        <div name='user-name'>昵称：".$replyer['nickname']."</div>
    	                    <div name='user-gender'>性别：".$replyer['gender']."</div>
        	                <div name='user-coin'>金币：".$replyer['coin']."</div>
            	        </div>
                	    <div class='reply'>".
                        	$record['content']
                        ."</div>
	               </div>
    	           <div class='slave-tail' onclick='reply(this)'></div>
                   <form class='reply-box' action='reply.php' style='display:none;' method='post' >
                            <input type='hidden' name='tu' value='".$record['si']."' /><!-- this item needs to bind with the data source -->
                            <input type='hidden' name='td' value='$td' /><!-- this item needs to bind with the data source -->
                           	<input type='hidden' name='pn' value='$pn' />
                            <textarea class='reply-box-input' name='content' value='' maxlength=300 onKeyUp='popTip(this);' ></textarea>
                            <div style='width:80px;height:30px;text-align:center;color:#FFF;background-color:#0CC;margin-top:-80px;margin-left:790px;'><input type='image' src='images/submit.jpg' style='border-width:0px;' name='act' value='提交' /></div>
                            <div style='cursor:pointer;width:80px;height:30px;text-align:center;color:#FFF;background-color:#0CC;margin-top:10px;margin-left:790px;' onclick='hide(this.parentNode);'>取消</div>
                            <div class='rest-words'>还可以输入300字</div>
                   	</form>
        	    </div>
                	";
                }
                
                
                echo "<!-- PAGE NUMBER CONTROLLER -->";
                 
                	if($page_count==0){
                		echo "<div class='page-num'>还没人回复呢，还不快抢沙发！</div>";
                	}
                	elseif($page_count<=6){
                		echo "<div class='page-num'>";
                		$i = 0;
                		while($i<$page_count){
                			$i++;
                			if($i==$pn){
                				echo "<a class='current' href='content.php?&td=$td&pn=$i'>$i</a>&nbsp;";
                			}
                			else{
                				echo "<a class='non-current' href='content.php?&td=$td&pn=$i'>$i</a>&nbsp;";
                			}
                		}
                		echo "&nbsp;共".$page_count."页&nbsp;&nbsp;</div>";
                	}
                	else{
                		if($pn==1){
                			echo "<div class='page-num'><a class='current' href='content.php?&td=$td&pn=1'>1</a>&nbsp;<a class='non-current' href='content.php?&td=$td&pn=2'>2</a>&nbsp;<a class='non-current' href='content.php?&td=$td&pn=3'>3</a>&nbsp;&nbsp;共$page_count页&nbsp;&nbsp;转到&nbsp;<input type='text' name='pn' value='' style='width:20px;height:20px;' onKeyPress='jump_key();' />&nbsp;页<button onclick='jump()' >跳转</button></div>";	
                		}
                		elseif($pn==$page_count){
                			echo "<div class='page-num'><a class='non-current' href='content.php?&td=$td&pn=".($page_count-2)."'>".($page_count-2)."</a>&nbsp;<a class='non-current' href='content.php?&td=$td&pn=".($page_count-1)."'>".($page_count-1)."</a>&nbsp;<a class='current' href='content.php?&td=$td&pn=".$page_count."'>".$page_count."</a>&nbsp;&nbsp;共$page_count页&nbsp;&nbsp;转到&nbsp;<input type='text' name='pn' value='' style='width:20px;height:20px;' onKeyPress='jump_key();' />&nbsp;页<button onclick='jump()' >跳转</button></div>";	
                		}
                		else{
                			echo "<div class='page-num'><a class='non-current' href='content.php?&td=$td&pn=".($pn-1)."'>".($pn-1)."</a>&nbsp;<a class='current' href='content.php?&td=$td&pn=".$pn."'>".$pn."</a>&nbsp;<a class='non-current' href='content.php?&td=$td&pn=".($pn+1)."'>".($pn+1)."</a>&nbsp;&nbsp;共$page_count页&nbsp;&nbsp;转到&nbsp;<input type='text' name='pn' value='' style='width:20px;height:20px;' onKeyPress='jump_key();' />&nbsp;页<button onclick='jump()' >跳转</button></div>";
                		}	
                	}
                	
                ?>
                
            </div>
            <div class='tail'>
            	<p class='contact-us'><i>联系我们 - Email: bruce.v.shung@gmail.com</i></p>
                <p class='copyright'><i>Powered By Christianland Team</i></p>
            </div>
        </div>
    </body>
    <script type='text/javascript'>
	function jump(){
		var pn = document.getElementsByName('pn')[0];
		if(pn.value.length<1){return;}
		this.location = "content.php?td=128&pn="+pn.value;
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
	
	function jump_key(){
		if(window.event.keyCode==13){
			jump();	
		}
	}
	
	</script>
</html>

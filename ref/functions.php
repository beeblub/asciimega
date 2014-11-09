<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
//contains functions used to generalize stuff
function create_view($width,$height,$url,$title,$description,$views,$authorname,$comments,$filesize,$frames,$userpicurl,$single)
{
	//$prestr = "http://tomatenbrei.cloudapp.net/ascii/";
	$divname = 'animationtable';

	//single is used to define whether page.php treats the page as a search result or as a single animation
	if($single)
		$divname = 'animationtable_single';

	//ugly design magic
	echo "<div name='".$divname."' style='display:inline-block;background-color:white;padding:10px;margin:10px;'>
<table cellpadding='0' cellspacing='0' border='0'  width='auto'><tr><td><center>";
	echo "<iframe src='embet?a=".$url."&preview=1' name='animationframe' id='".$url."' width='".$width."' height='".$height."' frameborder='0' allowFullScreen>Guckst du</iframe>";
	echo "</center></td></tr><tr><td style='background-color:white;opacity:0.9;'><div style='margin:5px;'>";
	echo "<i style='color:black;cursor:pointer;' onclick = \"window.location = 'watch?a=".$url."';\" style='font-size:large;'>".$title."</i>";

	$fontsize = "x-small";
	if($single)
		$fontsize = "small";

	echo "<br><div onclick = \"window.location = 'watch?a=".$url."';\" style='color:gray;font-size:small;margin:5px;max-width:360px;word-wrap: break-word; word-break: break-all;'>".$description."<br><br></td></tr><tr style='color:black;background-color:rgba(255,255,255,0.2)'><td><div onclick = \"window.location = 'watch?a=".$url."';\" style='display:inline-block;width:50%;text-align:left;font-size:".$fontsize.";'>Views: ".$views."<br>Comments: ".$comments."</div><div onclick = \"window.location = 'profile?n=$authorname';\" style='display:inline-block;width:50%;text-align:right;font-size:".$fontsize.";'><div style='padding-left:5px' ><img src='".get_picurl_small($userpicurl)."' class='nosmooth' height='50px'></div>by ".$authorname."</div></div></td></img></div>";
	if($single)
		echo "<tr><td><br>Traffic: ".(($views*$filesize))." Bytes</td></tr>";
	echo "</table></div>";
}
function get_picurl($picurl)
{
	return get_picurl_s($picurl,-1);
}
//for comments
function get_picurl_small($picurl)
{
	return get_picurl_s($picurl,100);
}
function get_picurl_s($picurl,$size)
{
	//$size from 100,300,500,700,1000
	//0 = native
	//-1 = standart
	if($size == -1)
		return "img?f=$picurl";
	else
		return "img?f=$picurl&s=".$size."x".$size;
}
//w and h of preview
function create_commentblock($link,$sqlcommand,$numcomments,$width,$height,$animationid)
{
		$command = $sqlcommand;

		$result = mysqli_query($link,$command);

		$ccount = 0;
		
		//comment header (possebility to post comments)
		echo "<center><div style='padding:30px;background-color:rgba(255,255,255,0.3);'>";
		echo "<font style='font-size:30px;'><i>Comments:</i></font><br>";
		if($animationid != -1)
		echo "<a style='color:blue;' href='postcomment.php?id=".$animationid."'>Write a comment</a><br><br>";
		//display each comment
		while ($row = mysqli_fetch_assoc($result)) {
			$ccount++;
			$animationid = strip_tags($row['id']);
		    	$content = strip_tags($row['content']);
			$content_url_animation = strip_tags($row['content_url_animation']);
			$name_author = strip_tags($row['name']);
			$pic = strip_tags($row['pic']);

			$author_name = $name_author;

			create_comment($pic,($author_name),$content,$content_url_animation,$width,$height);
			echo "<br>";
		}
		//no comments there
		if($ccount == 0)
		{
			echo "<font style='font-size:20px;'>There are none yet.</font><br>";
		}
		echo "</div></center>";
}

function create_comment($picurl,$authorname,$content,$content_url_animation,$width,$height)
{
	echo "<div class='comment'>"
	."<table><tr><td><img src='".get_picurl_small($picurl)."' class='nosmooth' width='50px'></img></td>"."<td><div onclick = \"window.location = 'profile?n=$authorname';\">".$authorname.":</div><br>".$content."<br>";
	if($content_url_animation != NULL)
	{
		//check if exists. else: dont add an animation. Easy as that
		echo "<iframe src='request?a=".$content_url_animation."&o=ap' name='animationframe' id='".$content_url_animation."' width='".$width."' height='".$height."' frameborder='0' allowFullScreen>Guckst du</iframe><br><a style='color:black;' href='watch?a=".$content_url_animation."'>$content_url_animation</a>";
	}
	echo "</td></tr></table></div>";
}

function create_message($message,$single)
{
	$divname = 'animationtable';
	if($single)
		$divname = 'animationtable_single';
	echo "<div name='".$divname."' style='display:inline-block;background-color:white;padding:10px;margin:10px;'><center>".$message."</center></div>";
}
//--------------------------------
//Login Functions
function check_exists($name,$pw)
{	

	include("ref/mysql_connect.php");
	$command = "SELECT id from users where name='".mysqli_real_escape_string($link,$name)."' and password='".mysqli_real_escape_string($link,md5($pw))."';";
	$result = mysqli_query($link,$command);
	$userid = -1;
	while ($row = mysqli_fetch_assoc($result)) {
		$userid = $row['id'];
	}
	mysqli_close($link);
	return $userid;
}
function create_user($name,$pw)
{
	$res = check_exists($name,$pw);
	
	if($res == -1)
	{
		include("ref/mysql_connect.php");
		$command = "INSERT INTO users (name, password,pic) VALUES ('".mysqli_real_escape_string($link,$name)."', '".mysqli_real_escape_string($link,md5($pw))."','a.png');";

		$result = mysqli_query($link,$command);

		if(mysqli_affected_rows($link) == 1){
		    echo "Your account '$name' has been created. You can now log in.";
		}
		mysqli_close($link);
	}
	else
	{
		echo "An account with this name does already exist!";
	}
}
function setuserdata($uid,$uname)
{
	session_start();
	if(!isset($_SESSION['userid']))
	{
		$_SESSION['userid'] = $uid;
		$_SESSION['username'] = $uname;
	}
}
function getuserid()
{
	session_start();
	if(!isset($_SESSION['userid']))
	{
		//there is no userid.. 
		return -1;
	}
	else
	{
		return $_SESSION['userid'];
	}
}
function getusername()
{
	session_start();
	if(!isset($_SESSION['username']))
	{
		//there is no userid.. 
		return -1;
	}
	else
	{
		return $_SESSION['username'];
	}
}
function logout()
{
	session_start();
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
}
?>

<?php
//contains functions used to generalize stuff
function create_view($width,$height,$url,$title,$description,$views,$authorname,$comments,$filesize,$frames,$single)
{	
	//$prestr = "http://tomatenbrei.cloudapp.net/ascii/";
	$divname = 'animationtable';

	//single is used to define whether page.php treats the page as a search result or as a single animation
	if($single)
		$divname = 'animationtable_single';

	//ugly design magic
	echo "<div name='".$divname."' style='display:inline-block;background-color:white;padding:10px;margin:10px;'>
<table cellpadding='0' cellspacing='0' border='0'  width='auto'><tr><td><center>";
	echo "<iframe src='request?a=".$url."&o=ap' name='animationframe' id='".$url."' width='".$width."' height='".$height."' frameborder='0' allowFullScreen>Guckst du</iframe>";
	echo "</center></td></tr><tr><td style='background-color:white;opacity:0.9;'><div style='margin:5px;'>";
	echo "<i style='color:black;cursor:pointer;' onclick = \"window.location = 'watch?a=".$url."';\" style='font-size:large;'>".$title."</i>";

	$fontsize = "x-small";
	if($single)
		$fontsize = "small";

	echo "<br><div onclick = \"window.location = 'watch?a=".$url."';\" style='color:gray;font-size:small;margin:5px;max-width:".$width."px;word-wrap: break-word; word-break: break-all;'>".$description."<br><br></td></tr><tr><td><div onclick = \"window.location = 'watch?a=".$url."';\" style='font-size:".$fontsize.";'>Views: ".$views."</div></td></tr><tr><td><div onclick = \"window.location = 'watch?a=".$url."';\" style='display:inline-block;width:50%;text-align:left;font-size:".$fontsize.";'>Comments: ".$comments."<br></div><div onclick = \"window.location = 'watch?a=".$url."';\" style='display:inline-block;width:50%;text-align:right;font-size:".$fontsize.";'>by ".$authorname."<br></div></div></div></td></tr>";
	if($single)
		echo "<tr><td><br>Traffic: ".(($views*$filesize))." Bytes</td></tr>";
	echo "</table></div>";
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
function setuserdata($uid,$uname)
{
	session_start();
	if(!isset($_SESSION['userid']))
	{
		$_SESSION['userid'] = $uid;
		$_SESSION['username'] = $uid;
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

<style>
#tablewrapper {background-color:rgba(0,0,0,0.5);}

#namediv {
    font-size:80px;height:100px;background-color:rgba(255,255,255,0.5);
}
.proftable
{
	border: none;
	border-collapse: collapse;
	padding: 0;
	margin: 0;
}
.lefthalf
{
position:absolute;width:50%;left:0px;
}
.righthalf
{
position:absolute;width:50%;left:50%;
}
.description
{
color:black;
background-color:rgba(255,255,255,0.3);
}
</style>
<center>
<?php
$name = strip_tags($_GET['n']);
include("mysql_connect.php");
include_once("functions.php");
$command = "Select name,description,pic from users where name = '".mysqli_real_escape_string($link,$name)."';";
$result = mysqli_query($link,$command);

$description = "";
$pic = "";

$exists = false;

while ($row = mysqli_fetch_assoc($result)) {
	$exists = true;
	$description = strip_tags($row['description']);
	$pic = strip_tags($row['pic']);
}
if($exists == false)
{
	create_message("The user with name '".$name."' does not exist.");
	mysqli_close($link);
}
else
{
$picsrc = get_picurl($pic);
echo "
<div>
<div id='tablewrapper'>
<table class='proftable'>
<tr>
<td>
<img src='$picsrc' class='nosmooth' width='300px'></img>
</td>
<td>
<div id='namediv'>
$name
</div>
</td>
</div>
</table>
</div>
<br>
<center>
<div class='description'>
$description
</div>
</center>
<br>
<div class='lefthalf'>
<center>
<div name='animationtable'>
Latest Comments:
</div>";
	create_commentblock($link,"Select content,content_url_animation,name,pic from comments,users where users.name='".mysqli_real_escape_string($link,$name)."' and users.id = comments.id_author order by timestamp desc limit 200;",$numcomments,300,160,-1);
	mysqli_close($link);
echo "
</center>
</div>
<div class='righthalf'>
<div name='animationtable'>
Latest Videos:
</div>";

	//we emulate user input search name
	$_GET['o'] = "n";
	$_GET['t'] = $name;
	$_GET['limit'] = 5;
	include("ref/search_style.php");
echo "</center>
</div>";
}
?>
</center>
